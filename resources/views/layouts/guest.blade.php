<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        <title>{{ config('brand.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans bg-surface text-slate-900 antialiased">
        <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
            <x-ui.logo href="{{ url('/') }}" size="lg" class="mb-10" />

            <div class="w-full max-w-md rounded-2xl border border-slate-200/80 bg-white p-8 shadow-card-md">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
