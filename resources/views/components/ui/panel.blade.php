@props(['title', 'href' => null, 'linkText' => 'View all'])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-card']) }}>
    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
        <h3 class="text-sm font-semibold text-slate-900">{{ $title }}</h3>
        @if ($href)
            <a href="{{ $href }}" class="text-xs font-medium text-brand-600 hover:text-brand-700">{{ __($linkText) }}</a>
        @endif
    </div>
    <div>
        {{ $slot }}
    </div>
</div>
