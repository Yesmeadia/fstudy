<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and apply strict enterprise security headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 1. Prevent Clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 2. Prevent MIME-sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 3. Cross-Site Scripting (XSS) Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // 4. Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 5. Restrict Browser Features & Permissions Policy (Allow camera and geolocation for evidence capture and coordinates)
        $response->headers->set('Permissions-Policy', 'camera=(self), microphone=(), geolocation=(self), payment=()');

        // 6. Content Security Policy (allowing local scripts, vite, bunny fonts, inline styles, OpenStreetMap, Google reCAPTCHA)
        $csp = "default-src 'self'; ".
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://fonts.bunny.net https://www.google.com/recaptcha/ https://www.gstatic.com/recaptcha/ https://challenges.cloudflare.com https://unpkg.com; ".
               "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://unpkg.com; ".
               "font-src 'self' https://fonts.bunny.net data:; ".
               "img-src 'self' data: https:; ".
               "frame-src 'self' https://www.google.com/recaptcha/ https://recaptcha.google.com/recaptcha/ https://challenges.cloudflare.com; ".
               "connect-src 'self' https://www.google.com/recaptcha/ https://challenges.cloudflare.com https://nominatim.openstreetmap.org; ".
               "frame-ancestors 'self';";
        $response->headers->set('Content-Security-Policy', $csp);

        // 7. Strip server banner / technology fingerprinting
        $response->headers->remove('X-Powered-By');
        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
        }

        // 8. HSTS for secure transports
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
