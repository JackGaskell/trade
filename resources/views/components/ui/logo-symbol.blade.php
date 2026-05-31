@props([
    'gradientId' => 'logo-hammer-gradient',
])

<svg
    {{ $attributes->merge(['class' => 'shrink-0']) }}
    viewBox="0 0 32 32"
    fill="none"
    aria-hidden="true"
>
    <defs>
        <linearGradient id="{{ $gradientId }}" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#22d3ee" />
            <stop offset="45%" stop-color="#0ea5e9" />
            <stop offset="100%" stop-color="#2563eb" />
        </linearGradient>
    </defs>
    <g
        stroke="url(#{{ $gradientId }})"
        stroke-linecap="round"
        stroke-linejoin="round"
        transform="translate(16 16) rotate(38) translate(-10 -12)"
    >
        <rect x="0.5" y="1.5" width="16" height="8.5" rx="2" stroke-width="2.85" />
        <path d="M0.5 3.75 0 1.25M0.5 8.25 0 10.75" stroke-width="2.35" />
        <path d="M13.5 10.25 13.5 26.5" stroke-width="3.15" />
    </g>
</svg>
