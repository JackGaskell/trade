<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="The Trade Tool helps UK tradespeople send quotes, chase invoices, track jobs and see profit — without spreadsheets or generic accounting software.">

        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        <title>{{ config('brand.name') }} — {{ config('brand.tagline') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="landing-page font-sans antialiased">
        <header class="landing-nav">
            <div class="landing-container flex h-[4.5rem] items-center justify-between">
                <x-ui.logo href="{{ url('/') }}" size="lg" :show-tagline="true" />

                <nav class="flex items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="landing-btn">Dashboard</a>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="landing-btn-secondary hidden sm:inline-flex">Log in</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="landing-btn">Start free</a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        <main>
            <section class="landing-hero">
                <div class="landing-hero-glow" aria-hidden="true"></div>

                <div class="landing-container">
                    <div class="landing-hero-copy">
                        <p class="landing-eyebrow">
                            <span class="landing-eyebrow-dot" aria-hidden="true"></span>
                            {{ config('brand.tagline') }}
                        </p>

                        <h1 class="landing-headline">
                            Stop chasing <span class="landing-highlight">invoices</span> on a Sunday night.
                        </h1>

                        <p class="landing-lead">
                            The Trade Tool is built for <strong class="text-slate-700">UK tradespeople</strong> — send quotes, track jobs, log expenses in pounds, and see what you've actually earned before tax time catches you out.
                        </p>

                        <div class="landing-cta-group">
                            @auth
                                <a href="{{ route('dashboard') }}" class="landing-btn">Go to dashboard</a>
                            @else
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="landing-btn">Start free — I'm a UK tradesperson</a>
                                @endif
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}" class="landing-btn-secondary">Log in</a>
                                @endif
                            @endauth
                        </div>

                        <ul class="landing-trust" aria-label="Why UK trades choose us">
                            <li class="landing-trust-pill">Free to start</li>
                            <li class="landing-trust-pill">£ GBP ready</li>
                            <li class="landing-trust-pill">Quote → invoice fast</li>
                        </ul>
                    </div>

                    <div class="landing-preview-wrap">
                        <div class="landing-preview-glow" aria-hidden="true"></div>
                        <div class="landing-preview">
                            <div class="landing-preview-chrome">
                                <div class="flex gap-1.5" aria-hidden="true">
                                    <span class="landing-chrome-dot"></span>
                                    <span class="landing-chrome-dot"></span>
                                    <span class="landing-chrome-dot"></span>
                                </div>
                                <span class="landing-preview-label">Your trade dashboard</span>
                            </div>

                            <div class="landing-preview-body">
                                <div class="landing-metrics">
                                    <div class="landing-metric landing-metric-highlight">
                                        <span class="landing-metric-label">Money owed to you</span>
                                        <span class="landing-metric-value money">£4,250</span>
                                    </div>
                                    <div class="landing-metric">
                                        <span class="landing-metric-label">Jobs on the go</span>
                                        <span class="landing-metric-value money">6</span>
                                    </div>
                                    <div class="landing-metric">
                                        <span class="landing-metric-label">Profit this month</span>
                                        <span class="landing-metric-value money landing-metric-profit">£1,840</span>
                                    </div>
                                </div>

                                <div class="landing-table">
                                    <div class="landing-table-head">
                                        <span>Invoices you need to chase</span>
                                    </div>
                                    <div class="landing-table-row">
                                        <div>
                                            <p class="landing-table-title">Kitchen rewire — Smith</p>
                                            <p class="landing-table-meta">Customer owes you · Due 12 Jun</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="landing-table-amount money">£850.00</p>
                                            <span class="landing-pill landing-pill-warning">Chase</span>
                                        </div>
                                    </div>
                                    <div class="landing-table-row">
                                        <div>
                                            <p class="landing-table-title">Bathroom fit-out — Patel</p>
                                            <p class="landing-table-meta">Customer owes you · Due 18 Jun</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="landing-table-amount money">£2,400.00</p>
                                            <span class="landing-pill landing-pill-warning">Chase</span>
                                        </div>
                                    </div>
                                    <div class="landing-table-row">
                                        <div>
                                            <p class="landing-table-title">Boiler service — Jones</p>
                                            <p class="landing-table-meta">Paid — money in the bank</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="landing-table-amount money">£180.00</p>
                                            <span class="landing-pill landing-pill-success">Paid</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="landing-trades" aria-label="Trades we support">
                <div class="landing-container">
                    <p class="landing-trades-intro">Built for every trade that works on site in the <span class="landing-highlight">UK</span></p>
                    <ul class="landing-trades-list">
                        @foreach (config('brand.trades') as $trade)
                            <li>{{ $trade }}</li>
                        @endforeach
                        <li class="landing-trades-more">+ every other trade</li>
                    </ul>
                </div>
            </section>

            <section class="landing-pain">
                <div class="landing-container">
                    <div class="landing-section-header">
                        <p class="landing-section-eyebrow">Sound familiar?</p>
                        <h2 class="landing-section-title">The admin that steals your evenings</h2>
                        <p class="landing-section-lead">You didn't go self-employed to sit behind a laptop. Generic software wasn't built for life in a van.</p>
                    </div>

                    <div class="landing-pain-grid">
                        <article class="landing-pain-card">
                            <h3 class="landing-pain-title">"Who still owes me money?"</h3>
                            <p class="landing-pain-copy">Invoices buried in email, amounts in your head — cash flow surprises you when you least need it.</p>
                            <p class="landing-pain-fix">See every outstanding invoice in pounds</p>
                        </article>

                        <article class="landing-pain-card">
                            <h3 class="landing-pain-title">"Where is that job up to?"</h3>
                            <p class="landing-pain-copy">Details in texts, quotes in Word, notes on paper — you piece it together before every call-back.</p>
                            <p class="landing-pain-fix">One record per client, quote to paid</p>
                        </article>

                        <article class="landing-pain-card">
                            <h3 class="landing-pain-title">"What did I actually make?"</h3>
                            <p class="landing-pain-copy">Materials, fuel, subs — profit only feels real when the accountant asks, and then it's too late.</p>
                            <p class="landing-pain-fix">Track expenses and profit as you go</p>
                        </article>
                    </div>
                </div>
            </section>

            <section class="landing-features">
                <div class="landing-container">
                    <div class="landing-section-header">
                        <p class="landing-section-eyebrow">What you get</p>
                        <h2 class="landing-section-title">Everything to run the <span class="landing-highlight">business side</span> of your trade</h2>
                        <p class="landing-section-lead">No office bloat. Just what UK trades need to quote, bill, and stay profitable.</p>
                    </div>

                    <div class="landing-feature-grid">
                        <article class="landing-feature-card">
                            <div class="landing-feature-icon" aria-hidden="true">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="landing-feature-title">Win work with proper quotes</h3>
                            <p class="landing-feature-copy">Send clear quotes from your phone or van. Turn them into invoices when the job's agreed — no retyping.</p>
                        </article>

                        <article class="landing-feature-card">
                            <div class="landing-feature-icon" aria-hidden="true">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="landing-feature-title">Run jobs without the chaos</h3>
                            <p class="landing-feature-copy">Every customer, every job, every note in one place. Know what's booked, finished, or waiting on payment.</p>
                        </article>

                        <article class="landing-feature-card">
                            <div class="landing-feature-icon" aria-hidden="true">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="landing-feature-title">Know your numbers in £</h3>
                            <p class="landing-feature-copy">Log receipts and job costs as you go. See profit on the dashboard — not months later with your accountant.</p>
                        </article>
                    </div>
                </div>
            </section>

            <section class="landing-steps">
                <div class="landing-container">
                    <div class="landing-section-header">
                        <p class="landing-section-eyebrow">Simple as it should be</p>
                        <h2 class="landing-section-title">Running before your next job starts</h2>
                    </div>

                    <ol class="landing-steps-list">
                        <li class="landing-step">
                            <span class="landing-step-num" aria-hidden="true">1</span>
                            <div>
                                <h3 class="landing-step-title">Add your first customer</h3>
                                <p class="landing-step-copy">Name, site, contact — the basics you already know. Takes a minute.</p>
                            </div>
                        </li>
                        <li class="landing-step">
                            <span class="landing-step-num" aria-hidden="true">2</span>
                            <div>
                                <h3 class="landing-step-title">Quote the job, then invoice it</h3>
                                <p class="landing-step-copy">Send a quote when they ask. Flip to an invoice when they say yes. Chase overdue from one screen.</p>
                            </div>
                        </li>
                        <li class="landing-step">
                            <span class="landing-step-num" aria-hidden="true">3</span>
                            <div>
                                <h3 class="landing-step-title">See what you've really earned</h3>
                                <p class="landing-step-copy">Expenses in, payments tracked — outstanding cash and profit without a spreadsheet.</p>
                            </div>
                        </li>
                    </ol>
                </div>
            </section>

            <section class="landing-cta-band">
                <div class="landing-container">
                    <div class="landing-cta-card">
                        <div class="landing-cta-copy">
                            <p class="landing-cta-eyebrow">For UK trades only</p>
                            <h2 class="landing-cta-title">Your van, your tools, your books — <span class="landing-highlight">finally together</span>.</h2>
                            <p class="landing-cta-lead">Electricians, plumbers, builders and trades across the UK — stop losing weekends to admin. Start free today.</p>
                        </div>
                        @guest
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="landing-btn landing-cta-btn">Start free</a>
                            @endif
                        @else
                            <a href="{{ route('dashboard') }}" class="landing-btn landing-cta-btn">Open dashboard</a>
                        @endguest
                    </div>
                </div>
            </section>
        </main>

        <footer class="landing-footer">
            <div class="landing-container landing-footer-inner">
                <x-ui.logo href="{{ url('/') }}" size="sm" />
                <p class="landing-footer-copy">&copy; {{ date('Y') }} {{ config('brand.name') }}. {{ config('brand.tagline') }}.</p>
            </div>
        </footer>
    </body>
</html>
