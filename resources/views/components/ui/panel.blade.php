@props(['title', 'href' => null, 'linkText' => 'View all'])

<div {{ $attributes->merge(['class' => 'card']) }}>
    <div class="card-header">
        <h3 class="text-sm font-bold text-slate-900">{{ $title }}</h3>
        @if ($href)
            <a href="{{ $href }}" class="text-link text-xs">{{ __($linkText) }}</a>
        @endif
    </div>
    <div>
        {{ $slot }}
    </div>
</div>
