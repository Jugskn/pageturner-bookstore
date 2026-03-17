<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order, public string $oldStatus) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Order #{$this->order->id} Status Updated")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your order **#{$this->order->id}** status has been updated.")
            ->line("**From:** " . ucfirst($this->oldStatus))
            ->line("**To:** " . ucfirst($this->order->status))
            ->action('View Order', route('orders.show', $this->order))
            ->line('Thank you for shopping with PageTurner!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'message' => "Order #{$this->order->id} is now {$this->order->status}.",
            'old_status' => $this->oldStatus,
            'new_status' => $this->order->status,
        ];
    }
}
