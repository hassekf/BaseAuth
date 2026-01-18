<div>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        @if(config('dau.social.google') || config('dau.social.apple'))
        <div class="flex space-y-2 flex-col">
                @if(config('dau.social.google'))
                <flux:button class="w-full">
                    <x-slot name="icon">
                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M23.06 12.25C23.06 11.47 22.99 10.72 22.86 10H12.5V14.26H18.42C18.16 15.63 17.38 16.79 16.21 17.57V20.34H19.78C21.86 18.42 23.06 15.6 23.06 12.25Z" fill="#4285F4"/>
                            <path d="M12.4997 23C15.4697 23 17.9597 22.02 19.7797 20.34L16.2097 17.57C15.2297 18.23 13.9797 18.63 12.4997 18.63C9.63969 18.63 7.20969 16.7 6.33969 14.1H2.67969V16.94C4.48969 20.53 8.19969 23 12.4997 23Z" fill="#34A853"/>
                            <path d="M6.34 14.0899C6.12 13.4299 5.99 12.7299 5.99 11.9999C5.99 11.2699 6.12 10.5699 6.34 9.90995V7.06995H2.68C1.93 8.54995 1.5 10.2199 1.5 11.9999C1.5 13.7799 1.93 15.4499 2.68 16.9299L5.53 14.7099L6.34 14.0899Z" fill="#FBBC05"/>
                            <path d="M12.4997 5.38C14.1197 5.38 15.5597 5.94 16.7097 7.02L19.8597 3.87C17.9497 2.09 15.4697 1 12.4997 1C8.19969 1 4.48969 3.47 2.67969 7.07L6.33969 9.91C7.20969 7.31 9.63969 5.38 12.4997 5.38Z" fill="#EA4335"/>
                        </svg>
                    </x-slot>
                    Continue with Google
                </flux:button>
                @endif
                @if(config('dau.social.apple'))
                <flux:button class="w-full">
                    <x-slot name="icon">
                        <svg width="30" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.365 13.699c.021 2.319 2.048 3.092 2.07 3.102-.016.054-.33 1.131-1.09 2.24-.658.94-1.34 1.876-2.416 1.895-1.058.019-1.398-.628-2.608-.628-1.21 0-1.588.609-2.591.648-1.038.04-1.83-.997-2.49-1.933-1.36-1.934-2.4-5.464-1.007-7.852.691-1.184 1.93-1.935 3.273-1.955 1.02-.02 1.987.691 2.608.691.62 0 1.788-.855 3.014-.729.513.021 1.955.208 2.88 1.564-.075.047-1.72 1.006-1.703 3.0Zm-1.98-6.297c.553-.669.926-1.6.824-2.53-.796.032-1.758.53-2.331 1.198-.51.588-.955 1.53-.835 2.432.886.07 1.789-.45 2.342-1.1Z" fill="currentColor"/>
                        </svg>
                    </x-slot>
                    Continue with Apple
                </flux:button>
                @endif
            </div>
        <flux:separator text="or" class="my-4" />
        @endif

        <form wire:submit.prevent="login" class="flex flex-col gap-6">

            <!-- Email Address -->
            <x-dau::input
                name="email"
                :label="__('Email address')"
                type="email"
                wire:model.lazy="email"
                autocomplete="email"
                required
                autofocus
                placeholder="email@example.com"
            ></x-dau::input>

            <!-- Password -->
            <x-dau::input
                name="password"
                :label="__('Password')"
                type="password"
                wire:model.lazy="password"
                autocomplete="current-password"
                required
            ></x-dau::input>

            @if (Route::has('password.request'))
                <a class="-mt-4 text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <!-- Remember Me -->
            @if (config('dau.features.login_remember_me'))
            <label class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-300">
                <input
                    type="checkbox"
                    wire:model="remember"
                    class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white dark:focus:ring-white/20"
                />
                <span>{{ __('Remember me') }}</span>
            </label>
            @endif



            <div class="flex items-center justify-end">
                <button type="submit" class="w-full rounded-md bg-zinc-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900/20 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100 dark:focus:ring-white/20" data-test="login-button">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ __('Don\'t have an account?') }}</span>
                <a class="font-semibold text-zinc-900 hover:text-zinc-700 dark:text-white dark:hover:text-zinc-200" href="{{ route('register') }}" wire:navigate>
                    {{ __('Sign up') }}
                </a>
            </div>
        @endif
    </div>
</div>
