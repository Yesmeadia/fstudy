<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Translation\PotentiallyTranslatedString;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.recaptcha.secret_key');

        // If recaptcha secret is not configured or in testing environment, bypass validation
        if (empty($secret) || app()->environment('testing')) {
            return;
        }

        if (empty($value)) {
            $fail("Please check the \"I'm not a robot\" box to verify you are human.");

            return;
        }

        try {
            $http = Http::asForm()->timeout(6);

            // In local development, prevent local CA certificate issues
            if (app()->environment('local')) {
                $http = $http->withoutVerifying();
            }

            $response = $http->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            if (! $response->successful() || ! $response->json('success')) {
                Log::warning('Google reCAPTCHA v2 verification failed', [
                    'errors' => $response->json('error-codes', []),
                    'ip' => request()->ip(),
                ]);

                $fail('reCAPTCHA security verification failed. Please try again.');
            }
        } catch (\Throwable $e) {
            Log::error('Google reCAPTCHA verification exception: '.$e->getMessage());

            // If in local and network fails, do not lock developer out
            if (app()->environment('local')) {
                Log::info('Google reCAPTCHA bypassed in local development due to network exception.');

                return;
            }

            $fail('Security verification service is temporarily unavailable. Please try again.');
        }
    }
}
