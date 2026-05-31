<header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-slate-200/80 bg-white/80 px-4 backdrop-blur-md lg:px-8">
    <button
        @click="sidebarOpen = true"
        class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-900 lg:hidden"
    >
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div class="flex flex-1 items-center justify-between">
        <div class="lg:hidden">
            <p class="text-sm font-bold text-slate-900">The Trade Tool</p>
        </div>

        <div class="hidden lg:block">
            <p class="text-sm text-slate-500">{{ now()->format('l, j F Y') }}</p>
        </div>

        <div class="hidden items-center gap-3 sm:flex">
            <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">{{ Auth::user()->name }}</a>
        </div>
    </div>
</header>
