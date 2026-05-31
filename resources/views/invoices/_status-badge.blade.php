@php
    $styles = match ($status) {
        'draft' => ['bg' => 'bg-slate-100 text-slate-600 ring-slate-500/20', 'dot' => 'bg-slate-400'],
        'sent' => ['bg' => 'bg-amber-50 text-amber-700 ring-amber-600/20', 'dot' => 'bg-amber-500'],
        'paid' => ['bg' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'dot' => 'bg-emerald-500'],
        'overdue' => ['bg' => 'bg-red-50 text-red-700 ring-red-600/20', 'dot' => 'bg-red-500'],
        default => ['bg' => 'bg-slate-100 text-slate-600 ring-slate-500/20', 'dot' => 'bg-slate-400'],
    };
    $label = \App\Models\Invoice::STATUS_LABELS[$status] ?? ucfirst($status);
@endphp

<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $styles['bg'] }}">
    <span class="h-1.5 w-1.5 rounded-full {{ $styles['dot'] }}"></span>
    {{ $label }}
</span>
