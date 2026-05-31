<x-app-layout>
    <x-ui.page-header title="New Expense" description="Record a business cost and attach a receipt." />

    <x-ui.card class="max-w-2xl p-6 sm:p-8">
        <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('expenses._form')
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Save Expense</button>
                <a href="{{ route('expenses.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900">Cancel</a>
            </div>
        </form>
    </x-ui.card>
</x-app-layout>
