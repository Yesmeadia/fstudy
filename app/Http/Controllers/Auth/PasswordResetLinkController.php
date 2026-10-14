<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\Recaptcha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'g-recaptcha-response' => [new Recaptcha],
        ]);

        // Attempt to send reset link without leaking whether the user exists in database
        Password::sendResetLink(
            $request->only('email')
        );

        // OWASP Standard: Always return a generic confirmation message to prevent account enumeration
        return back()->with('status', 'If an account matches that email address, we have sent a password reset link.');
    }
}
