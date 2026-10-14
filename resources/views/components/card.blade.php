@props([
    'title' => null,
    'subtitle' => null,
    'actions' => null,
    'footer' => null,
    'noPadding' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-slate-200/80 shadow-xs overflow-hidden']) }}>
    @if ($title || $subtitle || $actions)
        <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div>
                @if ($title)
                    <h3 class="text-base font-semibold text-slate-900 leading-snug">{{ $title }}</h3>
                @endif
                @if ($subtitle)
                    <p class="text-xs text-slate-500 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if ($actions)
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="{{ $noPadding ? '' : 'p-5' }}">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
            {{ $footer }}
        </div>
    @endif
</div>
