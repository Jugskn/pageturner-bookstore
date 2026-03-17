<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order Confirmed — #' . $this->order->id)
            ->greeting("Hello {$notifiable->name},")
            ->line('Your order has been placed successfully!')
            ->line("**Order #:** {$this->order->id}")
            ->line("**Total:** ₱" . number_format($this->order->total_amount, 2))
            ->action('View Order', route('orders.show', $this->order))
            ->line('Thank you for shopping with PageTurner!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'message' => "Your order #{$this->order->id} has been placed.",
            'total' => $this->order->total_amount,
        ];
    }
}
