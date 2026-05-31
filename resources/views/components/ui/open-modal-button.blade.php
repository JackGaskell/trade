@props(['modal', 'variant' => 'primary'])

@php
    $class = $variant === 'secondary' ? 'btn-secondary' : 'btn-primary';
@endphp

<button
    type="button"
    {{ $attributes->merge(['class' => $class]) }}
    @click="$dispatch('open-modal', '{{ $modal }}')"
>
    {{ $slot }}
</button>
