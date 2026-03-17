<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderForAdminNotification extends Notification
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
            ->subject('New Order Received — #' . $this->order->id)
            ->greeting('Hello Admin,')
            ->line('A new order has been placed.')
            ->line("**Order #:** {$this->order->id}")
            ->line("**Customer:** {$this->order->user->name}")
            ->line("**Total:** ₱" . number_format($this->order->total_amount, 2))
            ->action('View Order', route('admin.orders.show', $this->order))
            ->line('Please review and process the order.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'message' => "New order #{$this->order->id} from {$this->order->user->name}.",
            'total' => $this->order->total_amount,
        ];
    }
}
