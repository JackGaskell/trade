<x-app-layout>
    <x-ui.page-header
        title="Dashboard"
        description="What needs your attention today?"
    />

    {{-- Stat cards --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <x-ui.stat-card
            label="Outstanding Invoices"
            :value="\App\Models\Invoice::formatMoney($outstandingTotal)"
            hint="Unpaid — sent & awaiting payment"
            :href="route('invoices.index')"
            :variant="$outstandingTotal > 0 ? 'warning' : 'default'"
        />
        <x-ui.stat-card
            label="Overdue Invoices"
            :value="\App\Models\Invoice::formatMoney($overdueTotal)"
            hint="Needs chasing up"
            :href="route('invoices.index')"
            :variant="$overdueTotal > 0 ? 'danger' : 'default'"
        />
        <x-ui.stat-card
            label="Quotes Awaiting Response"
            :value="$quotesAwaitingResponse"
            hint="Sent to clients"
            :href="route('quotes.index')"
            variant="info"
        />
        <x-ui.stat-card
            label="Active Jobs"
            :value="$activeJobs"
            hint="In progress or pending"
            :href="route('jobs.index')"
        />
        <x-ui.stat-card
            label="Expenses This Month"
            :value="\App\Models\Invoice::formatMoney($expensesThisMonth)"
            :hint="now()->format('F Y')"
            :href="route('expenses.index')"
        />
        <x-ui.stat-card
            label="Profit Estimate"
            :value="\App\Models\Invoice::formatMoney($profitEstimate)"
            hint="Paid income minus expenses this month"
            :href="route('expenses.index')"
            :variant="$profitEstimate >= 0 ? 'success' : 'danger'"
        />
    </div>

    {{-- Panels --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        {{-- Upcoming Jobs --}}
        <x-ui.panel title="Upcoming Jobs" :href="route('jobs.index')">
            @if ($upcomingJobs->isEmpty())
                <p class="px-5 py-10 text-center text-sm text-slate-500">No upcoming jobs scheduled.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($upcomingJobs as $job)
                        <li>
                            <a href="{{ route('jobs.show', $job) }}" class="flex items-center justify-between gap-4 px-5 py-3.5 transition hover:bg-slate-50">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-900">{{ $job->title }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ $job->client->name }}</p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="text-sm font-medium text-slate-700">{{ $job->scheduled_date->format('d M') }}</p>
                                    <div class="mt-1">@include('jobs._status-badge', ['status' => $job->status])</div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.panel>

        {{-- Recent Clients --}}
        <x-ui.panel title="Recent Clients" :href="route('clients.index')">
            @if ($recentClients->isEmpty())
                <p class="px-5 py-10 text-center text-sm text-slate-500">No clients yet.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($recentClients as $client)
                        <li>
                            <a href="{{ route('clients.show', $client) }}" class="flex items-center justify-between gap-4 px-5 py-3.5 transition hover:bg-slate-50">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-900">{{ $client->name }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ $client->email ?? $client->phone ?? 'No contact details' }}</p>
                                </div>
                                <span class="shrink-0 text-xs text-slate-400">{{ $client->created_at->diffForHumans() }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.panel>

        {{-- Recent Quotes --}}
        <x-ui.panel title="Recent Quotes" :href="route('quotes.index')">
            @if ($recentQuotes->isEmpty())
                <p class="px-5 py-10 text-center text-sm text-slate-500">No quotes yet.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($recentQuotes as $quote)
                        <li>
                            <a href="{{ route('quotes.show', $quote) }}" class="flex items-center justify-between gap-4 px-5 py-3.5 transition hover:bg-slate-50">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900">{{ $quote->quote_number }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ $quote->job->client->name }}</p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="text-sm font-semibold text-slate-900">{{ $quote->formattedAmount() }}</p>
                                    <div class="mt-1">@include('quotes._status-badge', ['status' => $quote->status])</div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.panel>

        {{-- Recent Invoices --}}
        <x-ui.panel title="Recent Invoices" :href="route('invoices.index')">
            @if ($recentInvoices->isEmpty())
                <p class="px-5 py-10 text-center text-sm text-slate-500">No invoices yet.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($recentInvoices as $invoice)
                        <li>
                            <a href="{{ route('invoices.show', $invoice) }}" class="flex items-center justify-between gap-4 px-5 py-3.5 transition hover:bg-slate-50 {{ $invoice->status === 'overdue' ? 'bg-red-50/50 hover:bg-red-50' : '' }}">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900">{{ $invoice->invoice_number }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ $invoice->job->client->name }}</p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="text-sm font-semibold {{ $invoice->isUnpaid() ? 'text-amber-900' : 'text-slate-900' }}">{{ $invoice->formattedAmount() }}</p>
                                    <div class="mt-1">@include('invoices._status-badge', ['status' => $invoice->status])</div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.panel>
    </div>
</x-app-layout>
