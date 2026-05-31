@php
    $badgeClass = match ($status) {
        'enquiry' => 'badge-warning',
        'quoted' => 'badge-info',
        'accepted' => 'badge-indigo',
        'in_progress' => 'badge-trade',
        'completed' => 'badge-success',
        'cancelled' => 'badge-neutral',
        default => 'badge-neutral',
    };
    $label = \App\Models\Job::STATUS_LABELS[$status] ?? ucfirst(str_replace('_', ' ', $status));
@endphp

<span class="badge {{ $badgeClass }}">
    <span class="badge-dot"></span>
    {{ $label }}
</span>
