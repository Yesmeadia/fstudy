<?php

namespace App\Services\TwoFactor;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QRMarkupSVG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Str;

class TwoFactorAuthenticationService
{
    protected const BASE32_CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * Generate a random 16-character or 32-character Base32 secret key.
     * 16 characters (80-bit) is the standard for Google Authenticator / Microsoft Authenticator.
     */
    public function generateSecretKey(int $length = 16): string
    {
        $secret = '';
        $charsLen = strlen(self::BASE32_CHARS);
        for ($i = 0; $i < $length; $i++) {
            $secret .= self::BASE32_CHARS[random_int(0, $charsLen - 1)];
        }

        return $secret;
    }

    /**
     * Generate standard otpauth URI compatible with Google/Microsoft Authenticator.
     */
    public function qrCodeUri(string $issuer, string $accountName, string $secret): string
    {
        $cleanIssuer = trim(preg_replace('/[^\w\s-]/', '', $issuer));
        $encodedIssuer = rawurlencode($cleanIssuer);
        $encodedAccount = rawurlencode(trim($accountName));
        $cleanSecret = strtoupper(preg_replace('/[^A-Z2-7]/', '', $secret));

        return "otpauth://totp/{$encodedIssuer}:{$encodedAccount}?secret={$cleanSecret}&issuer={$encodedIssuer}&algorithm=SHA1&digits=6&period=30";
    }

    /**
     * Generate an array of 8 secure single-use recovery backup codes.
     */
    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(Str::random(4).'-'.Str::random(4));
        }

        return $codes;
    }

    /**
     * Verify a 6-digit TOTP code against a Base32 secret key with time window drift (RFC 6238).
     */
    public function verify(string $secret, string $code, int $window = 1): bool
    {
        $code = trim($code);
        if (strlen($code) !== 6 || ! ctype_digit($code)) {
            return false;
        }

        $cleanSecret = strtoupper(preg_replace('/[^A-Z2-7]/', '', $secret));
        if (empty($cleanSecret)) {
            return false;
        }

        $currentTimeSlice = (int) floor(time() / 30);

        for ($slice = -$window; $slice <= $window; $slice++) {
            $calculatedCode = $this->calculateCode($cleanSecret, $currentTimeSlice + $slice);
            if (hash_equals($calculatedCode, $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate 6-digit TOTP code for a specific time slice.
     */
    public function calculateCode(string $secret, int $timeSlice): string
    {
        $secretKey = $this->base32Decode($secret);
        $time = pack('N*', 0).pack('N*', $timeSlice);
        $hmac = hash_hmac('sha1', $time, $secretKey, true);
        $offset = ord(substr($hmac, -1)) & 0x0F;
        $hashPart = substr($hmac, $offset, 4);
        $value = unpack('N', $hashPart);
        $value = $value[1] & 0x7FFFFFFF;
        $modulo = pow(10, 6);

        return str_pad((string) ($value % $modulo), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Decode a Base32 string to binary (RFC 4648).
     */
    protected function base32Decode(string $b32): string
    {
        $b32 = strtoupper(preg_replace('/[^A-Z2-7]/', '', $b32));
        $buffer = 0;
        $bitsLeft = 0;
        $binary = '';

        for ($i = 0, $len = strlen($b32); $i < $len; $i++) {
            $val = strpos(self::BASE32_CHARS, $b32[$i]);
            if ($val === false) {
                continue;
            }
            $buffer = ($buffer << 5) | $val;
            $bitsLeft += 5;
            if ($bitsLeft >= 8) {
                $bitsLeft -= 8;
                $binary .= chr(($buffer >> $bitsLeft) & 0xFF);
            }
        }

        return $binary;
    }

    /**
     * Render ISO/IEC 18004 compliant Base64 Data URI for <img> tags.
     * Guaranteed 100% scannable by Google Authenticator, Microsoft Authenticator, and Authy.
     */
    public function qrCodeDataUri(string $text): string
    {
        $options = new QROptions;
        $options->outputInterface = QRMarkupSVG::class;
        $options->outputBase64 = true;
        $options->eccLevel = EccLevel::M;
        $options->scale = 6;
        $options->svgAddXmlHeader = true;

        $qrCode = new QRCode($options);

        return $qrCode->render($text);
    }

    /**
     * Render raw SVG string.
     */
    public function qrCodeSvg(string $text, int $size = 200): string
    {
        $options = new QROptions;
        $options->outputInterface = QRMarkupSVG::class;
        $options->outputBase64 = false;
        $options->eccLevel = EccLevel::M;
        $options->scale = 5;
        $options->svgAddXmlHeader = false;

        $qrCode = new QRCode($options);
        $svg = $qrCode->render($text);

        // Inject width and height attributes if needed
        if (! str_contains($svg, 'width=')) {
            $svg = preg_replace('/<svg /', "<svg width=\"{$size}\" height=\"{$size}\" ", $svg, 1);
        }

        return $svg;
    }
}
