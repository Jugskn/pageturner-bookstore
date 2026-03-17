<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use App\Notifications\NewReviewNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    public function create(Order $order)
    {
        $this->authorize('view', $order);

        if ($order->status !== 'completed') {
            return redirect()
                ->route('orders.show', $order)
                ->with('status', 'You can only review completed orders.');
        }

        $order->load('items.book');

        $existingReviews = Review::where('user_id', Auth::id())
            ->whereIn('book_id', $order->items->pluck('book_id'))
            ->get()
            ->keyBy('book_id');

        return view('orders.review', compact('order', 'existingReviews'));
    }

    public function store(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        if ($order->status !== 'completed') {
            return redirect()
                ->route('orders.show', $order)
                ->with('status', 'You can only review completed orders.');
        }

        $order->load('items.book');
        $bookIds = $order->items->pluck('book_id')->toArray();

        $validated = $request->validate([
            'reviews' => ['required', 'array', 'min:1'],
            'reviews.*.book_id' => ['required', 'integer', 'in:' . implode(',', $bookIds)],
            'reviews.*.rating' => ['required', 'integer', 'min:1', 'max:5'],
            'reviews.*.comment' => ['nullable', 'string', 'max:2000'],
        ]);

        foreach ($validated['reviews'] as $data) {
            $existing = Review::where('user_id', Auth::id())
                ->where('book_id', $data['book_id'])
                ->first();

            if ($existing) {
                $existing->update([
                    'rating' => $data['rating'],
                    'comment' => $data['comment'] ?? null,
                ]);
                continue;
            }

            $review = Review::create([
                'user_id' => Auth::id(),
                'book_id' => $data['book_id'],
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]);

            try {
                $review->load(['user', 'book']);
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new NewReviewNotification($review));
                }
            } catch (\Exception $e) {
                Log::warning('Review notification failed: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('orders.show', $order)
            ->with('status', 'Your reviews have been saved!');
    }
}
