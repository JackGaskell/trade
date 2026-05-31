<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Job') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($clients->isEmpty())
                <div class="p-6 bg-white shadow sm:rounded-lg text-center text-gray-500">
                    <p class="mb-4">{{ __('You need at least one client before creating a job.') }}</p>
                    <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                        {{ __('Add Client') }}
                    </a>
                </div>
            @else
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <form method="POST" action="{{ route('jobs.store') }}" class="space-y-6">
                            @csrf

                            @include('jobs._form')

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save Job') }}</x-primary-button>
                                <a href="{{ route('jobs.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
