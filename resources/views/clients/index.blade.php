<x-app-layout>
    <x-ui.page-header title="Clients" description="Manage your customers and their contact details.">
        <x-slot name="actions">
            <x-ui.open-modal-button modal="create-client">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Client
            </x-ui.open-modal-button>
        </x-slot>
    </x-ui.page-header>

    <x-ui.flash />

    <x-ui.card :padding="false">
        @if ($clients->isEmpty())
            <x-ui.empty-state
                message="No clients yet. Add your first client to get started."
                actionLabel="New Client"
                actionModal="create-client"
            />
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="hidden sm:table-cell">Phone</th>
                            <th class="hidden md:table-cell">Email</th>
                            <th class="hidden lg:table-cell">Jobs</th>
                            <th class="hidden lg:table-cell">Last Activity</th>
                            <th class="text-right"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                            @php
                                $lastActivity = $client->last_job_activity
                                    ? max($client->updated_at, \Illuminate\Support\Carbon::parse($client->last_job_activity))
                                    : $client->updated_at;
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('clients.show', $client) }}" class="font-semibold text-slate-900 hover:text-brand-600">
                                        {{ $client->name }}
                                    </a>
                                    <p class="mt-0.5 text-xs text-slate-500 sm:hidden">{{ $client->phone ?? $client->email ?? '—' }}</p>
                                </td>
                                <td class="hidden text-slate-600 sm:table-cell">{{ $client->phone ?? '—' }}</td>
                                <td class="hidden text-slate-600 md:table-cell">{{ $client->email ?? '—' }}</td>
                                <td class="hidden lg:table-cell">
                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700">
                                        {{ $client->jobs_count }}
                                    </span>
                                </td>
                                <td class="hidden text-slate-500 lg:table-cell">{{ $lastActivity->diffForHumans() }}</td>
                                <td class="text-right">
                                    <x-ui.table-actions
                                        :viewRoute="route('clients.show', $client)"
                                        :editRoute="route('clients.edit', $client)"
                                        :deleteRoute="route('clients.destroy', $client)"
                                        deleteConfirm="Are you sure you want to delete this client?"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($clients->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $clients->links() }}
                </div>
            @endif
        @endif
    </x-ui.card>

    @include('clients._create-modal')
</x-app-layout>
