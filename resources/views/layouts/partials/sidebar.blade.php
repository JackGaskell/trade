@php
    $navItems = [
        ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['route' => 'clients.index', 'label' => 'Clients', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        ['route' => 'jobs.index', 'label' => 'Jobs', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        ['route' => 'quotes.index', 'label' => 'Quotes', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['route' => 'invoices.index', 'label' => 'Invoices', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['route' => 'expenses.index', 'label' => 'Expenses', 'icon' => 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z'],
        ['route' => 'settings.business.edit', 'label' => 'Business', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
    ];
@endphp

<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-60 flex-col border-r border-slate-200/80 bg-white transition-transform duration-200 ease-in-out lg:translate-x-0"
>
    <div class="flex h-14 items-center border-b border-slate-100/80 px-5">
        <x-ui.logo href="{{ route('dashboard') }}" size="md" />
    </div>

    <nav class="flex-1 space-y-0.5 px-3 py-2">
        @foreach ($navItems as $item)
            @php
                $isActive = request()->routeIs(str_replace('.index', '.*', $item['route'])) || request()->routeIs($item['route']);
            @endphp
            <a
                href="{{ route($item['route']) }}"
                class="sidebar-link {{ $isActive ? 'sidebar-link-active' : '' }}"
                @click="sidebarOpen = false"
            >
                <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $item['icon'] }}" />
                </svg>
                {{ __($item['label']) }}
            </a>
        @endforeach
    </nav>

    <div class="border-t border-slate-100 p-3">
        <div class="rounded-xl bg-slate-50 p-3">
            <p class="truncate text-xs font-semibold text-slate-900">{{ Auth::user()->name }}</p>
            <p class="truncate text-[11px] text-slate-500">{{ Auth::user()->email }}</p>
            <div class="mt-2.5 flex gap-3">
                <a href="{{ route('profile.edit') }}" class="text-[11px] font-semibold text-slate-500 hover:text-cyan-600">{{ __('Profile') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-[11px] font-semibold text-slate-500 hover:text-cyan-600">{{ __('Log out') }}</button>
                </form>
            </div>
        </div>
    </div>
</aside>
