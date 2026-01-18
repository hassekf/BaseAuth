<?php

namespace CharlesHasse\Dau\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
            throw ValidationException::withMessages([
                'email' => __('Credenciais inválidas'),
            ]);
        }

        session()->regenerate();

        return redirect()->intended('/');
    }

    public function render()
    {
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
}
