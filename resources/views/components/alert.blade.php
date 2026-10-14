@props([
    'type' => 'info', // success, error, warning, info
    'title' => null,
    'autoDismiss' => true,
    'timeout' => null,
    'dismissible' => true,
])

@php
$defaultTimeout = match($type) {
    'success' => 4500,
    'error' => 6000,
    'warning' => 6000,
    default => 5000,
};
$duration = $timeout ?? $defaultTimeout;

$types = [
    'success' => [
        'container' => 'bg-emerald-50/95 border-emerald-200 text-emerald-950 shadow-sm',
        'progress' => 'bg-emerald-500/60',
        'close' => 'text-emerald-700 hover:text-emerald-950 hover:bg-emerald-100',
        'icon' => '<svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
    ],
    'error' => [
        'container' => 'bg-rose-50/95 border-rose-200 text-rose-950 shadow-sm',
        'progress' => 'bg-rose-500/60',
        'close' => 'text-rose-700 hover:text-rose-950 hover:bg-rose-100',
        'icon' => '<svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
    ],
    'warning' => [
        'container' => 'bg-amber-50/95 border-amber-200 text-amber-950 shadow-sm',
        'progress' => 'bg-amber-500/60',
        'close' => 'text-amber-700 hover:text-amber-950 hover:bg-amber-100',
        'icon' => '<svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>',
    ],
    'info' => [
        'container' => 'bg-cyan-50/95 border-cyan-200 text-cyan-950 shadow-sm',
        'progress' => 'bg-cyan-500/60',
        'close' => 'text-cyan-700 hover:text-cyan-950 hover:bg-cyan-100',
        'icon' => '<svg class="w-5 h-5 text-cyan-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
    ],
];
$cfg = $types[$type] ?? $types['info'];
@endphp

<div x-data="{ 
        show: true,
        init() {
            @if ($autoDismiss)
                setTimeout(() => { this.show = false; }, {{ $duration }});
            @endif
        }
     }"
     x-show="show"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 -translate-y-2 scale-98"
     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 -translate-y-2 scale-98"
     {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-2xl border p-4 text-xs ' . $cfg['container']]) }}>
    
    <div class="flex items-start gap-3">
        {!! $cfg['icon'] !!}
        
        <div class="flex-1 leading-relaxed">
            @if ($title)
                <h4 class="font-bold text-sm mb-1 leading-snug tracking-tight">{{ $title }}</h4>
            @endif
            <div class="text-xs">
                {{ $slot }}
            </div>
        </div>

        @if ($dismissible)
            <button type="button" @click="show = false"
                    title="Dismiss notification"
                    class="p-1 -mr-1 -mt-1 rounded-lg transition {{ $cfg['close'] }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        @endif
    </div>

    @if ($autoDismiss)
        <!-- Auto-Hide Progress Indicator Bar -->
        <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-black/5">
            <div class="h-full {{ $cfg['progress'] }}"
                 style="animation: shrinkBar {{ $duration }}ms linear forwards;"></div>
        </div>
        <style>
            @keyframes shrinkBar {
                from { width: 100%; }
                to { width: 0%; }
            }
        </style>
    @endif
</div>
