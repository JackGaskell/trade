@props(['padding' => true])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-card' . ($padding ? '' : '')]) }}>
    {{ $slot }}
</div>
