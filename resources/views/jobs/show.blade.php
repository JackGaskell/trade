<x-app-layout>
    <x-ui.page-header :title="$job->title" :description="$job->client->name">
        <x-slot name="actions">
            <a href="{{ route('jobs.edit', $job) }}" class="btn-secondary">Edit</a>
        </x-slot>
    </x-ui.page-header>

    <x-ui.flash />

    <div class="space-y-6">
        <x-ui.card class="p-6">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Status</dt>
                    <dd class="mt-1">@include('jobs._status-badge', ['status' => $job->status])</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Scheduled</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $job->scheduled_date?->format('d M Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Client</dt>
                    <dd class="mt-1 text-sm"><a href="{{ route('clients.show', $job->client) }}" class="font-medium text-brand-600 hover:text-brand-700">{{ $job->client->name }}</a></dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Created</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $job->created_at->format('d M Y') }}</dd>
                </div>
                @if ($job->description)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Description</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-slate-900">{{ $job->description }}</dd>
                    </div>
                @endif
                @if ($job->address)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Address</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-slate-900">{{ $job->address }}</dd>
                    </div>
                @endif
                @if ($job->notes)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Notes</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-slate-900">{{ $job->notes }}</dd>
                    </div>
                @endif
            </dl>
        </x-ui.card>

        <x-ui.panel title="Quotes" :href="route('quotes.create', ['job_id' => $job->id])" linkText="Add quote">
            @if ($job->quotes->isEmpty())
                <p class="px-5 py-8 text-center text-sm text-slate-500">No quotes for this job yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead><tr><th>Quote</th><th>Amount</th><th>Status</th><th class="hidden sm:table-cell">Valid Until</th><th></th></tr></thead>
                        <tbody>
                            @foreach ($job->quotes as $quote)
                                <tr>
                                    <td><a href="{{ route('quotes.show', $quote) }}" class="font-semibold text-slate-900 hover:text-brand-600">{{ $quote->quote_number }}</a></td>
                                    <td class="font-semibold">{{ $quote->formattedAmount() }}</td>
                                    <td>@include('quotes._status-badge', ['status' => $quote->status])</td>
                                    <td class="hidden text-slate-600 sm:table-cell">{{ $quote->valid_until?->format('d M Y') ?? '—' }}</td>
                                    <td class="text-right"><a href="{{ route('quotes.show', $quote) }}" class="text-sm font-medium text-brand-600">View</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-ui.panel>

        <x-ui.panel title="Invoices" :href="route('invoices.create', ['job_id' => $job->id])" linkText="Add invoice">
            @if ($job->invoices->isEmpty())
                <p class="px-5 py-8 text-center text-sm text-slate-500">No invoices for this job yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead><tr><th>Invoice</th><th>Amount</th><th>Status</th><th class="hidden sm:table-cell">Due</th><th></th></tr></thead>
                        <tbody>
                            @foreach ($job->invoices as $invoice)
                                <tr class="{{ $invoice->status === 'overdue' ? 'bg-red-50/60' : ($invoice->status === 'sent' ? 'bg-amber-50/30' : '') }}">
                                    <td><a href="{{ route('invoices.show', $invoice) }}" class="font-semibold text-slate-900 hover:text-brand-600">{{ $invoice->invoice_number }}</a></td>
                                    <td class="font-semibold {{ $invoice->isUnpaid() ? 'text-amber-900' : '' }}">{{ $invoice->formattedAmount() }}</td>
                                    <td>@include('invoices._status-badge', ['status' => $invoice->status])</td>
                                    <td class="hidden sm:table-cell {{ $invoice->status === 'overdue' ? 'text-red-700 font-medium' : 'text-slate-600' }}">{{ $invoice->due_date?->format('d M Y') ?? '—' }}</td>
                                    <td class="text-right"><a href="{{ route('invoices.show', $invoice) }}" class="text-sm font-medium text-brand-600">View</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-ui.panel>

        <x-ui.card class="p-6">
            <h3 class="text-sm font-semibold text-red-600">Delete Job</h3>
            <p class="mt-1 text-sm text-slate-500">Once deleted, this job and its details cannot be recovered.</p>
            <form method="POST" action="{{ route('jobs.destroy', $job) }}" class="mt-4" onsubmit="return confirm('Are you sure you want to delete this job?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Delete Job</button>
            </form>
        </x-ui.card>

        <a href="{{ route('jobs.index') }}" class="inline-flex text-sm font-medium text-slate-500 hover:text-slate-900">&larr; Back to jobs</a>
    </div>
</x-app-layout>
