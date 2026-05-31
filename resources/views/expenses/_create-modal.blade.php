<x-ui.modal name="create-expense" :show="$errors->any() || request('open') === 'create-expense'" maxWidth="lg">
    <div class="border-b border-slate-100 px-5 py-4">
        <h2 class="text-base font-semibold text-slate-900">New expense</h2>
        <p class="mt-0.5 text-sm text-slate-500">Record a business cost and attach a receipt.</p>
    </div>

    <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data" class="max-h-[70vh] overflow-y-auto px-5 py-4">
        @csrf
        @include('expenses._form', ['selectedJobId' => request('job_id')])

        <div class="mt-5 flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
            <button type="button" class="btn-secondary" @click="$dispatch('close-modal', 'create-expense')">Cancel</button>
            <button type="submit" class="btn-primary">Save expense</button>
        </div>
    </form>
</x-ui.modal>
