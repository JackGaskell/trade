@props([
    'href' => null,
    'showText' => true,
    'showTagline' => false,
    'size' => 'sm',
    'subtitle' => null,
    'taglineStyle' => 'label',
])

@php
    $href ??= auth()->check() ? route('dashboard') : url('/');

    $name = config('brand.name');
    $logoTagline = $subtitle ?? ($showTagline ? config('brand.logo_tagline') : null);

    $hammerGradientId = 'hammer-'.substr(uniqid(), -8);

    $sizes = [
        'sm' => [
            'mark' => 'logo-mark-sm',
            'icon' => 'logo-mark-icon-sm',
            'wordmark' => 'logo-wordmark-sm',
            'tagline' => 'logo-tagline-sm',
        ],
        'md' => [
            'mark' => 'logo-mark-md',
            'icon' => 'logo-mark-icon-md',
            'wordmark' => 'logo-wordmark-md',
            'tagline' => 'logo-tagline-md',
        ],
        'lg' => [
            'mark' => 'logo-mark-lg',
            'icon' => 'logo-mark-icon-lg',
            'wordmark' => 'logo-wordmark-lg',
            'tagline' => 'logo-tagline-lg',
        ],
    ];

    $s = $sizes[$size] ?? $sizes['sm'];

    $lockupClasses = collect([
        'logo-lockup',
        $showText ? '' : 'logo-lockup-icon-only',
        $logoTagline && $showText ? 'logo-lockup-with-tagline' : '',
    ])->filter()->implode(' ');
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($lockupClasses) }} aria-label="{{ $name }} — home">
@else
    <div {{ $attributes->class($lockupClasses) }}>
@endif
        <div class="logo-mark {{ $s['mark'] }}" aria-hidden="true">
            <x-ui.logo-symbol :gradient-id="$hammerGradientId" class="{{ $s['icon'] }}" />
        </div>

        @if ($showText)
            <div class="logo-lockup-text">
                <p class="logo-wordmark {{ $s['wordmark'] }}">
                    <span class="logo-wordmark-the">The</span>
                    <span class="logo-wordmark-accent">Trade</span>
                    <span class="logo-wordmark-tool">Tool</span>
                </p>
                @if ($logoTagline)
                    <p @class([
                        'logo-tagline',
                        'logo-tagline-caption' => $taglineStyle === 'caption',
                        $s['tagline'] => $taglineStyle !== 'caption',
                    ])>{{ $logoTagline }}</p>
                @endif
            </div>
        @else
            <span class="sr-only">{{ $name }}</span>
        @endif
@if ($href)
    </a>
@else
    </div>
@endif
