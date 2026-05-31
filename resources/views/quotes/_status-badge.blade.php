@php
    $badgeClass = match ($status) {
        'draft' => 'badge-neutral',
        'sent' => 'badge-info',
        'accepted' => 'badge-success',
        'rejected' => 'badge-danger',
        default => 'badge-neutral',
    };
    $label = \App\Models\Quote::STATUS_LABELS[$status] ?? ucfirst($status);
@endphp

<span class="badge {{ $badgeClass }}">
    <span class="badge-dot"></span>
    {{ $label }}
</span>
