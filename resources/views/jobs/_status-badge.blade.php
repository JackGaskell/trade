@php
    $styles = match ($status) {
        'enquiry' => ['bg' => 'bg-amber-50 text-amber-700 ring-amber-600/20', 'dot' => 'bg-amber-500'],
        'quoted' => ['bg' => 'bg-blue-50 text-blue-700 ring-blue-600/20', 'dot' => 'bg-blue-500'],
        'accepted' => ['bg' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20', 'dot' => 'bg-indigo-500'],
        'in_progress' => ['bg' => 'bg-orange-50 text-orange-700 ring-orange-600/20', 'dot' => 'bg-orange-500'],
        'completed' => ['bg' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'dot' => 'bg-emerald-500'],
        'cancelled' => ['bg' => 'bg-slate-100 text-slate-600 ring-slate-500/20', 'dot' => 'bg-slate-400'],
        default => ['bg' => 'bg-slate-100 text-slate-600 ring-slate-500/20', 'dot' => 'bg-slate-400'],
    };
    $label = \App\Models\Job::STATUS_LABELS[$status] ?? ucfirst(str_replace('_', ' ', $status));
@endphp

<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $styles['bg'] }}">
    <span class="h-1.5 w-1.5 rounded-full {{ $styles['dot'] }}"></span>
    {{ $label }}
</span>
