<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        if (! $this->googleIsConfigured()) {
            return redirect()
                ->route('customer.login')
                ->with('error', 'Login Google belum dikonfigurasi.');
        }

        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function callback(Request $request)
    {
        if (! $this->googleIsConfigured()) {
            return redirect()
                ->route('customer.login')
                ->with('error', 'Login Google belum dikonfigurasi.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('customer.login')
                ->with('error', 'Gagal masuk dengan Google. Silakan coba lagi.');
        }

        $email = $googleUser->getEmail();

        if (! $email) {
            return redirect()
                ->route('customer.login')
                ->with('error', 'Akun Google tidak mengembalikan alamat email.');
        }

        $rawUser = $googleUser->getRaw();
        $emailVerified = (bool) data_get($rawUser, 'email_verified', true);

        $customer = Customer::query()
            ->where('google_id', $googleUser->getId())
            ->first();

        if (! $customer) {
            $customer = Customer::query()
                ->where('email', $email)
                ->first();
        }

        if ($customer) {
            $customer->forceFill([
                'google_id' => $customer->google_id ?: $googleUser->getId(),
                'name' => $customer->name ?: ($googleUser->getName() ?: Str::before($email, '@')),
                'avatar_url' => $googleUser->getAvatar(),
                'email_verified_at' => $customer->email_verified_at ?: ($emailVerified ? now() : null),
            ])->save();
        } else {
            $customer = Customer::query()->create([
                'name' => $googleUser->getName() ?: Str::before($email, '@'),
                'email' => $email,
                'phone' => null,
                'password' => Str::random(64),
                'google_id' => $googleUser->getId(),
                'avatar_url' => $googleUser->getAvatar(),
                'email_verified_at' => $emailVerified ? now() : null,
                'accepts_marketing' => false,
            ]);
        }

        Auth::guard('customer')->login($customer, remember: true);

        $request->session()->regenerate();

        return redirect()->intended($this->defaultRedirectUrl());
    }

    private function googleIsConfigured(): bool
    {
        return filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'))
            && filled(config('services.google.redirect'));
    }

    private function defaultRedirectUrl(): string
    {
        if (RouteFacade::has('customer.orders')) {
            return route('customer.orders');
        }

        if (RouteFacade::has('customer.account')) {
            return route('customer.account');
        }

        if (RouteFacade::has('home')) {
            return route('home');
        }

        return url('/');
    }
}
