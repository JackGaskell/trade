<x-app-layout>
    <x-ui.page-header title="Expenses" description="Track business costs for tax and job profitability.">
        <x-slot name="actions">
            <x-ui.open-modal-button modal="create-expense">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Expense
            </x-ui.open-modal-button>
        </x-slot>
    </x-ui.page-header>

    <x-ui.flash />

    <div class="stat-card mb-6 flex items-center justify-between">
        <div>
            <p class="metric-label">This month</p>
            <p class="mt-0.5 money text-2xl font-bold text-slate-900">{{ \App\Models\Invoice::formatMoney($expensesThisMonth) }}</p>
        </div>
        <p class="hidden text-sm text-slate-500 sm:block">{{ now()->format('F Y') }}</p>
    </div>

    <x-ui.card :padding="false">
        @if ($expenses->isEmpty())
            <x-ui.empty-state
                message="No expenses recorded yet. Add your first receipt to start tracking costs."
                actionLabel="New Expense"
                actionModal="create-expense"
            />
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th class="hidden sm:table-cell">Category</th>
                            <th>Amount</th>
                            <th class="hidden md:table-cell">Job</th>
                            <th class="hidden lg:table-cell">Receipt</th>
                            <th class="text-right"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                            <tr>
                                <td class="text-slate-600">{{ $expense->expense_date->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('expenses.show', $expense) }}" class="row-link">
                                        {{ $expense->supplier }}
                                    </a>
                                    @if ($expense->description)
                                        <p class="mt-0.5 truncate text-xs text-slate-500 sm:hidden">{{ $expense->description }}</p>
                                    @endif
                                </td>
                                <td class="hidden sm:table-cell">
                                    @include('expenses._category-badge', ['category' => $expense->category])
                                </td>
                                <td class="font-semibold text-slate-900">{{ $expense->formattedAmount() }}</td>
                                <td class="hidden text-slate-600 md:table-cell">
                                    @if ($expense->job)
                                        <a href="{{ route('jobs.show', $expense->job) }}" class="text-slate-600 hover:text-cyan-600">{{ $expense->job->title }}</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="hidden lg:table-cell">
                                    @if ($expense->hasReceipt())
                                        <a href="{{ route('expenses.receipt', $expense) }}" class="text-link text-sm" target="_blank">View</a>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <x-ui.table-actions
                                        :viewRoute="route('expenses.show', $expense)"
                                        :editRoute="route('expenses.edit', $expense)"
                                        :deleteRoute="route('expenses.destroy', $expense)"
                                        deleteConfirm="Are you sure you want to delete this expense?"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($expenses->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $expenses->links() }}
                </div>
            @endif
        @endif
    </x-ui.card>

    @include('expenses._create-modal')
</x-app-layout>
