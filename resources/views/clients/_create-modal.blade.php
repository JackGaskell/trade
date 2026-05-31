<x-ui.modal name="create-client" :show="$errors->any() || request('open') === 'create-client'" maxWidth="lg">
    <div class="border-b border-slate-100 px-5 py-4">
        <h2 class="text-base font-semibold text-slate-900">New client</h2>
        <p class="mt-0.5 text-sm text-slate-500">Add a customer to your trade business.</p>
    </div>

    <form method="POST" action="{{ route('clients.store') }}" class="px-5 py-4">
        @csrf
        @include('clients._form')

        <div class="mt-5 flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
            <button type="button" class="btn-secondary" @click="$dispatch('close-modal', 'create-client')">Cancel</button>
            <button type="submit" class="btn-primary">Save client</button>
        </div>
    </form>
</x-ui.modal>
