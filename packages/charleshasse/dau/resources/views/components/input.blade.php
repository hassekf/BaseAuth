@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'id' => null,
    'required' => false,
    'autocomplete' => null,
    'placeholder' => null,
])

@php
    $labelPosition = config('dau.layout.labels_position', 'above');
    $autocompleteEmail = config('dau.features.autocomplete_email', true);
    $revealEnabled = config('dau.features.password_reveal', true);
    $inputId = $id ?? $name;
    $hasLabel = filled($label);
    $hasReveal = $revealEnabled && $type === 'password';
    $hasEmailAutocomplete = $autocompleteEmail && $type === 'email';
    $baseClasses = 'w-full rounded-md border border-zinc-300 bg-white px-3 text-sm text-zinc-900 placeholder:text-zinc-400 focus:border-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:placeholder:text-zinc-500 dark:focus:border-white dark:focus:ring-white/10';
    $paddingClasses = in_array($labelPosition, ['inside', 'floating'], true) ? 'pt-6 pb-2' : 'py-2';
    $inputClasses = trim($baseClasses.' '.$paddingClasses.($hasReveal ? ' pr-10' : ''));
    $resolvedPlaceholder = $placeholder ?? ($labelPosition === 'hidden' ? $label : null);
    $hasInteractive = $hasReveal || $hasEmailAutocomplete;

    $alpineData = null;
    if ($hasInteractive) {
        $alpineData = sprintf(<<<'ALPINE'
{
    value: '',
    suggestionSuffix: '',
    suggestionDomain: '',
    correction: '',
    reveal: false,
    hasEmail: %s,
    domains: ['gmail.com', 'outlook.com', 'hotmail.com', 'yahoo.com', 'icloud.com'],
    corrections: {
        'gamil': 'gmail.com',
        'gnail': 'gmail.com',
        'gmial': 'gmail.com',
        'hotnail': 'hotmail.com',
        'hotmial': 'hotmail.com',
        'outlok': 'outlook.com',
        'outllok': 'outlook.com',
        'yaho': 'yahoo.com',
        'yahho': 'yahoo.com'
    },
    init(input) {
        this.value = input.value || '';
        this.update();
    },
    handleInput(event) {
        this.value = event.target.value;
        this.update();
    },
    handleTab(event) {
        if (!this.suggestionSuffix) {
            return;
        }
        event.preventDefault();
        this.applySuggestion();
    },
    applySuggestion() {
        if (!this.suggestionDomain) {
            return;
        }
        const atIndex = this.value.indexOf('@');
        if (atIndex === -1) {
            return;
        }
        const nextValue = this.value.slice(0, atIndex + 1) + this.suggestionDomain;
        this.setValue(nextValue);
    },
    applyCorrection(domain) {
        const atIndex = this.value.indexOf('@');
        if (atIndex === -1) {
            return;
        }
        const nextValue = this.value.slice(0, atIndex + 1) + domain;
        this.setValue(nextValue);
    },
    setValue(nextValue) {
        this.value = nextValue;
        this.$refs.input.value = nextValue;
        this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
        this.update();
    },
    update() {
        if (!this.hasEmail) {
            return;
        }
        this.computeSuggestions();
    },
    computeSuggestions() {
        this.suggestionSuffix = '';
        this.suggestionDomain = '';
        this.correction = '';
        const value = (this.value || '').trim();
        const atIndex = value.indexOf('@');
        if (atIndex === -1) {
            return;
        }
        const domainInput = value.slice(atIndex + 1).toLowerCase();
        if (!domainInput) {
            return;
        }
        const correction = this.corrections[domainInput];
        if (correction && correction !== domainInput) {
            this.correction = correction;
        }

        const dotIndex = domainInput.indexOf('.');
        if (dotIndex !== -1) {
            const base = domainInput.slice(0, dotIndex);
            const tld = domainInput.slice(dotIndex + 1);
            const exact = `${base}.com`;
            if (tld !== 'com' && this.domains.includes(exact)) {
                this.correction = exact;
            }
        }

        const match = this.domains.find((domain) => domain.startsWith(domainInput));
        if (match && match !== domainInput) {
            this.suggestionDomain = match;
            this.suggestionSuffix = match.slice(domainInput.length);
        }
    }
}
ALPINE, $hasEmailAutocomplete ? 'true' : 'false');
    }
@endphp

