<?php

use App\Http\Controllers\Customer\Auth\GoogleAuthController;
use App\Http\Controllers\Customer\MidtransFinishController;
use App\Http\Controllers\Customer\MidtransNotificationController;
use App\Livewire\Customer\PromoPage;
use App\Livewire\Customer\Account\DashboardPage;
use App\Livewire\Customer\Account\OrdersPage;
use App\Livewire\Customer\Auth\ForgotPasswordPage;
use App\Livewire\Customer\Auth\LoginPage;
use App\Livewire\Customer\Auth\RegisterPage;
use App\Livewire\Customer\Auth\ResetPasswordPage;
use App\Livewire\Customer\CheckoutPage;
use App\Livewire\Customer\ContentPageShow;
use App\Livewire\Customer\FaqPage;
use App\Livewire\Customer\GameDetailPage;
use App\Livewire\Customer\HomePage;
use App\Livewire\Customer\OrderLookupPage;
use App\Livewire\Customer\OrderStatusPage;
use App\Livewire\Customer\PaymentPage;
use App\Livewire\Customer\TopUpPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Rute Aset Livewire
|--------------------------------------------------------------------------
| Digunakan apabila aplikasi berjalan di dalam subdirektori domain.
*/
Livewire::setUpdateRoute(function ($handle) {
    return Route::post(
        config('app.asset_prefix') . '/livewire/update',
        $handle
    );
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(
        config('app.asset_prefix') . '/livewire/livewire.js',
        $handle
    );
});

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/
Route::get('/', HomePage::class)->name('home');

Route::get('/games/{slug}', GameDetailPage::class)
    ->name('games.show');

Route::get('/checkout/{slug}', CheckoutPage::class)
    ->name('checkout.show');

Route::get('/top-up', TopUpPage::class)
    ->name('topup.index');

Route::get('/cek-pesanan', OrderLookupPage::class)
    ->name('orders.lookup');

/*
|--------------------------------------------------------------------------
| Halaman Informasi
|--------------------------------------------------------------------------
*/
Route::get('/bantuan', FaqPage::class)
    ->name('faq.index');

Route::redirect('/faq', '/bantuan');

Route::get('/terms', ContentPageShow::class)
    ->defaults('slug', 'terms')
    ->name('terms.show');

Route::get('/privacy', ContentPageShow::class)
    ->defaults('slug', 'privacy')
    ->name('privacy.show');

/*
|--------------------------------------------------------------------------
| Pembayaran dan Pesanan
|--------------------------------------------------------------------------
| Rute finish harus berada di atas /payment/{invoice} agar kata "finish"
| tidak dianggap sebagai nomor invoice.
*/
Route::get('/payment/finish', MidtransFinishController::class)
    ->name('payment.finish');

Route::get('/payment/{invoice}', PaymentPage::class)
    ->name('payment.show');

Route::get('/orders/{invoice}', OrderStatusPage::class)
    ->name('orders.show');

Route::post('/midtrans/notification', MidtransNotificationController::class)
    ->name('midtrans.notification');

/*
|--------------------------------------------------------------------------
| Autentikasi Pelanggan
|--------------------------------------------------------------------------
*/
Route::middleware('guest:customer')->group(function () {
    Route::get('/register', RegisterPage::class)
        ->name('customer.register');

    Route::get('/login', LoginPage::class)
        ->name('customer.login');

    Route::get('/lupa-kata-sandi', ForgotPasswordPage::class)
        ->name('customer.password.request');

    Route::get(
        '/atur-ulang-kata-sandi/{token}',
        ResetPasswordPage::class
    )->name('customer.password.reset');

    Route::get('/auth/google/redirect', [
        GoogleAuthController::class,
        'redirect',
    ])->name('customer.google.redirect');

    Route::get('/auth/google/callback', [
        GoogleAuthController::class,
        'callback',
    ])->name('customer.google.callback');
});

/*
|--------------------------------------------------------------------------
| Akun Pelanggan
|--------------------------------------------------------------------------
*/
Route::middleware('customer.auth')->group(function () {
    Route::get('/account', DashboardPage::class)
        ->name('customer.account');

    Route::get('/account/orders', OrdersPage::class)
        ->name('customer.orders');

    Route::post('/logout', function () {
        Auth::guard('customer')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('home');
    })->name('customer.logout');
});

Route::get('/promo', PromoPage::class)
    ->name('promo.index');