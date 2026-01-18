<?php

namespace CharlesHasse\Dau\Livewire;

use Livewire\Component;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    public function normalizeName(): void
    {
        $value = trim($this->name ?? '');

        if ($value === '') {
            return;
        }

        if ($value !== mb_strtoupper($value)) {
            return;
        }

        $lowered = mb_strtolower($value);
        $parts = preg_split('/(\s+)/u', $lowered, -1, PREG_SPLIT_DELIM_CAPTURE);
        $parts = array_map(function ($part) {
            if (trim($part) === '') {
                return $part;
            }

            return mb_strtoupper(mb_substr($part, 0, 1)).mb_substr($part, 1);
        }, $parts);
        $this->name = implode('', $parts);
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        // placeholder: replace with real registration logic
        session()->flash('dau_status', 'Registration submitted (placeholder)');
    }

    public function render()
    {
        $this->applyConfigOverrides();
        $theme = config('dau.layout.theme', 'default');

        return view(
            "dau::themes.$theme.pages.register",
            [
                'config_features' => config('dau.features'),
                'config_layout' => config('dau.layout'),
                'config_social' => config('dau.social'),
            ]
        )->layout(
            "dau::themes.$theme.layouts.base",
            [
                'config_layout' => config('dau.layout'),
                'config_features' => config('dau.features'),
                'config_social' => config('dau.social'),
            ]
        );
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