<div class="space-y-1" @if ($hasInteractive) x-data="{!! $alpineData !!}" x-init="init($refs.input)" @endif>
    @if ($hasLabel && $labelPosition === 'above')
        <label for="{{ $inputId }}" class="text-sm font-medium text-zinc-800 dark:text-zinc-200">
            {{ $label }}
        </label>
    @endif

    @if ($hasLabel && $labelPosition === 'inset')
        <div class="relative">
            <label for="{{ $inputId }}" class="pointer-events-none absolute left-3 right-3 top-0 flex -translate-y-1/2 items-center gap-2 text-xs font-medium text-zinc-600 dark:text-zinc-300">
                <span class="bg-white px-1 dark:bg-zinc-900">{{ $label }}</span>
                <span class="h-px flex-1 bg-zinc-300 dark:bg-zinc-700"></span>
            </label>
            <input
                x-ref="input"
                id="{{ $inputId }}"
                name="{{ $name }}"
                type="{{ $type }}"
                @if ($hasReveal) x-bind:type="reveal ? 'text' : 'password'" @endif
                @if ($hasEmailAutocomplete) x-on:input="handleInput($event)" x-on:keydown.tab="handleTab($event)" @endif
                value="{{ $value }}"
                @if ($required) required @endif
                @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
                @if ($resolvedPlaceholder) placeholder="{{ $resolvedPlaceholder }}" @endif
                {{ $attributes->merge(['class' => $inputClasses]) }}
            />
            @if ($hasEmailAutocomplete)
                <div class="pointer-events-none absolute inset-y-0 left-0 right-0 flex items-center px-3 text-sm text-zinc-400/70">
                    <span class="invisible whitespace-pre" x-ref="mirror" x-text="value"></span>
                    <span class="whitespace-pre" x-show="suggestionSuffix" x-text="suggestionSuffix"></span>
                </div>
            @endif
            @if ($hasReveal)
                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white" x-on:click="reveal = !reveal" x-bind:aria-pressed="reveal">
                    <span class="sr-only">{{ __('Toggle password visibility') }}</span>
                    <svg x-show="!reveal" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <svg x-show="reveal" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M3 3l18 18"></path>
                        <path d="M10.6 10.6A2 2 0 0 0 9 12a3 3 0 0 0 5.9 1"></path>
                        <path d="M9.9 5a9.5 9.5 0 0 1 10.6 7 10.6 10.6 0 0 1-4.2 5.3"></path>
                        <path d="M6.2 6.2A10.6 10.6 0 0 0 2 12a10.6 10.6 0 0 0 4.2 5.3"></path>
                    </svg>
                </button>
            @endif
        </div>
    @elseif ($hasLabel && $labelPosition === 'inside')
        <div class="relative">
            <input
                x-ref="input"
                id="{{ $inputId }}"
                name="{{ $name }}"
                type="{{ $type }}"
                @if ($hasReveal) x-bind:type="reveal ? 'text' : 'password'" @endif
                @if ($hasEmailAutocomplete) x-on:input="handleInput($event)" x-on:keydown.tab="handleTab($event)" @endif
                value="{{ $value }}"
                @if ($resolvedPlaceholder) placeholder="{{ $resolvedPlaceholder }}" @endif
                @if ($required) required @endif
                @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
                {{ $attributes->merge(['class' => $inputClasses.' peer']) }}
            />
            <label for="{{ $inputId }}" class="pointer-events-none absolute left-3 top-2 text-xs font-medium text-zinc-500 dark:text-zinc-400">
                {{ $label }}
            </label>
            @if ($hasEmailAutocomplete)
                <div class="pointer-events-none absolute inset-y-0 left-0 right-0 flex items-center px-3 text-sm text-zinc-400/70">
                    <span class="invisible whitespace-pre" x-ref="mirror" x-text="value"></span>
                    <span class="whitespace-pre" x-show="suggestionSuffix" x-text="suggestionSuffix"></span>
                </div>
            @endif
            @if ($hasReveal)
                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white" x-on:click="reveal = !reveal" x-bind:aria-pressed="reveal">
                    <span class="sr-only">{{ __('Toggle password visibility') }}</span>
                    <svg x-show="!reveal" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <svg x-show="reveal" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M3 3l18 18"></path>
                        <path d="M10.6 10.6A2 2 0 0 0 9 12a3 3 0 0 0 5.9 1"></path>
                        <path d="M9.9 5a9.5 9.5 0 0 1 10.6 7 10.6 10.6 0 0 1-4.2 5.3"></path>
                        <path d="M6.2 6.2A10.6 10.6 0 0 0 2 12a10.6 10.6 0 0 0 4.2 5.3"></path>
                    </svg>
                </button>
            @endif
        </div>
    @elseif ($hasLabel && $labelPosition === 'floating')
        <div class="relative">
            <input
                x-ref="input"
                id="{{ $inputId }}"
                name="{{ $name }}"
                type="{{ $type }}"
                @if ($hasReveal) x-bind:type="reveal ? 'text' : 'password'" @endif
                @if ($hasEmailAutocomplete) x-on:input="handleInput($event)" x-on:keydown.tab="handleTab($event)" @endif
                value="{{ $value }}"
                placeholder=" "
                @if ($required) required @endif
                @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
                {{ $attributes->merge(['class' => $inputClasses.' peer']) }}
            />
            <label for="{{ $inputId }}" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-zinc-500 transition-all peer-focus:top-2 peer-focus:text-xs peer-focus:text-zinc-700 peer-[&:not(:placeholder-shown)]:top-2 peer-[&:not(:placeholder-shown)]:text-xs peer-[&:not(:placeholder-shown)]:text-zinc-700 dark:text-zinc-400 dark:peer-focus:text-zinc-200 dark:peer-[&:not(:placeholder-shown)]:text-zinc-200">
                {{ $label }}
            </label>
            @if ($hasEmailAutocomplete)
                <div class="pointer-events-none absolute inset-y-0 left-0 right-0 flex items-center px-3 text-sm text-zinc-400/70">
                    <span class="invisible whitespace-pre" x-ref="mirror" x-text="value"></span>
                    <span class="whitespace-pre" x-show="suggestionSuffix" x-text="suggestionSuffix"></span>
                </div>
            @endif
            @if ($hasReveal)
                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white" x-on:click="reveal = !reveal" x-bind:aria-pressed="reveal">
                    <span class="sr-only">{{ __('Toggle password visibility') }}</span>
                    <svg x-show="!reveal" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <svg x-show="reveal" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M3 3l18 18"></path>
                        <path d="M10.6 10.6A2 2 0 0 0 9 12a3 3 0 0 0 5.9 1"></path>
                        <path d="M9.9 5a9.5 9.5 0 0 1 10.6 7 10.6 10.6 0 0 1-4.2 5.3"></path>
                        <path d="M6.2 6.2A10.6 10.6 0 0 0 2 12a10.6 10.6 0 0 0 4.2 5.3"></path>
                    </svg>
                </button>
            @endif
        </div>
    @else
        @if ($hasLabel && $labelPosition === 'hidden')
            <label for="{{ $inputId }}" class="sr-only">
                {{ $label }}
            </label>
        @endif

        <div class="relative">
            <input
                x-ref="input"
                id="{{ $inputId }}"
                name="{{ $name }}"
                type="{{ $type }}"
                @if ($hasReveal) x-bind:type="reveal ? 'text' : 'password'" @endif
                @if ($hasEmailAutocomplete) x-on:input="handleInput($event)" x-on:keydown.tab="handleTab($event)" @endif
                value="{{ $value }}"
                @if ($required) required @endif
                @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
                @if ($resolvedPlaceholder) placeholder="{{ $resolvedPlaceholder }}" @endif
                {{ $attributes->merge(['class' => $inputClasses]) }}
            />
            @if ($hasEmailAutocomplete)
                <div class="pointer-events-none absolute inset-y-0 left-0 right-0 flex items-center px-3 text-sm text-zinc-400/70">
                    <span class="invisible whitespace-pre" x-ref="mirror" x-text="value"></span>
                    <span class="whitespace-pre" x-show="suggestionSuffix" x-text="suggestionSuffix"></span>
                </div>
            @endif
            @if ($hasReveal)
                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white" x-on:click="reveal = !reveal" x-bind:aria-pressed="reveal">
                    <span class="sr-only">{{ __('Toggle password visibility') }}</span>
                    <svg x-show="!reveal" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <svg x-show="reveal" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M3 3l18 18"></path>
                        <path d="M10.6 10.6A2 2 0 0 0 9 12a3 3 0 0 0 5.9 1"></path>
                        <path d="M9.9 5a9.5 9.5 0 0 1 10.6 7 10.6 10.6 0 0 1-4.2 5.3"></path>
                        <path d="M6.2 6.2A10.6 10.6 0 0 0 2 12a10.6 10.6 0 0 0 4.2 5.3"></path>
                    </svg>
                </button>
            @endif
        </div>
    @endif

    @if ($hasEmailAutocomplete)
        <div class="mt-2 text-right text-xs text-zinc-500 dark:text-zinc-400" x-show="correction">
            <button type="button" class="underline underline-offset-2 hover:text-zinc-900 dark:hover:text-white" x-on:click="applyCorrection(correction)">
                {{ __('Did you mean') }} <span x-text="correction"></span>?
            </button>
        </div>
    @endif

    @error($name)
        <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
