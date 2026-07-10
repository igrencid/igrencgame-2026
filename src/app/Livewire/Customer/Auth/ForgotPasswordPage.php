<?php

namespace App\Livewire\Customer\Auth;

use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Component;
use Throwable;

class ForgotPasswordPage extends Component
{
    public string $email = '';

    public bool $linkSent = false;

    public function sendResetLink(): void
    {
        $this->resetErrorBag();

        $data = $this->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
        ]);

        $this->email = Str::lower(trim($data['email']));

        try {
            $status = Password::broker('customers')->sendResetLink([
                'email' => $this->email,
            ]);

            if ($status === PasswordBroker::RESET_THROTTLED) {
                $this->addError(
                    'email',
                    'Permintaan terlalu sering. Tunggu beberapa saat, lalu coba kembali.'
                );

                return;
            }

            /*
             * Pesan dibuat sama meskipun email tidak ditemukan.
             * Ini mencegah orang lain mengecek apakah suatu email terdaftar.
             */
            $this->linkSent = true;
        } catch (Throwable $exception) {
            report($exception);

            $this->addError(
                'email',
                'Tautan pengaturan ulang belum dapat dikirim. Silakan coba kembali.'
            );
        }
    }

    public function render(): View
    {
        return view('livewire.customer.auth.forgot-password-page')
            ->layout('layouts.public', [
                'title' => 'Lupa Kata Sandi',
            ]);
    }
}
