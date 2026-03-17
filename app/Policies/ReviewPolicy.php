<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function create(User $user, Book $book): bool
    {
        if (! $user->hasVerifiedEmail()) {
            return false;
        }

        $hasCompletedOrder = $user->orders()
            ->where('status', 'completed')
            ->whereHas('items', fn ($q) => $q->where('book_id', $book->id))
            ->exists();

        $alreadyReviewed = Review::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->exists();

        return $hasCompletedOrder && ! $alreadyReviewed;
    }
}
