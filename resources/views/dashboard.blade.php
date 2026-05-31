<x-app-layout>
    <x-ui.page-header
        title="Dashboard"
        description="What needs your attention today?"
    />

    {{-- Hero overview — gradient lives here --}}
    <div class="hero-card relative mb-6">
        <div class="relative z-10">
            <p class="text-sm font-medium text-white/80">{{ now()->format('F Y') }} overview</p>

            <div class="mt-6 grid gap-6 sm:grid-cols-3">
                <a href="{{ route('expenses.index') }}" class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/20 backdrop-blur-sm transition hover:bg-white/15">
                    <p class="hero-label">Profit Estimate</p>
                    <p class="hero-value {{ $profitEstimate < 0 ? 'text-red-200' : '' }}">{{ \App\Models\Invoice::formatMoney($profitEstimate) }}</p>
                    <p class="mt-1 text-xs text-white/50">Paid income minus expenses</p>
                </a>

                <a href="{{ route('invoices.index') }}" class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/20 backdrop-blur-sm transition hover:bg-white/15">
                    <p class="hero-label">Outstanding Invoices</p>
                    <p class="hero-value">{{ \App\Models\Invoice::formatMoney($outstandingTotal) }}</p>
                    <p class="mt-1 text-xs text-white/50">Awaiting payment</p>
                </a>

                <a href="{{ route('invoices.index') }}" class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/20 backdrop-blur-sm transition hover:bg-white/15 {{ $overdueTotal > 0 ? 'ring-amber-300/40' : '' }}">
                    <p class="hero-label">Overdue Invoices</p>
                    <p class="hero-value {{ $overdueTotal > 0 ? 'text-amber-200' : '' }}">{{ \App\Models\Invoice::formatMoney($overdueTotal) }}</p>
                    <p class="mt-1 text-xs text-white/50">{{ $overdueTotal > 0 ? 'Needs chasing' : 'All clear' }}</p>
                </a>
            </div>
        </div>
    </div>

    {{-- Secondary metrics --}}
    <div class="mb-8 grid grid-cols-2 gap-3 lg:grid-cols-4">
        <x-ui.stat-card
            label="Quotes Awaiting Response"
            :value="$quotesAwaitingResponse"
            hint="Sent to clients"
            :href="route('quotes.index')"
            :variant="$quotesAwaitingResponse > 0 ? 'warning' : 'default'"
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
            label="Total Clients"
            :value="$clientsCount"
            hint="On your books"
            :href="route('clients.index')"
        />
    </div>

    {{-- Panels --}}
    <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
        <x-ui.panel title="Upcoming Jobs" :href="route('jobs.index')">
            @if ($upcomingJobs->isEmpty())
                <p class="px-5 py-10 text-center text-sm text-slate-500">No upcoming jobs scheduled.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($upcomingJobs as $job)
                        <li>
                            <a href="{{ route('jobs.show', $job) }}" class="panel-row">
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

        <x-ui.panel title="Recent Clients" :href="route('clients.index')">
            @if ($recentClients->isEmpty())
                <p class="px-5 py-10 text-center text-sm text-slate-500">No clients yet.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($recentClients as $client)
                        <li>
                            <a href="{{ route('clients.show', $client) }}" class="panel-row">
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

        <x-ui.panel title="Recent Quotes" :href="route('quotes.index')">
            @if ($recentQuotes->isEmpty())
                <p class="px-5 py-10 text-center text-sm text-slate-500">No quotes yet.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($recentQuotes as $quote)
                        <li>
                            <a href="{{ route('quotes.show', $quote) }}" class="panel-row">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900">{{ $quote->quote_number }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ $quote->job->client->name }}</p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="money text-sm font-bold text-slate-900">{{ $quote->formattedAmount() }}</p>
                                    <div class="mt-1">@include('quotes._status-badge', ['status' => $quote->status])</div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.panel>

        <x-ui.panel title="Recent Invoices" :href="route('invoices.index')">
            @if ($recentInvoices->isEmpty())
                <p class="px-5 py-10 text-center text-sm text-slate-500">No invoices yet.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($recentInvoices as $invoice)
                        <li>
                            <a href="{{ route('invoices.show', $invoice) }}" class="panel-row {{ $invoice->status === 'overdue' ? 'bg-red-50/50' : '' }}">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900">{{ $invoice->invoice_number }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ $invoice->job->client->name }}</p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="money text-sm font-bold {{ $invoice->isUnpaid() ? 'text-amber-700' : 'text-slate-900' }}">{{ $invoice->formattedAmount() }}</p>
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
