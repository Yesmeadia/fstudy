@props([
    'variant' => 'slate', // slate, blue, emerald, amber, rose, indigo, purple, orange, teal
    'size' => 'md',       // sm, md, lg
])

@php
$variants = [
    'slate' => 'bg-slate-50 text-slate-700 border-slate-200 ring-slate-500/10',
    'blue' => 'bg-blue-50 text-blue-700 border-blue-200 ring-blue-500/10',
    'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200 ring-emerald-500/10',
    'amber' => 'bg-amber-50 text-amber-800 border-amber-200 ring-amber-500/10',
    'rose' => 'bg-rose-50 text-rose-700 border-rose-200 ring-rose-500/10',
    'indigo' => 'bg-indigo-50 text-indigo-700 border-indigo-200 ring-indigo-500/10',
    'purple' => 'bg-purple-50 text-purple-700 border-purple-200 ring-purple-500/10',
    'orange' => 'bg-orange-50 text-orange-700 border-orange-200 ring-orange-500/10',
    'teal' => 'bg-teal-50 text-teal-700 border-teal-200 ring-teal-500/10',
];

$sizes = [
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-1 text-xs',
    'lg' => 'px-3 py-1.5 text-sm font-medium',
];

$classes = "inline-flex items-center gap-1.5 rounded-full font-medium border ring-1 ring-inset " .
    ($variants[$variant] ?? $variants['slate']) . " " .
    ($sizes[$size] ?? $sizes['md']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
