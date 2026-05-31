<header class="topbar">
    <button
        @click="sidebarOpen = true"
        class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 lg:hidden"
    >
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div class="flex flex-1 items-center justify-between">
        <div class="lg:hidden">
            <x-ui.logo href="{{ route('dashboard') }}" size="sm" :show-text="true" />
        </div>

        <div class="hidden lg:block">
            <p class="text-sm font-medium text-slate-500">{{ now()->format('l, j F Y') }}</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('profile.edit') }}" class="hidden rounded-lg px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-cyan-600 sm:inline-flex">
                {{ Auth::user()->name }}
            </a>
        </div>
    </div>
</header>
