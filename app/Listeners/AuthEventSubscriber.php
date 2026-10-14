<?php

namespace App\Listeners;

use App\Models\AuditLog;
use App\Notifications\LoginAlertNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Request;

class AuthEventSubscriber
{
    public function handleUserLogin(Login $event): void
    {
        $user = $event->user;
        $ip = Request::ip() ?? '127.0.0.1';
        $agent = Request::header('User-Agent') ?? 'Unknown Browser';
        $time = now()->format('d M Y, h:i:s A T');

        // 1. Update user model
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ]);

        // 2. Audit Trail
        AuditLog::log(
            action: 'user_login',
            model: $user,
            newValues: [
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->roles->first()?->name ?? 'User',
                'ip' => $ip,
                'user_agent' => $agent,
            ]
        );

        // 3. Send Security Email Alert
        try {
            $user->notify(new LoginAlertNotification($ip, $agent, $time));
        } catch (\Throwable $e) {
            // Non-blocking if mail service has transient delay
        }
    }

    public function handleUserLogout(Logout $event): void
    {
        if ($user = $event->user) {
            AuditLog::log(
                action: 'user_logout',
                model: $user,
                oldValues: [
                    'email' => $user->email,
                    'name' => $user->name,
                ]
            );
        }
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleUserLogin',
            Logout::class => 'handleUserLogout',
        ];
    }
}
