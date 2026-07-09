<?php

namespace App\Livewire\Customer\Auth;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class RegisterPage extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $accepts_marketing = false;

    public function register()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:customers,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'accepts_marketing' => ['boolean'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $customer = Customer::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?: null,
            'password' => $data['password'],
            'accepts_marketing' => $data['accepts_marketing'] ?? false,
        ]);

        Auth::guard('customer')->login($customer);

        session()->regenerate();

        return redirect()->intended($this->redirectAfterRegister());
    }

    private function redirectAfterRegister(): string
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
        return view('livewire.customer.auth.register-page')
            ->layout('layouts.public', [
                'title' => 'Daftar Akun Pelanggan',
            ]);
    }
}
