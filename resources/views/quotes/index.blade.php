<x-app-layout>
    <x-ui.page-header title="Quotes" description="Send quotes and track client responses.">
        <x-slot name="actions">
            <a href="{{ route('quotes.create') }}" class="btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Quote
            </a>
        </x-slot>
    </x-ui.page-header>

    <x-ui.flash />

    <x-ui.card :padding="false">
        @if ($quotes->isEmpty())
            <x-ui.empty-state
                message="No quotes yet. Create a quote for one of your jobs."
                actionLabel="New Quote"
                :actionHref="route('quotes.create')"
            />
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Quote</th>
                            <th class="hidden sm:table-cell">Client</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="hidden md:table-cell">Valid Until</th>
                            <th class="text-right"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quotes as $quote)
                            <tr>
                                <td>
                                    <a href="{{ route('quotes.show', $quote) }}" class="font-semibold text-slate-900 hover:text-brand-600">
                                        {{ $quote->quote_number }}
                                    </a>
                                    <p class="mt-0.5 text-xs text-slate-500 sm:hidden">{{ $quote->job->client->name }}</p>
                                </td>
                                <td class="hidden sm:table-cell">
                                    <a href="{{ route('clients.show', $quote->job->client) }}" class="text-slate-600 hover:text-brand-600">
                                        {{ $quote->job->client->name }}
                                    </a>
                                </td>
                                <td class="font-semibold text-slate-900">{{ $quote->formattedAmount() }}</td>
                                <td>@include('quotes._status-badge', ['status' => $quote->status])</td>
                                <td class="hidden text-slate-600 md:table-cell">
                                    {{ $quote->valid_until?->format('d M Y') ?? '—' }}
                                </td>
                                <td class="text-right">
                                    <x-ui.table-actions
                                        :viewRoute="route('quotes.show', $quote)"
                                        :editRoute="route('quotes.edit', $quote)"
                                        :deleteRoute="route('quotes.destroy', $quote)"
                                        deleteConfirm="Are you sure you want to delete this quote?"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($quotes->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $quotes->links() }}
                </div>
            @endif
        @endif
    </x-ui.card>
</x-app-layout>
