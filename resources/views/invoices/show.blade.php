<x-app-layout>
    <x-ui.page-header :title="$invoice->invoice_number" :description="$invoice->job->client->name . ' · ' . $invoice->job->title">
        <x-slot name="actions">
            <a href="{{ route('invoices.edit', $invoice) }}" class="btn-secondary">Edit</a>
        </x-slot>
    </x-ui.page-header>

    <x-ui.flash />

    <div class="space-y-6">
        @if ($invoice->isUnpaid())
            <div class="rounded-xl border {{ $invoice->status === 'overdue' ? 'border-red-200 bg-red-50' : 'border-amber-200 bg-amber-50' }} p-4">
                <p class="text-sm font-semibold {{ $invoice->status === 'overdue' ? 'text-red-800' : 'text-amber-800' }}">
                    {{ $invoice->status === 'overdue' ? 'This invoice is overdue.' : 'This invoice is awaiting payment.' }}
                </p>
                @if ($invoice->due_date)
                    <p class="mt-1 text-sm {{ $invoice->status === 'overdue' ? 'text-red-700' : 'text-amber-700' }}">Due: {{ $invoice->due_date->format('d M Y') }}</p>
                @endif
            </div>
        @endif

        <x-ui.card class="p-6">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Amount</dt>
                    <dd class="mt-1 text-2xl font-bold text-slate-900">{{ $invoice->formattedAmount() }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Status</dt>
                    <dd class="mt-1">@include('invoices._status-badge', ['status' => $invoice->status])</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Due Date</dt>
                    <dd class="mt-1 text-sm {{ $invoice->status === 'overdue' ? 'font-medium text-red-700' : 'text-slate-900' }}">{{ $invoice->due_date?->format('d M Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Created</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $invoice->created_at->format('d M Y') }}</dd>
                </div>
                @if ($invoice->notes)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Notes</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-slate-900">{{ $invoice->notes }}</dd>
                    </div>
                @endif
            </dl>
        </x-ui.card>

        <x-ui.card class="p-6">
            <h3 class="text-sm font-semibold text-red-600">Delete Invoice</h3>
            <p class="mt-1 text-sm text-slate-500">Once deleted, this invoice cannot be recovered.</p>
            <form method="POST" action="{{ route('invoices.destroy', $invoice) }}" class="mt-4" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Delete Invoice</button>
            </form>
        </x-ui.card>

        <a href="{{ route('invoices.index') }}" class="inline-flex text-sm font-medium text-slate-500 hover:text-slate-900">&larr; Back to invoices</a>
    </div>
</x-app-layout>
