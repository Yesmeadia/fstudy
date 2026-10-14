<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Notifications\TwoFactorCodeNotification;
use App\Services\TwoFactor\TwoFactorAuthenticationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // If Two-Factor Authentication is enabled on this account
        if ($user && $user->two_factor_enabled) {
            $code = $user->generateTwoFactorCode();
            $remember = $request->boolean('remember');

            Auth::logout();

            $request->session()->put('login.id', $user->id);
            $request->session()->put('login.remember', $remember);

            try {
                $user->notify(new TwoFactorCodeNotification($code));
            } catch (\Throwable $e) {
                // Non-blocking if mail service has delay
            }

            return redirect()->route('two-factor.challenge');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Display Two-Factor Authentication challenge screen.
     */
    public function twoFactorChallenge(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('login.id')) {
            return redirect()->route('login');
        }

        $user = User::find($request->session()->get('login.id'));
        if (! $user) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge', [
            'user' => $user,
            'demoCode' => app()->environment('local', 'testing') ? $user->two_factor_code : null,
        ]);
    }

    /**
     * Verify submitted 2FA code and log user in.
     */
    public function verifyTwoFactor(Request $request, TwoFactorAuthenticationService $twoFactorService): RedirectResponse
    {
        $userId = $request->session()->get('login.id');
        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if (! $user) {
            return redirect()->route('login');
        }

        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $code = trim($request->code);
        $authenticated = false;
        $usedBackupCode = false;

        // 1. Verify 6-digit TOTP code from Authenticator App
        if ($user->two_factor_secret && $twoFactorService->verify($user->two_factor_secret, $code)) {
            $authenticated = true;
        }

        // 2. Verify emergency recovery backup code
        if (! $authenticated && $user->consumeRecoveryCode($code)) {
            $authenticated = true;
            $usedBackupCode = true;
        }

        // 3. Fallback to emailed 6-digit OTP code (or local test code 123456)
        if (! $authenticated) {
            if ($user->two_factor_code === $code && $user->two_factor_expires_at && $user->two_factor_expires_at->isFuture()) {
                $authenticated = true;
            } elseif ($code === '123456' && app()->environment('local', 'testing')) {
                $authenticated = true;
            }
        }

        if ($authenticated) {
            $remember = (bool) $request->session()->get('login.remember', false);
            $request->session()->forget(['login.id', 'login.remember']);
            $user->resetTwoFactorCode();

            Auth::login($user, $remember);
            $request->session()->regenerate();

            if ($usedBackupCode) {
                session()->flash('warning', 'Security Alert: You authenticated using an emergency recovery backup code. Please generate a new set of backup codes in your profile.');
            }

            return redirect()->intended(route('dashboard', absolute: false));
        }

        return back()->withErrors(['code' => 'The provided code or emergency recovery code is invalid or has expired.']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
