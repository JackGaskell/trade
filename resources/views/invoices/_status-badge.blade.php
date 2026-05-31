@php
    $badgeClass = match ($status) {
        'draft' => 'badge-neutral',
        'sent' => 'badge-info',
        'paid' => 'badge-success',
        'overdue' => 'badge-danger',
        default => 'badge-neutral',
    };
    $label = \App\Models\Invoice::STATUS_LABELS[$status] ?? ucfirst($status);
@endphp

<span class="badge {{ $badgeClass }}">
    <span class="badge-dot"></span>
    {{ $label }}
</span>
