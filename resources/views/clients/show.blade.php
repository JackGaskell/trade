<x-app-layout>
    <x-ui.page-header :title="$client->name" description="Client details and job history.">
        <x-slot name="actions">
            <a href="{{ route('clients.edit', $client) }}" class="btn-secondary">Edit</a>
        </x-slot>
    </x-ui.page-header>

    <x-ui.flash />

    <div class="space-y-6">
        <x-ui.card class="p-6">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Email</dt>
                    <dd class="mt-1 text-sm text-slate-900">
                        @if ($client->email)
                            <a href="mailto:{{ $client->email }}" class="text-brand-600 hover:text-brand-700">{{ $client->email }}</a>
                        @else — @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Phone</dt>
                    <dd class="mt-1 text-sm text-slate-900">
                        @if ($client->phone)
                            <a href="tel:{{ $client->phone }}" class="text-brand-600 hover:text-brand-700">{{ $client->phone }}</a>
                        @else — @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Added</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $client->created_at->format('d M Y') }}</dd>
                </div>
                @if ($client->address)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Address</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-slate-900">{{ $client->address }}</dd>
                    </div>
                @endif
                @if ($client->notes)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Notes</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-slate-900">{{ $client->notes }}</dd>
                    </div>
                @endif
            </dl>
        </x-ui.card>

        <x-ui.panel title="Jobs" :href="route('jobs.index', ['open' => 'create-job', 'client_id' => $client->id])" linkText="Add job">
            @if ($client->jobs->isEmpty())
                <p class="px-5 py-8 text-center text-sm text-slate-500">No jobs for this client yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th class="hidden sm:table-cell">Scheduled</th>
                                <th class="text-right"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($client->jobs as $job)
                                <tr>
                                    <td>
                                        <a href="{{ route('jobs.show', $job) }}" class="font-semibold text-slate-900 hover:text-brand-600">{{ $job->title }}</a>
                                    </td>
                                    <td>@include('jobs._status-badge', ['status' => $job->status])</td>
                                    <td class="hidden text-slate-600 sm:table-cell">{{ $job->scheduled_date?->format('d M Y') ?? '—' }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('jobs.show', $job) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-ui.panel>

        <x-ui.card class="p-6">
            <h3 class="text-sm font-semibold text-red-600">Delete Client</h3>
            <p class="mt-1 text-sm text-slate-500">Once deleted, this client and their jobs cannot be recovered.</p>
            <form method="POST" action="{{ route('clients.destroy', $client) }}" class="mt-4" onsubmit="return confirm('Are you sure you want to delete this client?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Delete Client</button>
            </form>
        </x-ui.card>

        <a href="{{ route('clients.index') }}" class="inline-flex text-sm font-medium text-slate-500 hover:text-slate-900">&larr; Back to clients</a>
    </div>
</x-app-layout>
