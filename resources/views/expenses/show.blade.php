<x-app-layout>
    <x-ui.page-header :title="$expense->supplier" description="Expense details and receipt.">
        <x-slot name="actions">
            <a href="{{ route('expenses.edit', $expense) }}" class="btn-secondary">Edit</a>
        </x-slot>
    </x-ui.page-header>

    <x-ui.flash />

    <div class="space-y-6">
        <x-ui.card class="p-6">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Date</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $expense->expense_date->format('d M Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Category</dt>
                    <dd class="mt-1">@include('expenses._category-badge', ['category' => $expense->category])</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Amount</dt>
                    <dd class="mt-1 text-lg font-bold text-slate-900">{{ $expense->formattedAmount() }}</dd>
                </div>
                @if ($expense->vat_amount !== null)
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">VAT amount</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $expense->formattedVatAmount() }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Linked job</dt>
                    <dd class="mt-1 text-sm text-slate-900">
                        @if ($expense->job)
                            <a href="{{ route('jobs.show', $expense->job) }}" class="font-medium text-brand-600 hover:text-brand-700">{{ $expense->job->title }}</a>
                            <span class="text-slate-500"> — {{ $expense->job->client->name }}</span>
                        @else
                            General business expense
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Receipt</dt>
                    <dd class="mt-1 text-sm text-slate-900">
                        @if ($expense->hasReceipt())
                            <a href="{{ route('expenses.receipt', $expense) }}" class="font-medium text-brand-600 hover:text-brand-700" target="_blank">
                                {{ $expense->receipt_original_name ?? 'View receipt' }}
                            </a>
                        @else
                            No receipt attached
                        @endif
                    </dd>
                </div>
                @if ($expense->description)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Description</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-slate-900">{{ $expense->description }}</dd>
                    </div>
                @endif
            </dl>
        </x-ui.card>

        <x-ui.card class="p-6">
            <h3 class="text-sm font-semibold text-red-600">Delete Expense</h3>
            <p class="mt-1 text-sm text-slate-500">This will permanently remove the expense and any attached receipt.</p>
            <form method="POST" action="{{ route('expenses.destroy', $expense) }}" class="mt-4" onsubmit="return confirm('Are you sure you want to delete this expense?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Delete Expense</button>
            </form>
        </x-ui.card>

        <a href="{{ route('expenses.index') }}" class="inline-flex text-sm font-medium text-slate-500 hover:text-slate-900">&larr; Back to expenses</a>
    </div>
</x-app-layout>
