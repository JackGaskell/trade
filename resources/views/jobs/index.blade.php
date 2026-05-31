<x-app-layout>
    <x-ui.page-header title="Jobs" description="Track work from enquiry through to completion.">
        <x-slot name="actions">
            <x-ui.open-modal-button modal="create-job">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Job
            </x-ui.open-modal-button>
        </x-slot>
    </x-ui.page-header>

    <x-ui.flash />

    <x-ui.card :padding="false">
        @if ($jobs->isEmpty())
            <x-ui.empty-state
                message="No jobs yet. Create a job for one of your clients."
                actionLabel="New Job"
                actionModal="create-job"
            />
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Job</th>
                            <th class="hidden sm:table-cell">Client</th>
                            <th>Status</th>
                            <th class="hidden md:table-cell">Scheduled</th>
                            <th class="text-right"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobs as $job)
                            <tr>
                                <td>
                                    <a href="{{ route('jobs.show', $job) }}" class="font-semibold text-slate-900 hover:text-brand-600">
                                        {{ $job->title }}
                                    </a>
                                    <p class="mt-0.5 text-xs text-slate-500 sm:hidden">{{ $job->client->name }}</p>
                                </td>
                                <td class="hidden sm:table-cell">
                                    <a href="{{ route('clients.show', $job->client) }}" class="text-slate-600 hover:text-brand-600">
                                        {{ $job->client->name }}
                                    </a>
                                </td>
                                <td>@include('jobs._status-badge', ['status' => $job->status])</td>
                                <td class="hidden text-slate-600 md:table-cell">
                                    {{ $job->scheduled_date?->format('d M Y') ?? '—' }}
                                </td>
                                <td class="text-right">
                                    <x-ui.table-actions
                                        :viewRoute="route('jobs.show', $job)"
                                        :editRoute="route('jobs.edit', $job)"
                                        :deleteRoute="route('jobs.destroy', $job)"
                                        deleteConfirm="Are you sure you want to delete this job?"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($jobs->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $jobs->links() }}
                </div>
            @endif
        @endif
    </x-ui.card>

    @include('jobs._create-modal')
</x-app-layout>
