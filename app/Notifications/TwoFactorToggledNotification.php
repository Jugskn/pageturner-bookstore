<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorToggledNotification extends Notification
{
    use Queueable;

    public function __construct(public bool $enabled) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->enabled ? 'enabled' : 'disabled';

        return (new MailMessage)
            ->subject("Two-Factor Authentication {$status}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Two-factor authentication has been **{$status}** on your account.")
            ->line($this->enabled
                ? 'Your account is now more secure. You will be asked for a verification code when logging in.'
                : 'Your account is no longer protected by two-factor authentication.')
            ->line('If you did not make this change, please contact support immediately.');
    }
}
