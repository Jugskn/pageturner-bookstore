<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification
{
    use Queueable;

    public function __construct(public Review $review) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Review Submitted')
            ->greeting('Hello Admin,')
            ->line("A new review has been submitted by **{$this->review->user->name}**.")
            ->line("**Book:** {$this->review->book->title}")
            ->line("**Rating:** {$this->review->rating}/5")
            ->action('View Book', route('books.show', $this->review->book))
            ->line('Please review the content.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'review_id' => $this->review->id,
            'book_title' => $this->review->book->title,
            'user_name' => $this->review->user->name,
            'rating' => $this->review->rating,
            'message' => "{$this->review->user->name} reviewed {$this->review->book->title}.",
        ];
    }
}
