@props([
    'variant' => 'light', // 'light' uses white logo (for dark bg), 'dark' uses dark logo (for light bg)
    'height' => 'h-10',
    'class' => '',
])

@php
    $logoSrc = $variant === 'dark' ? asset('logob.png') : asset('logow.png');
@endphp

<img src="{{ $logoSrc }}"
     alt="YES INDIA FOUNDATION"
     {{ $attributes->merge(['class' => "inline-block object-contain {$height} {$class}"]) }}>
