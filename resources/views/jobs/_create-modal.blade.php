<x-ui.modal name="create-job" :show="$errors->any() || request('open') === 'create-job'" maxWidth="lg">
    <div class="border-b border-slate-100 px-5 py-4">
        <h2 class="text-base font-semibold text-slate-900">New job</h2>
        <p class="mt-0.5 text-sm text-slate-500">Track work from enquiry through to completion.</p>
    </div>

    @if ($clients->isEmpty())
        <div class="px-5 py-8 text-center">
            <p class="text-sm text-slate-500">You need at least one client before creating a job.</p>
            <a href="{{ route('clients.index', ['open' => 'create-client']) }}" class="btn-primary mt-4 inline-flex">Add client</a>
        </div>
    @else
        <form method="POST" action="{{ route('jobs.store') }}" class="max-h-[70vh] overflow-y-auto px-5 py-4">
            @csrf
            @include('jobs._form', ['selectedClientId' => request('client_id')])

            <div class="mt-5 flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                <button type="button" class="btn-secondary" @click="$dispatch('close-modal', 'create-job')">Cancel</button>
                <button type="submit" class="btn-primary">Save job</button>
            </div>
        </form>
    @endif
</x-ui.modal>
