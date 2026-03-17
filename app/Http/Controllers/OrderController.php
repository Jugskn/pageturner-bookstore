<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use App\Notifications\OrderPlacedNotification;
use App\Notifications\OrderStatusChangedNotification;
use App\Notifications\NewOrderForAdminNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.book')
            ->latest('placed_at')
            ->latest('created_at')
            ->get();

        $reviewedBookIds = Review::where('user_id', Auth::id())
            ->pluck('book_id')
            ->toArray();

        return view('orders.index', compact('orders', 'reviewedBookIds'));
    }

    public function store(StoreOrderRequest $request)
    {
        $this->authorize('create', Order::class);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('status', 'Your cart is empty.');
        }

        $bookIds = array_keys($cart);
        $books = Book::whereIn('id', $bookIds)->get()->keyBy('id');

        if ($books->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('status', 'The books in your cart are no longer available.');
        }

        $validated = $request->validated();

        DB::transaction(function () use ($cart, $books, $validated, &$order) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'shipped',
                'total_amount' => 0,
                'shipping_address' => $validated['shipping_address'],
                'placed_at' => now(),
            ]);

            $total = 0;

            foreach ($cart as $bookId => $row) {
                if (! isset($books[$bookId])) {
                    continue;
                }

                $book = $books[$bookId];
                $quantity = $row['quantity'] ?? 1;
                $unitPrice = $row['price'] ?? $book->price;
                $lineTotal = $quantity * $unitPrice;
                $total += $lineTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $book->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ]);
            }

            $order->update(['total_amount' => $total]);
        });

        session()->forget('cart');

        try {
            $order->load('user');
            Auth::user()->notify(new OrderPlacedNotification($order));

            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                sleep(1);
                $admin->notify(new NewOrderForAdminNotification($order));
            }
        } catch (\Exception $e) {
            Log::warning('Order notification failed: ' . $e->getMessage());
        }

        return redirect()
            ->route('orders.show', $order)
            ->with('status', 'Order placed successfully.');
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load('items.book');

        $reviewedBookIds = Review::where('user_id', Auth::id())
            ->pluck('book_id')
            ->toArray();

        return view('orders.show', compact('order', 'reviewedBookIds'));
    }

    public function cancel(Order $order)
    {
        $this->authorize('view', $order);

        if ($order->status !== 'shipped') {
            return back()->with('status', 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new OrderStatusChangedNotification($order, 'shipped'));
            }
        } catch (\Exception $e) {
            Log::warning('Cancel notification failed: ' . $e->getMessage());
        }

        return back()->with('status', 'Order has been cancelled.');
    }

    public function receive(Order $order)
    {
        $this->authorize('view', $order);

        if ($order->status !== 'shipped') {
            return back()->with('status', 'Only shipped orders can be marked as received.');
        }

        $order->update(['status' => 'completed']);

        return redirect()
            ->route('orders.show', $order)
            ->with('status', 'Order completed! You can now write a review or buy again.');
    }

    public function buyAgain(Order $order)
    {
        $this->authorize('view', $order);

        $order->load('items.book');
        $cart = session('cart', []);

        foreach ($order->items as $item) {
            if ($item->book && $item->book->status === 'available') {
                $cart[$item->book_id] = [
                    'quantity' => $item->quantity,
                    'price' => $item->book->price,
                ];
            }
        }

        session(['cart' => $cart]);

        return redirect()
            ->route('cart.index')
            ->with('status', 'Items from your previous order have been added to your cart.');
    }

    public function adminIndex()
    {
        $this->authorize('create', Book::class);

        $orders = Order::with('user', 'items.book')
            ->latest('placed_at')
            ->latest('created_at')
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function adminShow(Order $order)
    {
        $this->authorize('updateStatus', $order);

        $order->load('user', 'items.book');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('updateStatus', $order);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,shipped,completed,cancelled'],
        ]);

        $oldStatus = $order->status;

        $order->update([
            'status' => $validated['status'],
        ]);

        try {
            $order->user->notify(new OrderStatusChangedNotification($order, $oldStatus));
        } catch (\Exception $e) {
            Log::warning('Order status notification failed: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('status', 'Order status updated.');
    }
}
