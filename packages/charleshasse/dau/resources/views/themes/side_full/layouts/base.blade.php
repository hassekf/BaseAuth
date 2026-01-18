<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>{{ $title ?? config('app.name') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-white text-slate-900 antialiased dark:bg-slate-950 dark:text-white">
        @if(config('dau.layout.mode_toggle'))
            <div class="fixed right-4 top-4 z-50">
                <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle" aria-label="Toggle dark mode" />
            </div>
        @endif

        <div class="grid min-h-screen lg:grid-cols-2">
            <section class="flex items-center justify-center px-6 py-12 lg:px-12">
                <div class="w-full max-w-md space-y-8">
                    <div class="flex items-center gap-2 text-lg font-semibold">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm dark:bg-white dark:text-slate-900">
                            <span class="text-base font-bold">DAU</span>
                        </span>
                        <span>Dynamic Auth UI</span>
                    </div>

                    <div class="space-y-2">
                        <h1 class="text-3xl font-semibold">Welcome Back</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Please enter your details to continue.
                        </p>
                    </div>

                    <div class="flex rounded-xl bg-slate-100 p-1 text-sm font-medium text-slate-500 dark:bg-slate-900/60 dark:text-slate-400">
                        <a class="flex-1 rounded-lg bg-white px-4 py-2 text-center text-slate-900 shadow-sm transition hover:bg-slate-50 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700" href="{{ route('login') }}" wire:navigate>
                            Sign in
                        </a>
                        <a class="flex-1 rounded-lg px-4 py-2 text-center transition hover:text-slate-900 dark:hover:text-white" href="{{ route('register') }}" wire:navigate>
                            Sign up
                        </a>
                    </div>

                    {{ $slot }}

                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Here goes some additional information about security and privacy.
                    </p>
                </div>
            </section>

            <section class="relative hidden overflow-hidden lg:flex">
                <div class="absolute inset-0 bg-gradient-to-br from-sky-100 via-sky-200 to-sky-300 dark:from-slate-900 dark:via-slate-900 dark:to-sky-900"></div>
                <div class="absolute -left-24 top-24 h-64 w-64 rounded-full bg-white/60 blur-3xl dark:bg-white/10"></div>
                <div class="absolute bottom-16 right-10 h-52 w-52 rounded-full bg-sky-500/30 blur-3xl dark:bg-sky-500/20"></div>
                <div class="relative z-10 flex w-full items-center justify-center">
                    <div class="relative">
                        <div class="absolute -inset-10 rounded-[48px] bg-white/20 blur-2xl dark:bg-white/10"></div>
                        <div class="relative h-64 w-56 rounded-[36px] bg-gradient-to-br from-sky-500 via-sky-600 to-sky-700 shadow-[0_30px_80px_-40px_rgba(15,90,160,0.9)] dark:shadow-[0_30px_80px_-40px_rgba(15,90,160,0.5)]">
                            <div class="absolute inset-8 rounded-[26px] bg-sky-200/80 dark:bg-sky-300/10"></div>
                            <div class="absolute inset-16 rounded-full border-4 border-sky-300/70 dark:border-sky-200/30"></div>
                            <div class="absolute right-10 top-24 h-12 w-12 rounded-full bg-sky-200/90 dark:bg-sky-200/30"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('dau::partials.tuner')
        @fluxScripts
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    </body>
</html>
