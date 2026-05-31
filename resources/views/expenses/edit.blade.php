<x-app-layout>
    <x-ui.page-header title="Edit Expense" :description="$expense->supplier" />

    <x-ui.card class="max-w-2xl p-6 sm:p-8">
        <form method="POST" action="{{ route('expenses.update', $expense) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            @include('expenses._form')
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Update Expense</button>
                <a href="{{ route('expenses.show', $expense) }}" class="text-sm font-medium text-slate-500 hover:text-slate-900">Cancel</a>
            </div>
        </form>
    </x-ui.card>
</x-app-layout>
