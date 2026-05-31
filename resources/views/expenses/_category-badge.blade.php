@php
    $colors = [
        'materials_stock' => 'bg-amber-50 text-amber-800 ring-amber-600/20',
        'tools_equipment' => 'bg-slate-100 text-slate-700 ring-slate-600/20',
        'vehicle_travel' => 'bg-blue-50 text-blue-800 ring-blue-600/20',
        'subcontractors' => 'bg-sky-50 text-sky-800 ring-sky-600/20',
        'insurance' => 'bg-green-50 text-green-800 ring-green-600/20',
        'office_phone_software' => 'bg-cyan-50 text-cyan-800 ring-cyan-600/20',
        'professional_fees' => 'bg-rose-50 text-rose-800 ring-rose-600/20',
    ];
    $class = $colors[$category] ?? 'bg-slate-100 text-slate-700 ring-slate-600/20';
    $label = \App\Models\Expense::CATEGORY_LABELS[$category] ?? ucfirst(str_replace('_', ' ', $category));
@endphp

<span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium ring-1 ring-inset {{ $class }}">
    {{ $label }}
</span>
