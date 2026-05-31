@php
    $client = $client ?? null;
@endphp

<section class="space-y-5">
    <div>
        <x-input-label for="name" :value="__('Name')" class="text-slate-700" />
        <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900" :value="old('name', $client?->name)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>
    <div>
        <x-input-label for="email" :value="__('Email')" class="text-slate-700" />
        <x-text-input id="email" name="email" type="email" class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900" :value="old('email', $client?->email)" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>
    <div>
        <x-input-label for="phone" :value="__('Phone')" class="text-slate-700" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900" :value="old('phone', $client?->phone)" />
        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
    </div>
    <div>
        <x-input-label for="address" :value="__('Address')" class="text-slate-700" />
        <textarea id="address" name="address" rows="3" class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900">{{ old('address', $client?->address) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('address')" />
    </div>
    <div>
        <x-input-label for="notes" :value="__('Notes')" class="text-slate-700" />
        <textarea id="notes" name="notes" rows="4" class="mt-1.5 block w-full rounded-lg border-slate-200 shadow-sm focus:border-slate-900 focus:ring-slate-900">{{ old('notes', $client?->notes) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>
</section>
