<x-ui.modal name="create-quote" :show="$errors->any() || request('open') === 'create-quote'" maxWidth="lg">
    <div class="border-b border-slate-100 px-5 py-4">
        <h2 class="text-base font-semibold text-slate-900">New quote</h2>
        <p class="mt-0.5 text-sm text-slate-500">Send a quote for one of your jobs.</p>
    </div>

    @if ($jobs->isEmpty())
        <div class="px-5 py-8 text-center">
            <p class="text-sm text-slate-500">You need at least one job before creating a quote.</p>
            <a href="{{ route('jobs.index', ['open' => 'create-job']) }}" class="btn-primary mt-4 inline-flex">Add job</a>
        </div>
    @else
        <form method="POST" action="{{ route('quotes.store') }}" class="max-h-[70vh] overflow-y-auto px-5 py-4">
            @csrf
            @include('quotes._form', ['selectedJobId' => request('job_id')])

            <div class="mt-5 flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                <button type="button" class="btn-secondary" @click="$dispatch('close-modal', 'create-quote')">Cancel</button>
                <button type="submit" class="btn-primary">Save quote</button>
            </div>
        </form>
    @endif
</x-ui.modal>
