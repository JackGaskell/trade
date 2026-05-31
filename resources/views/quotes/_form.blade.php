@php
    $quote = $quote ?? null;
@endphp

<section>
    <div class="space-y-6">
        <div>
            <x-input-label for="job_id" :value="__('Job')" />
            <select id="job_id" name="job_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="">{{ __('Select a job') }}</option>
                @foreach ($jobs as $job)
                    <option value="{{ $job->id }}" @selected(old('job_id', $selectedJobId ?? $quote?->job_id) == $job->id)>
                        {{ $job->title }} — {{ $job->client->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('job_id')" />
        </div>

        @if ($quote)
            <div>
                <x-input-label :value="__('Quote Number')" />
                <p class="mt-1 text-sm text-gray-900 font-medium">{{ $quote->quote_number }}</p>
            </div>
        @endif

        <div>
            <x-input-label for="amount" :value="__('Amount (£)')" />
            <x-text-input id="amount" name="amount" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('amount', $quote?->amount)" required />
            <x-input-error class="mt-2" :messages="$errors->get('amount')" />
        </div>

        <div>
            <x-input-label for="description" :value="__('Description')" />
            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="{{ __('Describe the work included in this quote...') }}">{{ old('description', $quote?->description) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>

        <div>
            <x-input-label for="valid_until" :value="__('Valid Until')" />
            <x-text-input id="valid_until" name="valid_until" type="date" class="mt-1 block w-full" :value="old('valid_until', $quote?->valid_until?->format('Y-m-d'))" />
            <x-input-error class="mt-2" :messages="$errors->get('valid_until')" />
        </div>

        <div>
            <x-input-label for="status" :value="__('Status')" />
            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                @foreach (\App\Models\Quote::STATUSES as $status)
                    <option value="{{ $status }}" @selected(old('status', $quote?->status ?? \App\Models\Quote::STATUS_DRAFT) === $status)>
                        {{ \App\Models\Quote::STATUS_LABELS[$status] }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('status')" />
        </div>
    </div>
</section>
