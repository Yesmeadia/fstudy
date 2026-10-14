<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorCodeNotification extends Notification
{
    use Queueable;

    public function __construct(public string $code) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $orgName = config('app.name', 'YES INDIA FOUNDATION');

        return (new MailMessage)
            ->subject("[Security Verification] Your Authentication Code: {$this->code} — {$orgName}")
            ->view('emails.auth.two-factor-code', [
                'user' => $notifiable,
                'code' => $this->code,
            ]);
    }
}
