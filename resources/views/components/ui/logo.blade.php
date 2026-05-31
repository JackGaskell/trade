@props([
    'href' => null,
    'showText' => true,
    'size' => 'sm',
    'subtitle' => null,
    'taglineStyle' => 'label',
])

@php
    $href ??= auth()->check() ? route('dashboard') : url('/');

    $name = config('brand.name');
    $tagline = $subtitle ?? null;

    $sizes = [
        'sm' => [
            'accent' => 'logo-accent-sm',
            'wordmark' => 'logo-wordmark-sm',
            'tagline' => 'logo-tagline-sm',
        ],
        'md' => [
            'accent' => 'logo-accent-md',
            'wordmark' => 'logo-wordmark-md',
            'tagline' => 'logo-tagline-md',
        ],
        'lg' => [
            'accent' => 'logo-accent-lg',
            'wordmark' => 'logo-wordmark-lg',
            'tagline' => 'logo-tagline-lg',
        ],
    ];

    $s = $sizes[$size] ?? $sizes['sm'];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class('logo-lockup') }} aria-label="{{ $name }} — home">
@else
    <div {{ $attributes->class('logo-lockup') }}>
@endif
        @if ($showText)
            <span class="logo-accent {{ $s['accent'] }}" aria-hidden="true"></span>
            <p class="logo-wordmark {{ $s['wordmark'] }}">
                <span class="logo-wordmark-the">The</span>
                <span class="logo-wordmark-accent">Trade</span>
                <span class="logo-wordmark-tool">Tool</span>
            </p>
            @if ($tagline)
                <p @class([
                    'col-start-2 row-start-2',
                    'logo-tagline-caption' => $taglineStyle === 'caption',
                    $s['tagline'] => $taglineStyle !== 'caption',
                ])>{{ $tagline }}</p>
            @endif
        @else
            <span class="logo-accent logo-accent-icon col-span-2" aria-hidden="true"></span>
            <span class="sr-only col-span-2">{{ $name }}</span>
        @endif
@if ($href)
    </a>
@else
    </div>
@endif
