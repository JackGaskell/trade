<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Quote') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($jobs->isEmpty())
                <div class="p-6 bg-white shadow sm:rounded-lg text-center text-gray-500">
                    <p class="mb-4">{{ __('You need at least one job before creating a quote.') }}</p>
                    <a href="{{ route('jobs.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                        {{ __('Add Job') }}
                    </a>
                </div>
            @else
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <form method="POST" action="{{ route('quotes.store') }}" class="space-y-6">
                            @csrf

                            @include('quotes._form')

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save Quote') }}</x-primary-button>
                                <a href="{{ route('quotes.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
