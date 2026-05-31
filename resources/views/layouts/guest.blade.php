<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'The Trade Tool') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans bg-surface text-slate-900 antialiased">
        <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
            <a href="/" class="mb-8 flex items-center gap-3">
                <div class="brand-mark h-11 w-11">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-bold tracking-tight text-slate-900">The Trade Tool</p>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Business finance</p>
                </div>
            </a>

            <div class="w-full max-w-md rounded-2xl border border-slate-200/80 bg-white p-8 shadow-card-md">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
