<x-ui.modal name="create-invoice" :show="$errors->any() || request('open') === 'create-invoice'" maxWidth="lg">
    <div class="border-b border-slate-100 px-5 py-4">
        <h2 class="text-base font-semibold text-slate-900">New invoice</h2>
        <p class="mt-0.5 text-sm text-slate-500">Create an invoice to get paid for a job.</p>
    </div>

    @if ($jobs->isEmpty())
        <div class="px-5 py-8 text-center">
            <p class="text-sm text-slate-500">You need at least one job before creating an invoice.</p>
            <a href="{{ route('jobs.index', ['open' => 'create-job']) }}" class="btn-primary mt-4 inline-flex">Add job</a>
        </div>
    @else
        <form method="POST" action="{{ route('invoices.store') }}" class="max-h-[70vh] overflow-y-auto px-5 py-4">
            @csrf
            @include('invoices._form', ['selectedJobId' => request('job_id')])

            <div class="mt-5 flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                <button type="button" class="btn-secondary" @click="$dispatch('close-modal', 'create-invoice')">Cancel</button>
                <button type="submit" class="btn-primary">Save invoice</button>
            </div>
        </form>
    @endif
</x-ui.modal>
