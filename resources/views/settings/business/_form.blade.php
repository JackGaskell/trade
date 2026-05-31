@php
    $profile = $profile ?? null;
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
    ];
@endphp

<section class="space-y-8">
    <div>
        <h3 class="text-sm font-semibold text-slate-900">Business details</h3>
        <p class="mt-1 text-sm text-slate-500">How your business is set up for UK tax and invoicing.</p>
    </div>

    <div class="space-y-5">
        <div>
            <x-input-label for="business_type" :value="__('Business type')" class="text-slate-700" />
            <select
                id="business_type"
                name="business_type"
                class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900"
                required
            >
                @foreach (\App\Models\BusinessProfile::BUSINESS_TYPE_LABELS as $value => $label)
                    <option value="{{ $value }}" @selected(old('business_type', $profile?->business_type) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('business_type')" />
        </div>

        <div>
            <x-input-label for="trading_name" :value="__('Trading name')" class="text-slate-700" />
            <x-text-input
                id="trading_name"
                name="trading_name"
                type="text"
                class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900"
                :value="old('trading_name', $profile?->trading_name)"
                placeholder="e.g. Smith Plumbing Ltd"
            />
            <p class="mt-1.5 text-xs text-slate-500">The name shown on quotes and invoices.</p>
            <x-input-error class="mt-2" :messages="$errors->get('trading_name')" />
        </div>

        <div>
            <x-input-label for="address" :value="__('Business address')" class="text-slate-700" />
            <textarea
                id="address"
                name="address"
                rows="4"
                class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900"
                placeholder="Street&#10;Town&#10;Postcode"
            >{{ old('address', $profile?->address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>
    </div>

    <div class="border-t border-slate-100 pt-8">
        <h3 class="text-sm font-semibold text-slate-900">VAT</h3>
        <p class="mt-1 text-sm text-slate-500">Required if you are registered for VAT with HMRC.</p>

        <div class="mt-5 space-y-5">
            <label class="flex items-start gap-3">
                <input
                    type="hidden"
                    name="vat_registered"
                    value="0"
                >
                <input
                    type="checkbox"
                    name="vat_registered"
                    value="1"
                    class="mt-1 rounded border-slate-300 text-slate-900 shadow-sm focus:ring-slate-900"
                    x-model="vatRegistered"
                    @checked(old('vat_registered', $profile?->vat_registered))
                >
                <span>
                    <span class="block text-sm font-medium text-slate-900">VAT registered</span>
                    <span class="block text-xs text-slate-500">Your invoices will include VAT once this is enabled.</span>
                </span>
            </label>
            <x-input-error class="mt-2" :messages="$errors->get('vat_registered')" />

            <div x-show="vatRegistered" x-cloak>
                <x-input-label for="vat_number" :value="__('VAT number')" class="text-slate-700" />
                <x-text-input
                    id="vat_number"
                    name="vat_number"
                    type="text"
                    class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900"
                    :value="old('vat_number', $profile?->vat_number)"
                    placeholder="GB123456789"
                />
                <p class="mt-1.5 text-xs text-slate-500">UK format: GB followed by 9 or 12 digits.</p>
                <x-input-error class="mt-2" :messages="$errors->get('vat_number')" />
            </div>
        </div>
    </div>

    <div class="border-t border-slate-100 pt-8">
        <h3 class="text-sm font-semibold text-slate-900">Tax & CIS</h3>
        <p class="mt-1 text-sm text-slate-500">Used for Self Assessment exports and construction industry reporting.</p>

        <div class="mt-5 space-y-5">
            <div>
                <x-input-label for="utr" :value="__('Unique Taxpayer Reference (UTR)')" class="text-slate-700" />
                <x-text-input
                    id="utr"
                    name="utr"
                    type="text"
                    inputmode="numeric"
                    autocomplete="off"
                    class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900"
                    :value="old('utr', $profile?->utr)"
                    placeholder="10 digit UTR"
                />
                <p class="mt-1.5 text-xs text-slate-500">Stored encrypted. Leave blank to keep your existing UTR.</p>
                <x-input-error class="mt-2" :messages="$errors->get('utr')" />
            </div>

            <label class="flex items-start gap-3">
                <input
                    type="hidden"
                    name="cis_registered"
                    value="0"
                >
                <input
                    type="checkbox"
                    name="cis_registered"
                    value="1"
                    class="mt-1 rounded border-slate-300 text-slate-900 shadow-sm focus:ring-slate-900"
                    @checked(old('cis_registered', $profile?->cis_registered))
                >
                <span>
                    <span class="block text-sm font-medium text-slate-900">CIS registered</span>
                    <span class="block text-xs text-slate-500">Construction Industry Scheme — common for builders, electricians, and similar trades.</span>
                </span>
            </label>
            <x-input-error class="mt-2" :messages="$errors->get('cis_registered')" />
        </div>
    </div>

    <div class="border-t border-slate-100 pt-8">
        <h3 class="text-sm font-semibold text-slate-900">Accounting year</h3>
        <p class="mt-1 text-sm text-slate-500">Most sole traders use the UK tax year: 6 April to 5 April.</p>

        <div class="mt-5 grid gap-5 sm:grid-cols-2">
            <div>
                <x-input-label for="accounting_year_start_month" :value="__('Year starts in')" class="text-slate-700" />
                <select
                    id="accounting_year_start_month"
                    name="accounting_year_start_month"
                    class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900"
                    required
                >
                    @foreach ($months as $value => $label)
                        <option value="{{ $value }}" @selected((int) old('accounting_year_start_month', $profile?->accounting_year_start_month ?? 4) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('accounting_year_start_month')" />
            </div>

            <div>
                <x-input-label for="accounting_year_start_day" :value="__('Day of month')" class="text-slate-700" />
                <x-text-input
                    id="accounting_year_start_day"
                    name="accounting_year_start_day"
                    type="number"
                    min="1"
                    max="31"
                    class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900"
                    :value="old('accounting_year_start_day', $profile?->accounting_year_start_day ?? 6)"
                    required
                />
                <x-input-error class="mt-2" :messages="$errors->get('accounting_year_start_day')" />
            </div>
        </div>

        @if ($profile?->exists)
            <p class="mt-3 text-xs text-slate-500">
                Current period: {{ $profile->accountingYearLabel() }}
            </p>
        @endif
    </div>
</section>
