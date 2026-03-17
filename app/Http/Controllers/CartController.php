<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\UpdateCartItemRequest;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        $bookIds = array_keys($cart);
        $books = Book::whereIn('id', $bookIds)->get()->keyBy('id');

        $items = [];
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

            $items[] = [
                'book' => $book,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
            ];
        }

        return view('cart.index', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function add(UpdateCartItemRequest $request, Book $book)
    {
        $validated = $request->validated();
        $cart = session('cart', []);
        $currentQty = $cart[$book->id]['quantity'] ?? 0;

        $cart[$book->id] = [
            'quantity' => $currentQty + $validated['quantity'],
            'price' => $book->price,
        ];

        session(['cart' => $cart]);

        return redirect()
            ->route('cart.index')
            ->with('status', 'Book added to your cart.');
    }

    public function update(UpdateCartItemRequest $request, Book $book)
    {
        $validated = $request->validated();
        $cart = session('cart', []);

        if (! isset($cart[$book->id])) {
            return redirect()
                ->route('cart.index')
                ->with('status', 'Item not found in cart.');
        }

        $cart[$book->id]['quantity'] = $validated['quantity'];
        session(['cart' => $cart]);

        return redirect()
            ->route('cart.index')
            ->with('status', 'Cart updated.');
    }

    public function remove(Book $book)
    {
        $cart = session('cart', []);
        unset($cart[$book->id]);
        session(['cart' => $cart]);

        return redirect()
            ->route('cart.index')
            ->with('status', 'Item removed from cart.');
    }
}

