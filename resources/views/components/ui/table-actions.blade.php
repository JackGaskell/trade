@props(['viewRoute', 'editRoute', 'deleteRoute' => null, 'deleteConfirm' => 'Are you sure you want to delete this?'])

<x-dropdown align="right" width="48">
    <x-slot name="trigger">
        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
            </svg>
        </button>
    </x-slot>

    <x-slot name="content">
        <x-dropdown-link :href="$viewRoute">{{ __('View') }}</x-dropdown-link>
        <x-dropdown-link :href="$editRoute">{{ __('Edit') }}</x-dropdown-link>
        @if ($deleteRoute)
            <form method="POST" action="{{ $deleteRoute }}" onsubmit="return confirm('{{ $deleteConfirm }}');">
                @csrf
                @method('DELETE')
                <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-red-600 transition hover:bg-red-50 focus:bg-red-50 focus:outline-none">
                    {{ __('Delete') }}
                </button>
            </form>
        @endif
    </x-slot>
</x-dropdown>
