@props([
    'label',
    'value',
    'subtext' => null,
    'icon' => null,
    'color' => 'blue', // blue, emerald, amber, purple, rose, slate
    'href' => null,
])

@php
$colorStyles = [
    'blue' => 'bg-blue-50 text-blue-600 border-blue-100',
    'emerald' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
    'amber' => 'bg-amber-50 text-amber-600 border-amber-100',
    'purple' => 'bg-purple-50 text-purple-600 border-purple-100',
    'rose' => 'bg-rose-50 text-rose-600 border-rose-100',
    'slate' => 'bg-slate-100 text-slate-700 border-slate-200',
];
$style = $colorStyles[$color] ?? $colorStyles['blue'];
@endphp

<div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-xs hover:border-slate-300 transition relative overflow-hidden group">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ $label }}</p>
            <p class="text-2xl font-bold text-slate-900 mt-1.5 tracking-tight">{{ $value }}</p>
            @if ($subtext)
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">{{ $subtext }}</p>
            @endif
        </div>
        <div class="w-10 h-10 rounded-lg flex items-center justify-center border {{ $style }}">
            {{ $slot }}
        </div>
    </div>
    @if ($href)
        <a href="{{ $href }}" class="absolute inset-0 focus:outline-none" aria-label="{{ $label }}"></a>
    @endif
</div>
