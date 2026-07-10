<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Actions\Action;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function getTitle(): string | Htmlable
    {
        return 'Masuk Admin ' . config('brand.name', 'Igrenc');
    }

    public function getHeading(): string | Htmlable
    {
        return 'Masuk ke Admin ' . config('brand.name', 'Igrenc');
    }

    public function getSubheading(): string | Htmlable
    {
        return 'Kelola katalog game, pesanan, pembayaran, voucher, dan pengaturan layanan dari satu tempat.';
    }

    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()
            ->label('Masuk ke Dasbor');
    }
}
