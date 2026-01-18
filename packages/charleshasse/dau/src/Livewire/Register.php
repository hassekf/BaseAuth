<?php

namespace CharlesHasse\Dau\Livewire;

use Livewire\Component;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

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
        $theme = config('dau.layout.theme', 'default');

        return view("dau::themes.$theme.pages.register")
            ->layout("dau::themes.$theme.layouts.base");
    }
}
