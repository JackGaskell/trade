@props(['label', 'value', 'hint' => null, 'href' => null, 'variant' => 'default'])

@php
    $variants = [
        'default' => 'border-slate-200/80 hover:border-slate-300',
        'warning' => 'border-amber-200/80 hover:border-amber-300 bg-amber-50/30',
        'danger' => 'border-red-200/80 hover:border-red-300 bg-red-50/30',
        'info' => 'border-blue-200/80 hover:border-blue-300 bg-blue-50/30',
    ];
    $valueColors = [
        'default' => 'text-slate-900',
        'warning' => 'text-amber-900',
        'danger' => 'text-red-700',
        'info' => 'text-blue-900',
    ];
    $classes = 'group block rounded-xl border bg-white p-5 shadow-card transition-all duration-200 hover:shadow-card-hover ' . ($variants[$variant] ?? $variants['default']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
        <p class="mt-2 text-2xl font-bold tracking-tight {{ $valueColors[$variant] ?? $valueColors['default'] }}">{{ $value }}</p>
        @if ($hint)
            <p class="mt-2 text-xs text-slate-400 group-hover:text-slate-500">{{ $hint }}</p>
        @endif
    </a>
@else
    <div {{ $attributes->merge(['class' => $classes]) }}>
        <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
        <p class="mt-2 text-2xl font-bold tracking-tight {{ $valueColors[$variant] ?? $valueColors['default'] }}">{{ $value }}</p>
        @if ($hint)
            <p class="mt-2 text-xs text-slate-400">{{ $hint }}</p>
        @endif
    </div>
@endif
