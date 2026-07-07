<?php

namespace App\Livewire\Customer\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

class LoginPage extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $ok = Auth::guard('customer')->attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember);

        if (! $ok) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        request()->session()->regenerate();

        return redirect()->intended(route('customer.account'));
    }

    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.customer.auth.login-page');
    }
}
