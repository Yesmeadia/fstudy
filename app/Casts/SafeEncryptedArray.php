<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

/**
 * Safe encrypted array attribute cast.
 *
 * @implements CastsAttributes<array<array-key, mixed>|null, array<array-key, mixed>|string|null>
 */
class SafeEncryptedArray implements CastsAttributes
{
    /**
     * Cast the given value (from database storage to PHP array).
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        // If already an array
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            // 1. First try to decrypt directly (SQLite or raw string)
            try {
                $decrypted = Crypt::decryptString($value);
                $parsed = json_decode($decrypted, true);

                return is_array($parsed) ? $parsed : $decrypted;
            } catch (\Throwable $e) {
                // Not a direct ciphertext string
            }

            // 2. Try json_decode in case it was stored wrapped in JSON quotes (MySQL JSON column)
            $decoded = json_decode($value, true);
            if (is_string($decoded)) {
                try {
                    $decrypted = Crypt::decryptString($decoded);
                    $parsed = json_decode($decrypted, true);

                    return is_array($parsed) ? $parsed : $decrypted;
                } catch (\Throwable $e) {
                    return $decoded;
                }
            } elseif (is_array($decoded)) {
                return $decoded;
            }
        }

        return $value;
    }

    /**
     * Prepare the given value for storage (from PHP array to encrypted JSON string).
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        $json = is_string($value) ? $value : json_encode($value);
        $encrypted = Crypt::encryptString($json);

        // MySQL JSON column expects valid JSON syntax. json_encode ensures the ciphertext
        // is wrapped as a valid JSON string scalar: "eyJpdi..."
        return json_encode($encrypted);
    }
}
