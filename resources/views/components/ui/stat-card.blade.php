@props(['label', 'value', 'hint' => null, 'href' => null, 'variant' => 'default'])

@php
    $valueColors = [
        'default' => 'text-slate-900',
        'warning' => 'text-amber-600',
        'danger' => 'text-red-600',
        'success' => 'text-emerald-600',
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'stat-card group']) }}>
        <p class="metric-label">{{ $label }}</p>
        <p class="money mt-2 text-2xl font-bold {{ $valueColors[$variant] ?? $valueColors['default'] }}">{{ $value }}</p>
        @if ($hint)
            <p class="mt-2 text-xs leading-relaxed text-slate-400 group-hover:text-slate-500">{{ $hint }}</p>
        @endif
    </a>
@else
    <div {{ $attributes->merge(['class' => 'stat-card']) }}>
        <p class="metric-label">{{ $label }}</p>
        <p class="money mt-2 text-2xl font-bold {{ $valueColors[$variant] ?? $valueColors['default'] }}">{{ $value }}</p>
        @if ($hint)
            <p class="mt-2 text-xs leading-relaxed text-slate-400">{{ $hint }}</p>
        @endif
    </div>
@endif
