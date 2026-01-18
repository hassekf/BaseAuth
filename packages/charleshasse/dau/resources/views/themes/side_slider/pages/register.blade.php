<div class="space-y-6">
    <div class="space-y-2">
        <h1 class="text-3xl font-semibold text-zinc-900 dark:text-white">Create an account</h1>
        @if (Route::has('login'))
            <p class="text-sm text-zinc-500 dark:text-white/60">
                Already have an account?
                <a class="font-semibold text-zinc-900 hover:text-zinc-700 dark:text-white dark:hover:text-white/80" href="{{ route('login') }}" wire:navigate>Log in</a>
            </p>
        @endif
    </div>

    <form wire:submit.prevent="submit" class="flex flex-col gap-5">
        <x-dau::input
            name="name"
            :label="__('Name')"
            type="text"
            wire:model.lazy="name"
            wire:blur="normalizeName"
            autocomplete="name"
            required
            placeholder="Your name"
        ></x-dau::input>

        <x-dau::input
            name="email"
            :label="__('Email address')"
            type="email"
            wire:model.lazy="email"
            autocomplete="email"
            required
            placeholder="email@example.com"
        ></x-dau::input>

        <x-dau::input
            name="password"
            :label="__('Password')"
            type="password"
            wire:model.lazy="password"
            autocomplete="new-password"
            required
        ></x-dau::input>

        <x-dau::input
            name="password_confirmation"
            :label="__('Confirm password')"
            type="password"
            wire:model.lazy="password_confirmation"
            autocomplete="new-password"
            required
        ></x-dau::input>

        <label class="flex items-center gap-2 text-sm text-zinc-600 dark:text-white/70">
            <input
                type="checkbox"
                class="h-4 w-4 rounded border-zinc-300 bg-white text-zinc-900 focus:ring-zinc-900/20 dark:border-white/20 dark:bg-white/10 dark:text-white dark:focus:ring-white/20"
                required
            />
            <span>I agree to the Terms &amp; Conditions</span>
        </label>

        <button type="submit" class="w-full rounded-lg bg-[#6d5bd7] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#5f4fc6] dark:bg-[#6d5bd7] dark:hover:bg-[#5f4fc6]">
            {{ __('Create account') }}
        </button>
    </form>

    @php
        $hasSocial = config('dau.social.google') || config('dau.social.apple');
    @endphp

    @if ($hasSocial)
        <div class="flex items-center gap-3 text-xs text-zinc-400 dark:text-white/50">
            <span class="h-px flex-1 bg-zinc-200 dark:bg-white/10"></span>
            or register with
            <span class="h-px flex-1 bg-zinc-200 dark:bg-white/10"></span>
        </div>

        @php
            $socialCount = (config('dau.social.google') ? 1 : 0) + (config('dau.social.apple') ? 1 : 0);
            $socialGridClass = $socialCount > 1 ? 'sm:grid-cols-2' : '';
        @endphp

        <div class="grid gap-3 {{ $socialGridClass }}">
            @if (config('dau.social.google'))
                <button class="flex w-full items-center justify-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50 dark:border-white/15 dark:bg-white/5 dark:text-white dark:hover:bg-white/10">
                    <span class="text-lg text-zinc-600 dark:text-white" aria-hidden="true">G</span>
                    Google
                </button>
            @endif
            @if (config('dau.social.apple'))
                <button class="flex w-full items-center justify-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50 dark:border-white/15 dark:bg-white/5 dark:text-white dark:hover:bg-white/10">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.365 13.699c.021 2.319 2.048 3.092 2.07 3.102-.016.054-.33 1.131-1.09 2.24-.658.94-1.34 1.876-2.416 1.895-1.058.019-1.398-.628-2.608-.628-1.21 0-1.588.609-2.591.648-1.038.04-1.83-.997-2.49-1.933-1.36-1.934-2.4-5.464-1.007-7.852.691-1.184 1.93-1.935 3.273-1.955 1.02-.02 1.987.691 2.608.691.62 0 1.788-.855 3.014-.729.513.021 1.955.208 2.88 1.564-.075.047-1.72 1.006-1.703 3.0Zm-1.98-6.297c.553-.669.926-1.6.824-2.53-.796.032-1.758.530-2.331 1.198-.51.588-.955 1.53-.835 2.432.886.07 1.789-.45 2.342-1.1Z" fill="currentColor"/>
                    </svg>
                    Apple
                </button>
            @endif
        </div>
    @endif
</div>
