<?php

namespace App\Livewire\Customer\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class LoginPage extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $this->resetErrorBag();

        $this->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (! Auth::guard('customer')->attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            $this->addError('auth', 'Email atau kata sandi tidak sesuai.');

            return null;
        }

        session()->regenerate();

        return redirect()->intended($this->redirectAfterLogin());
    }

    private function redirectAfterLogin(): string
    {
        if (Route::has('customer.orders')) {
            return route('customer.orders');
        }

        if (Route::has('customer.account')) {
            return route('customer.account');
        }

        if (Route::has('home')) {
            return route('home');
        }

        return url('/');
    }

    public function render(): View
    {
        return view('livewire.customer.auth.login-page')
            ->layout('layouts.public', [
                'title' => 'Masuk Akun Pelanggan',
            ]);
    }
}
