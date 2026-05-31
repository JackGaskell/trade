@php
    $expense = $expense ?? null;
@endphp

<section class="space-y-5">
    <div>
        <x-input-label for="expense_date" :value="__('Date')" class="text-slate-700" />
        <x-text-input
            id="expense_date"
            name="expense_date"
            type="date"
            class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900"
            :value="old('expense_date', $expense?->expense_date?->format('Y-m-d') ?? now()->format('Y-m-d'))"
            required
        />
        <x-input-error class="mt-2" :messages="$errors->get('expense_date')" />
    </div>

    <div>
        <x-input-label for="supplier" :value="__('Supplier')" class="text-slate-700" />
        <x-text-input
            id="supplier"
            name="supplier"
            type="text"
            class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900"
            :value="old('supplier', $expense?->supplier)"
            placeholder="e.g. Screwfix, BP, ABC Subcontractors"
            required
        />
        <x-input-error class="mt-2" :messages="$errors->get('supplier')" />
    </div>

    <div>
        <x-input-label for="category" :value="__('Category')" class="text-slate-700" />
        <select
            id="category"
            name="category"
            class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900"
            required
        >
            <option value="">{{ __('Select a category') }}</option>
            @foreach (\App\Models\Expense::CATEGORY_LABELS as $value => $label)
                <option value="{{ $value }}" @selected(old('category', $expense?->category) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('category')" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Description')" class="text-slate-700" />
        <textarea
            id="description"
            name="description"
            rows="3"
            class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900"
            placeholder="What was this expense for?"
        >{{ old('description', $expense?->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <x-input-label for="amount" :value="__('Amount (£)')" class="text-slate-700" />
            <x-text-input
                id="amount"
                name="amount"
                type="number"
                step="0.01"
                min="0.01"
                class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900"
                :value="old('amount', $expense?->amount)"
                required
            />
            <x-input-error class="mt-2" :messages="$errors->get('amount')" />
        </div>

        @if ($vatRegistered)
            <div>
                <x-input-label for="vat_amount" :value="__('VAT amount (£)')" class="text-slate-700" />
                <x-text-input
                    id="vat_amount"
                    name="vat_amount"
                    type="number"
                    step="0.01"
                    min="0"
                    class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900"
                    :value="old('vat_amount', $expense?->vat_amount)"
                />
                <p class="mt-1.5 text-xs text-slate-500">VAT portion of this expense, if reclaimable.</p>
                <x-input-error class="mt-2" :messages="$errors->get('vat_amount')" />
            </div>
        @endif
    </div>

    <div>
        <x-input-label for="job_id" :value="__('Link to job (optional)')" class="text-slate-700" />
        <select
            id="job_id"
            name="job_id"
            class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900"
        >
            <option value="">{{ __('No job — general business expense') }}</option>
            @foreach ($jobs as $job)
                <option value="{{ $job->id }}" @selected(old('job_id', $selectedJobId ?? $expense?->job_id) == $job->id)>
                    {{ $job->title }} — {{ $job->client->name }}
                </option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('job_id')" />
    </div>

    <div>
        <x-input-label for="receipt" :value="__('Receipt (photo or PDF)')" class="text-slate-700" />
        <input
            id="receipt"
            name="receipt"
            type="file"
            accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
            class="mt-1.5 block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200"
        />
        <p class="mt-1.5 text-xs text-slate-500">JPG, PNG or PDF up to 5 MB.</p>
        <x-input-error class="mt-2" :messages="$errors->get('receipt')" />

        @if ($expense?->hasReceipt())
            <div class="mt-3 flex flex-wrap items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                <a href="{{ route('expenses.receipt', $expense) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700" target="_blank">
                    View current receipt
                </a>
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remove_receipt" value="1" class="rounded border-slate-300 text-slate-900 shadow-sm focus:ring-slate-900" @checked(old('remove_receipt'))>
                    Remove receipt
                </label>
            </div>
        @endif
    </div>
</section>
