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
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-[radial-gradient(circle_at_top,_#f1edf9_0%,_#e3d9f4_45%,_#d4cfe3_100%)] text-zinc-900 antialiased dark:bg-[radial-gradient(circle_at_top,_#5b5571_0%,_#2c2737_45%,_#181422_100%)] dark:text-white">
        @if(config('dau.layout.mode_toggle'))
            <div class="fixed right-4 top-4 z-50">
                <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle" aria-label="Toggle dark mode" />
            </div>
        @endif

        <div class="flex min-h-screen items-center justify-center px-6 py-12">
            <div class="w-full max-w-5xl overflow-hidden rounded-3xl border border-white/40 bg-white/70 shadow-[0_40px_120px_-40px_rgba(90,70,160,0.35)] dark:border-white/10 dark:bg-[#2b2739]/90 dark:shadow-[0_40px_120px_-40px_rgba(8,5,20,0.9)]">
                <div class="grid min-h-[620px] md:grid-cols-2">
                    <section class="relative flex flex-col justify-between bg-gradient-to-br from-[#b9a7ff] via-[#8b7bf0] to-[#6a5cc7] p-10 text-white dark:from-[#6d5bd7] dark:via-[#4b3ea8] dark:to-[#2e264e]">
                        <div class="flex items-center justify-end relative z-10">
                            <a class="inline-flex items-center gap-2 rounded-full border border-white/40 bg-white/15 px-4 py-1 text-xs font-medium transition hover:bg-white/25 dark:border-white/20 dark:bg-white/10 dark:hover:bg-white/20" href="{{ route('home') }}" wire:navigate>
                                Back to website
                                <span aria-hidden="true">→</span>
                            </a>
                        </div>

                        <div class="space-y-4 relative z-10">
                            <p class="text-3xl font-semibold leading-tight">
                                Dynamic Auth UI,<br />SideSlider Theme
                            </p>
                            <div class="flex items-center gap-2">
                                <span class="h-1.5 w-6 rounded-full bg-white/50"></span>
                                <span class="h-1.5 w-6 rounded-full bg-white/30"></span>
                                <span class="h-1.5 w-6 rounded-full bg-white"></span>
                            </div>
                        </div>
                        <img src="https://picsum.photos/600/600" alt="Photo frame" class="pointer-events-none absolute left-0 top-0 z-0 h-full w-full object-cover opacity-30 dark:opacity-30" />
                    </section>

                    <section class="flex items-center justify-center bg-white px-8 py-12 md:px-12 dark:bg-[#2b2739]">
                        <div class="w-full max-w-md space-y-6">
                            {{ $slot }}
                        </div>
                    </section>
                </div>
            </div>
        </div>
        @include('dau::partials.tuner')
        @fluxScripts
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    </body>
</html>
