<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginAlertNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $ipAddress,
        public string $userAgent,
        public string $loginTime
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $orgName = config('app.name', 'YES INDIA FOUNDATION');

        return (new MailMessage)
            ->subject("[Security Alert] Successful Account Login — {$orgName}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your {$orgName} portal account was just accessed with the following session details:")
            ->line("**Time:** {$this->loginTime}")
            ->line("**IP Address:** {$this->ipAddress}")
            ->line("**Device / Browser:** {$this->userAgent}")
            ->action('Review Account Activity', route('profile.edit'))
            ->line('If this was you, you can safely disregard this email. If you did NOT initiate this login, please change your password immediately and contact your system administrator.')
            ->salutation("Best regards,\nSecurity Directorate & IT Team\n{$orgName}");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'security_login_alert',
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'login_time' => $this->loginTime,
            'message' => "New login detected from IP {$this->ipAddress} at {$this->loginTime}.",
        ];
    }
}
