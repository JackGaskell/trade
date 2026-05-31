@props(['padding' => true])

<div {{ $attributes->merge(['class' => 'card' . ($padding ? '' : '')]) }}>
    {{ $slot }}
</div>
