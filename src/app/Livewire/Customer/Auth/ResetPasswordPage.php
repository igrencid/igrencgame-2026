<?php

namespace App\Livewire\Customer\Auth;

use App\Models\Customer;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Component;
use Throwable;

class ResetPasswordPage extends Component
{
    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = Str::lower(trim((string) request()->query('email', '')));
    }

    public function resetPassword()
    {
        $this->resetErrorBag();

        $data = $this->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)->letters()->mixedCase()->numbers(),
            ],
        ], [
            'token.required' => 'Token pengaturan ulang tidak ditemukan.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
        ]);

        try {
            $status = Password::broker('customers')->reset(
                [
                    'email' => Str::lower(trim($data['email'])),
                    'password' => $data['password'],
                    'password_confirmation' => $data['password_confirmation'],
                    'token' => $data['token'],
                ],
                function (Customer $customer, string $password): void {
                    $customer->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($customer));
                }
            );

            if ($status !== PasswordBroker::PASSWORD_RESET) {
                $this->addError(
                    'email',
                    match ($status) {
                        PasswordBroker::INVALID_TOKEN => 'Tautan tidak valid atau sudah kedaluwarsa.',
                        PasswordBroker::INVALID_USER => 'Akun dengan alamat email tersebut tidak ditemukan.',
                        default => 'Kata sandi belum dapat diperbarui.',
                    }
                );

                return null;
            }

            session()->flash(
                'status',
                'Kata sandi berhasil diperbarui. Silakan masuk menggunakan kata sandi baru.'
            );

            return redirect()->route('customer.login');
        } catch (Throwable $exception) {
            report($exception);

            $this->addError(
                'email',
                'Terjadi kendala ketika memperbarui kata sandi.'
            );
        }

        return null;
    }

    public function render(): View
    {
        return view('livewire.customer.auth.reset-password-page')
            ->layout('layouts.public', [
                'title' => 'Atur Ulang Kata Sandi',
            ]);
    }
}
