@php
    $themeOptions = ['default', 'side_slider', 'side_full'];
    $labelOptions = ['above', 'inside', 'inset', 'floating', 'hidden'];
    $query = request()->query();

    $currentTheme = $query['theme'] ?? config('dau.layout.theme', 'default');
    $currentLabels = $query['labels'] ?? config('dau.layout.labels_position', 'above');
    $socialGoogle = array_key_exists('social_google', $query) ? filter_var($query['social_google'], FILTER_VALIDATE_BOOLEAN) : config('dau.social.google');
    $socialApple = array_key_exists('social_apple', $query) ? filter_var($query['social_apple'], FILTER_VALIDATE_BOOLEAN) : config('dau.social.apple');
    $passwordReveal = array_key_exists('password_reveal', $query) ? filter_var($query['password_reveal'], FILTER_VALIDATE_BOOLEAN) : config('dau.features.password_reveal');
    $autocompleteEmail = array_key_exists('autocomplete_email', $query) ? filter_var($query['autocomplete_email'], FILTER_VALIDATE_BOOLEAN) : config('dau.features.autocomplete_email');
    $modeToggle = array_key_exists('mode_toggle', $query) ? filter_var($query['mode_toggle'], FILTER_VALIDATE_BOOLEAN) : config('dau.layout.mode_toggle');
    $featureRegister = array_key_exists('feature_register', $query) ? filter_var($query['feature_register'], FILTER_VALIDATE_BOOLEAN) : config('dau.features.register');
    $loginTwoSteps = array_key_exists('login_two_steps', $query) ? filter_var($query['login_two_steps'], FILTER_VALIDATE_BOOLEAN) : config('dau.features.login_two_steps');
    $rememberMe = array_key_exists('remember_me', $query) ? filter_var($query['remember_me'], FILTER_VALIDATE_BOOLEAN) : config('dau.features.login_remember_me');
@endphp

<div
    x-data="{
        open: false,
        theme: '{{ $currentTheme }}',
        labels: '{{ $currentLabels }}',
        socialGoogle: {{ $socialGoogle ? 'true' : 'false' }},
        socialApple: {{ $socialApple ? 'true' : 'false' }},
        passwordReveal: {{ $passwordReveal ? 'true' : 'false' }},
        autocompleteEmail: {{ $autocompleteEmail ? 'true' : 'false' }},
        modeToggle: {{ $modeToggle ? 'true' : 'false' }},
        featureRegister: {{ $featureRegister ? 'true' : 'false' }},
        loginTwoSteps: {{ $loginTwoSteps ? 'true' : 'false' }},
        rememberMe: {{ $rememberMe ? 'true' : 'false' }},
        apply() {
            const params = new URLSearchParams(window.location.search);
            params.set('theme', this.theme);
            params.set('labels', this.labels);
            params.set('social_google', this.socialGoogle ? '1' : '0');
            params.set('social_apple', this.socialApple ? '1' : '0');
            params.set('password_reveal', this.passwordReveal ? '1' : '0');
            params.set('autocomplete_email', this.autocompleteEmail ? '1' : '0');
            params.set('login_two_steps', this.loginTwoSteps ? '1' : '0');
            params.set('mode_toggle', this.modeToggle ? '1' : '0');
            params.set('feature_register', this.featureRegister ? '1' : '0');
            params.set('remember_me', this.rememberMe ? '1' : '0');
            window.location.search = params.toString();
        },
        reset() {
            const params = new URLSearchParams(window.location.search);
            ['theme', 'labels', 'social_google', 'social_apple', 'password_reveal', 'autocomplete_email', 'login_two_steps', 'mode_toggle', 'feature_register', 'remember_me'].forEach((key) => {
                params.delete(key);
            });
            window.location.search = params.toString();
        }
    }"
    class="fixed bottom-5 right-5 z-50 text-sm"
>
    <button
        type="button"
        class="flex items-center gap-2 rounded-full bg-black/80 px-4 py-2 text-xs font-semibold text-white shadow-lg backdrop-blur dark:bg-white/90 dark:text-slate-900"
        x-on:click="open = !open"
    >
        Theme Tuner
    </button>

    <div
        x-show="open"
        x-cloak
        class="mt-3 w-72 rounded-2xl border border-black/10 bg-white p-4 shadow-2xl dark:border-white/10 dark:bg-slate-900"
    >
        <div class="space-y-3">
            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                Theme
            </label>
            <select
                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                x-model="theme"
            >
                @foreach ($themeOptions as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>

            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                Labels
            </label>
            <select
                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                x-model="labels"
            >
                @foreach ($labelOptions as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>

            <div class="grid grid-cols-2 gap-3 pt-2 text-xs text-slate-700 dark:text-slate-200">
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="h-4 w-4 rounded border-slate-300" x-model="socialGoogle" />
                    Google
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="h-4 w-4 rounded border-slate-300" x-model="socialApple" />
                    Apple
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="h-4 w-4 rounded border-slate-300" x-model="passwordReveal" />
                    Reveal
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="h-4 w-4 rounded border-slate-300" x-model="autocompleteEmail" />
                    Autocomplete
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="h-4 w-4 rounded border-slate-300" x-model="modeToggle" />
                    Mode toggle
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="h-4 w-4 rounded border-slate-300" x-model="featureRegister" />
                    Register
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="h-4 w-4 rounded border-slate-300" x-model="loginTwoSteps" />
                    Two steps
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="h-4 w-4 rounded border-slate-300" x-model="rememberMe" />
                    Remember me
                </label>
            </div>

            <div class="flex gap-2 pt-2">
                <button
                    type="button"
                    class="flex-1 rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900"
                    x-on:click="apply"
                >
                    Apply
                </button>
                <button
                    type="button"
                    class="flex-1 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 dark:border-slate-700 dark:text-slate-300 dark:hover:text-white"
                    x-on:click="reset"
                >
                    Reset
                </button>
            </div>
        </div>
    </div>
</div>
