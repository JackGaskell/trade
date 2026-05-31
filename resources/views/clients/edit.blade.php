<x-app-layout>
    <x-ui.page-header title="Edit Client" :description="$client->name" />

    <x-ui.card class="max-w-2xl p-6 sm:p-8">
        <form method="POST" action="{{ route('clients.update', $client) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('clients._form')
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Update Client</button>
                <a href="{{ route('clients.show', $client) }}" class="text-sm font-medium text-slate-500 hover:text-slate-900">Cancel</a>
            </div>
        </form>
    </x-ui.card>
</x-app-layout>
