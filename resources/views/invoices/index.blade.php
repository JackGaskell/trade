<x-app-layout>
    <x-ui.page-header title="Invoices" description="Get paid faster — track what's outstanding and overdue.">
        <x-slot name="actions">
            <x-ui.open-modal-button modal="create-invoice">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Invoice
            </x-ui.open-modal-button>
        </x-slot>
    </x-ui.page-header>

    <x-ui.flash />

    @if ($totalUnpaid > 0)
        <div class="mb-6 flex items-center justify-between rounded-xl border border-amber-200/80 bg-amber-50/50 px-5 py-4">
            <div>
                <p class="text-sm font-medium text-amber-800">Total Unpaid</p>
                <p class="mt-0.5 text-2xl font-bold text-amber-900">{{ \App\Models\Invoice::formatMoney($totalUnpaid) }}</p>
            </div>
            <p class="hidden text-sm text-amber-700 sm:block">Overdue invoices are highlighted below</p>
        </div>
    @endif

    <x-ui.card :padding="false">
        @if ($invoices->isEmpty())
            <x-ui.empty-state
                message="No invoices yet. Create an invoice to get paid."
                actionLabel="New Invoice"
                actionModal="create-invoice"
            />
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th class="hidden sm:table-cell">Client</th>
                            <th>Amount</th>
                            <th class="hidden md:table-cell">Due Date</th>
                            <th>Status</th>
                            <th class="text-right"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            @php
                                $rowHighlight = match ($invoice->status) {
                                    'overdue' => 'bg-red-50/60 hover:bg-red-50',
                                    'sent' => 'bg-amber-50/30 hover:bg-amber-50/50',
                                    default => '',
                                };
                            @endphp
                            <tr class="{{ $rowHighlight }}">
                                <td>
                                    <a href="{{ route('invoices.show', $invoice) }}" class="row-link">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                    <p class="mt-0.5 text-xs text-slate-500 sm:hidden">{{ $invoice->job->client->name }}</p>
                                </td>
                                <td class="hidden sm:table-cell">
                                    <a href="{{ route('clients.show', $invoice->job->client) }}" class="text-slate-600 hover:text-cyan-600">
                                        {{ $invoice->job->client->name }}
                                    </a>
                                </td>
                                <td class="font-semibold {{ $invoice->isUnpaid() ? 'text-amber-900' : 'text-slate-900' }}">
                                    {{ $invoice->formattedAmount() }}
                                </td>
                                <td class="hidden md:table-cell {{ $invoice->status === 'overdue' ? 'font-medium text-red-700' : 'text-slate-600' }}">
                                    {{ $invoice->due_date?->format('d M Y') ?? '—' }}
                                </td>
                                <td>@include('invoices._status-badge', ['status' => $invoice->status])</td>
                                <td class="text-right">
                                    <x-ui.table-actions
                                        :viewRoute="route('invoices.show', $invoice)"
                                        :editRoute="route('invoices.edit', $invoice)"
                                        :deleteRoute="route('invoices.destroy', $invoice)"
                                        deleteConfirm="Are you sure you want to delete this invoice?"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($invoices->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $invoices->links() }}
                </div>
            @endif
        @endif
    </x-ui.card>

    @include('invoices._create-modal')
</x-app-layout>
