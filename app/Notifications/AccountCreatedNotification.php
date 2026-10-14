<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public User $user, public string $roleName) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $orgName = config('app.name', 'YES INDIA FOUNDATION');

        return (new MailMessage)
            ->subject("[Welcome] Your Account Access — {$orgName}")
            ->view('emails.auth.account-created', [
                'user' => $this->user,
                'roleName' => $this->roleName,
            ]);
    }
}
