<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Simple business finance software for UK tradespeople. Manage clients, jobs, quotes, invoices and expenses in one place.">

        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        <title>{{ config('brand.name') }} — {{ config('brand.tagline') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans bg-white text-slate-900 antialiased">
        <header class="landing-nav">
            <div class="mx-auto flex h-16 max-w-5xl items-center justify-between px-6">
                <x-ui.logo href="{{ url('/') }}" size="md" />

                <nav class="flex items-center gap-1">
                    @auth
                        <a href="{{ route('dashboard') }}" class="landing-btn">Dashboard</a>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="landing-btn-ghost hidden sm:inline-flex">Log in</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="landing-btn">Get started</a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        <main>
            {{-- Hero --}}
            <section class="mx-auto max-w-5xl px-6 pb-20 pt-20 sm:pb-28 sm:pt-28">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="brand-eyebrow">{{ config('brand.tagline') }}</p>
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-900 sm:text-[3.25rem] sm:leading-[1.08]">
                        Everything your trade business needs. Nothing it doesn't.
                    </h1>
                    <p class="mx-auto mt-6 max-w-lg text-lg leading-relaxed text-slate-500">
                        Clients, jobs, quotes, invoices and expenses — organised in one calm, professional workspace.
                    </p>

                    <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="landing-btn">Go to dashboard</a>
                        @else
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="landing-btn">Start free</a>
                            @endif
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="landing-btn-ghost">Log in</a>
                            @endif
                        @endauth
                    </div>
                </div>

                {{-- App preview --}}
                <div class="landing-preview-wrap mx-auto mt-16 max-w-3xl sm:mt-20">
                    <div class="landing-preview">
                        <div class="landing-preview-bar">
                            <div class="flex gap-1.5">
                                <span class="h-2.5 w-2.5 rounded-full bg-slate-200"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-slate-200"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-slate-200"></span>
                            </div>
                            <span class="text-xs font-medium text-slate-400">Dashboard</span>
                        </div>

                        <div class="p-6 sm:p-8">
                            <div class="grid gap-3 sm:grid-cols-3">
                                <div class="landing-stat-pill-accent">
                                    <p class="text-xs font-medium text-cyan-600">Outstanding</p>
                                    <p class="money mt-1 text-xl font-semibold text-slate-900">£4,250</p>
                                </div>
                                <div class="landing-stat-pill">
                                    <p class="text-xs font-medium text-slate-400">Active jobs</p>
                                    <p class="money mt-1 text-xl font-semibold text-slate-900">6</p>
                                </div>
                                <div class="landing-stat-pill">
                                    <p class="text-xs font-medium text-slate-400">Profit</p>
                                    <p class="money mt-1 text-xl font-semibold text-emerald-600">£1,840</p>
                                </div>
                            </div>

                            <div class="mt-6 overflow-hidden rounded-xl border border-slate-100">
                                <div class="border-b border-slate-100 bg-slate-50/80 px-4 py-2.5">
                                    <p class="text-xs font-medium text-slate-500">Recent invoices</p>
                                </div>
                                <div class="landing-row">
                                    <div>
                                        <p class="text-sm font-medium text-slate-900">Kitchen rewire — Smith</p>
                                        <p class="text-xs text-slate-400">Due 12 Jun</p>
                                    </div>
                                    <p class="money text-sm font-medium text-slate-900">£850.00</p>
                                </div>
                                <div class="landing-row">
                                    <div>
                                        <p class="text-sm font-medium text-slate-900">Bathroom fit-out — Patel</p>
                                        <p class="text-xs text-slate-400">Due 18 Jun</p>
                                    </div>
                                    <p class="money text-sm font-medium text-slate-900">£2,400.00</p>
                                </div>
                                <div class="landing-row">
                                    <div>
                                        <p class="text-sm font-medium text-slate-900">Boiler service — Jones</p>
                                        <p class="text-xs text-emerald-600">Paid</p>
                                    </div>
                                    <p class="money text-sm font-medium text-slate-900">£180.00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Features --}}
            <section class="border-t border-slate-100 bg-surface">
                <div class="mx-auto grid max-w-5xl gap-12 px-6 py-20 sm:grid-cols-3 sm:gap-8">
                    <div class="flex flex-col items-center text-center sm:items-start sm:text-left">
                        <div class="landing-feature-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h2 class="mt-4 text-sm font-semibold text-slate-900">Quotes & invoices</h2>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500">Create professional quotes, convert to invoices, and track what's owed.</p>
                    </div>

                    <div class="flex flex-col items-center text-center sm:items-start sm:text-left">
                        <div class="landing-feature-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="mt-4 text-sm font-semibold text-slate-900">Jobs & clients</h2>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500">Keep every job and client detail in one place, from first enquiry to final payment.</p>
                    </div>

                    <div class="flex flex-col items-center text-center sm:items-start sm:text-left">
                        <div class="landing-feature-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="mt-4 text-sm font-semibold text-slate-900">Expenses & profit</h2>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500">Log receipts, monitor spending, and see a clear picture of your margins.</p>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-slate-100 py-10">
            <div class="mx-auto flex max-w-5xl flex-col items-center justify-between gap-4 px-6 sm:flex-row">
                <x-ui.logo href="{{ url('/') }}" size="sm" />
                <p class="text-xs text-slate-400">&copy; {{ date('Y') }} {{ config('brand.name') }}</p>
            </div>
        </footer>
    </body>
</html>
