<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $token,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('customer.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        $expireMinutes = (int) config('auth.passwords.customers.expire', 60);

        return (new MailMessage)
            ->subject('Atur Ulang Kata Sandi IgrencGame')
            ->greeting('Halo, ' . ($notifiable->name ?: 'Pelanggan') . '.')
            ->line('Kami menerima permintaan untuk mengatur ulang kata sandi akun IgrencGame Anda.')
            ->action('Atur Ulang Kata Sandi', $url)
            ->line("Tautan ini berlaku selama {$expireMinutes} menit.")
            ->line('Abaikan pesan ini apabila Anda tidak meminta pengaturan ulang kata sandi.')
            ->salutation('Salam, IgrencGame');
    }
}
