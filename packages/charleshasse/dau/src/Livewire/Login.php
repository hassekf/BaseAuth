<?php

namespace CharlesHasse\Dau\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Flux\Flux;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules()
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function login()
    {
        $this->validate();

        if (! Auth::attempt(
            ['email' => $this->email, 'password' => $this->password],
            $this->remember
        )) {
            // throw ValidationException::withMessages([
            //     'email' => __('Credenciais inválidas'),
            // ]);

            Flux::toast(
                heading: 'Login Failed',
                text: 'Invalid credentials. Please try again.',
                variant: 'danger',
            );

        } else {
            session()->regenerate();
    
            return redirect()->intended('/');

        }

    }

    public function render()
    {
        $this->applyConfigOverrides();
        $theme = config('dau.layout.theme', 'default');

        return view("dau::themes.$theme.pages.login",
        [
            'config_features' => config('dau.features'),
            'config_layout' => config('dau.layout'),
            'config_social' => config('dau.social')
        ]
        )
            ->layout("dau::themes.$theme.layouts.base",
        [
            'config_layout' => config('dau.layout'),
            'config_features' => config('dau.features'),
            'config_social' => config('dau.social')
        ]);
    }

    private function applyConfigOverrides(): void
    {
        $query = request()->query();
        $overrides = session('dau_overrides', []);

        if ($query !== []) {
            $theme = $query['theme'] ?? null;
            $labels = $query['labels'] ?? null;

            if (in_array($theme, ['default', 'side_slider', 'side_full'], true)) {
                $overrides['theme'] = $theme;
            }

            if (in_array($labels, ['above', 'inside', 'inset', 'floating', 'hidden'], true)) {
                $overrides['labels'] = $labels;
            }

            $booleanKeys = [
                'social_google',
                'social_apple',
                'login_two_steps',
                'password_reveal',
                'autocomplete_email',
                'mode_toggle',
                'feature_register',
                'remember_me',
            ];

            foreach ($booleanKeys as $key) {
                if (! array_key_exists($key, $query)) {
                    continue;
                }
                $overrides[$key] = filter_var($query[$key], FILTER_VALIDATE_BOOLEAN);
            }

            session(['dau_overrides' => $overrides]);
        }

        if (isset($overrides['theme'])) {
            config(['dau.layout.theme' => $overrides['theme']]);
        }

        if (isset($overrides['labels'])) {
            config(['dau.layout.labels_position' => $overrides['labels']]);
        }

        $booleanMap = [
            'social_google' => 'dau.social.google',
            'social_apple' => 'dau.social.apple',
            'login_two_steps' => 'dau.features.login_two_steps',
            'password_reveal' => 'dau.features.password_reveal',
            'autocomplete_email' => 'dau.features.autocomplete_email',
            'mode_toggle' => 'dau.layout.mode_toggle',
            'feature_register' => 'dau.features.register',
            'remember_me' => 'dau.features.login_remember_me',
        ];

        foreach ($booleanMap as $key => $path) {
            if (! array_key_exists($key, $overrides)) {
                continue;
            }
            config([$path => $overrides[$key]]);
        }
    }
}
