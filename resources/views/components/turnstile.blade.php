@props(['theme' => 'light', 'size' => 'normal', 'action' => null])

{{-- Backward compatibility alias: forwards to Google reCAPTCHA v2 --}}
<x-recaptcha :theme="$theme" :size="$size" />
