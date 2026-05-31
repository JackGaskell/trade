@php
    $invoice = $invoice ?? null;
@endphp

<section>
    <div class="space-y-6">
        <div>
            <x-input-label for="job_id" :value="__('Job')" />
            <select id="job_id" name="job_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="">{{ __('Select a job') }}</option>
                @foreach ($jobs as $job)
                    <option value="{{ $job->id }}" @selected(old('job_id', $selectedJobId ?? $invoice?->job_id) == $job->id)>
                        {{ $job->title }} — {{ $job->client->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('job_id')" />
        </div>

        @if ($invoice)
            <div>
                <x-input-label :value="__('Invoice Number')" />
                <p class="mt-1 text-sm text-gray-900 font-medium">{{ $invoice->invoice_number }}</p>
            </div>
        @endif

        <div>
            <x-input-label for="amount" :value="__('Amount (£)')" />
            <x-text-input id="amount" name="amount" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('amount', $invoice?->amount)" required />
            <x-input-error class="mt-2" :messages="$errors->get('amount')" />
        </div>

        <div>
            <x-input-label for="due_date" :value="__('Due Date')" />
            <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" :value="old('due_date', $invoice?->due_date?->format('Y-m-d'))" />
            <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
        </div>

        <div>
            <x-input-label for="status" :value="__('Status')" />
            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                @foreach (\App\Models\Invoice::STATUSES as $status)
                    <option value="{{ $status }}" @selected(old('status', $invoice?->status ?? \App\Models\Invoice::STATUS_DRAFT) === $status)>
                        {{ \App\Models\Invoice::STATUS_LABELS[$status] }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('status')" />
        </div>

        <div>
            <x-input-label for="notes" :value="__('Notes')" />
            <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="{{ __('Payment terms, bank details, or other notes...') }}">{{ old('notes', $invoice?->notes) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
        </div>
    </div>
</section>
