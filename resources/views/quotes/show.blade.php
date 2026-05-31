<x-app-layout>
    <x-ui.page-header :title="$quote->quote_number" :description="$quote->job->client->name . ' · ' . $quote->job->title">
        <x-slot name="actions">
            <a href="{{ route('quotes.edit', $quote) }}" class="btn-secondary">Edit</a>
        </x-slot>
    </x-ui.page-header>

    <x-ui.flash />

    <div class="space-y-6">
        <x-ui.card class="p-6">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Amount</dt>
                    <dd class="mt-1 text-2xl font-bold text-slate-900">{{ $quote->formattedAmount() }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Status</dt>
                    <dd class="mt-1">@include('quotes._status-badge', ['status' => $quote->status])</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Valid Until</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $quote->valid_until?->format('d M Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Created</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $quote->created_at->format('d M Y') }}</dd>
                </div>
                @if ($quote->description)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Description</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-slate-900">{{ $quote->description }}</dd>
                    </div>
                @endif
            </dl>
        </x-ui.card>

        <x-ui.card class="p-6">
            <h3 class="text-sm font-semibold text-red-600">Delete Quote</h3>
            <p class="mt-1 text-sm text-slate-500">Once deleted, this quote cannot be recovered.</p>
            <form method="POST" action="{{ route('quotes.destroy', $quote) }}" class="mt-4" onsubmit="return confirm('Are you sure you want to delete this quote?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Delete Quote</button>
            </form>
        </x-ui.card>

        <a href="{{ route('quotes.index') }}" class="inline-flex text-sm font-medium text-slate-500 hover:text-slate-900">&larr; Back to quotes</a>
    </div>
</x-app-layout>
