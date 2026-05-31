<x-app-layout>
    <x-ui.page-header
        title="Business & Tax Profile"
        description="Your UK business details for invoicing, VAT, and future tax exports."
    />

    <x-ui.flash />

    <x-ui.card class="max-w-2xl p-6 sm:p-8">
        <form method="POST" action="{{ route('settings.business.update') }}" class="space-y-6" x-data="{ vatRegistered: {{ old('vat_registered', $profile->vat_registered) ? 'true' : 'false' }} }">
            @csrf
            @method('PATCH')
            @include('settings.business._form')

            <div class="flex items-center gap-3 border-t border-slate-100 pt-6">
                <button type="submit" class="btn-primary">Save business profile</button>

                @if (session('status') === 'business-profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 3000)"
                        class="text-link text-sm"
                    >Saved.</p>
                @endif
            </div>
        </form>
    </x-ui.card>
</x-app-layout>
