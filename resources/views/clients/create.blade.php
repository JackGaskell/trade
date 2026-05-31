<x-app-layout>
    <x-ui.page-header title="New Client" description="Add a new customer to your trade business." />

    <x-ui.card class="max-w-2xl p-6 sm:p-8">
        <form method="POST" action="{{ route('clients.store') }}" class="space-y-6">
            @csrf
            @include('clients._form')
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Save Client</button>
                <a href="{{ route('clients.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900">Cancel</a>
            </div>
        </form>
    </x-ui.card>
</x-app-layout>
