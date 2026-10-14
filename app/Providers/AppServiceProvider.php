<?php

namespace App\Providers;

use App\Listeners\AuthEventSubscriber;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enforce HTTPS transport in production or when accessed via secure proxies
        if (app()->environment('production') || request()->server('HTTP_X_FORWARDED_PROTO') === 'https' || request()->isSecure()) {
            URL::forceScheme('https');
        }

        // Custom branded HTML template for Password Reset emails
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $orgName = config('app.name', 'YES INDIA FOUNDATION');
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $expire = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

            return (new MailMessage)
                ->subject("[Security] Reset Your Account Password — {$orgName}")
                ->view('emails.auth.reset-password', [
                    'user' => $notifiable,
                    'actionUrl' => $url,
                    'count' => $expire,
                ]);
        });

        Event::subscribe(AuthEventSubscriber::class);

        // User-based and IP-based Dual Rate Limiter for Login
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email');
            $ip = (string) $request->ip();

            return [
                // 1. IP-based limit: max 10 attempts per minute
                Limit::perMinute(10)->by($ip)->response(function () {
                    return back()->withInput()->withErrors([
                        'email' => 'Security Notice: Too many login attempts from this IP address. Access temporarily throttled.',
                    ]);
                }),

                // 2. User identifier + IP limit: max 5 attempts per minute
                Limit::perMinute(5)->by(Str::lower($email).'|'.$ip)->response(function () {
                    return back()->withInput()->withErrors([
                        'email' => 'Security Notice: Too many login attempts. Please wait 60 seconds before retrying.',
                    ]);
                }),
            ];
        });

        // Rate Limiter for Geocoding APIs (Reverse & Forward Search)
        RateLimiter::for('geocoding', function (Request $request) {
            $key = $request->user()?->id ?: $request->ip();

            return Limit::perMinute(30)->by($key)->response(function () {
                return response()->json([
                    'success' => false,
                    'message' => 'Rate limit exceeded: Too many coordinate or address lookup requests. Please wait a moment before trying again.',
                    'retry_after' => 60,
                ], 429);
            });
        });

        // Rate Limiter for Schools Management Operations
        RateLimiter::for('schools', function (Request $request) {
            $key = $request->user()?->id ?: $request->ip();

            return Limit::perMinute(60)->by($key);
        });

        // Rate Limiter for Feasibility Applications & Workflow Operations
        RateLimiter::for('applications', function (Request $request) {
            $key = $request->user()?->id ?: $request->ip();

            return Limit::perMinute(60)->by($key);
        });

        // Rate Limiter for Heavy Reports & Data Exports (PDF & CSV DoS Mitigation)
        RateLimiter::for('reports', function (Request $request) {
            $key = $request->user()?->id ?: $request->ip();

            return Limit::perMinute(10)->by($key)->response(function () {
                return back()->with('error', 'Report generation rate limit reached. Please wait a moment before generating another export.');
            });
        });

        // Rate Limiter for Document Uploads (Mitigating Storage Exhaustion / File Flooding)
        RateLimiter::for('uploads', function (Request $request) {
            $key = $request->user()?->id ?: $request->ip();

            return Limit::perMinute(15)->by($key)->response(function () {
                return back()->with('error', 'Upload frequency limit reached. Please wait a moment before uploading more files.');
            });
        });

        // Rate Limiter for Document Downloads
        RateLimiter::for('downloads', function (Request $request) {
            $key = $request->user()?->id ?: $request->ip();

            return Limit::perMinute(40)->by($key);
        });

        // Rate Limiter for Sensitive 2FA Profile Actions (Mitigating TOTP Confirmation Brute Force)
        RateLimiter::for('two-factor', function (Request $request) {
            $key = $request->user()?->id ?: $request->ip();

            return Limit::perMinute(6)->by($key)->response(function () {
                return back()->withErrors([
                    'code' => 'Too many two-factor verification attempts. Please wait 60 seconds before trying again.',
                ]);
            });
        });
    }
}
