@props([
    'title' => null,
])

@php
    $siteSetting = class_exists(\App\Models\SiteSetting::class)
        ? \App\Models\SiteSetting::current()
        : null;

    $siteName = $siteSetting?->site_name ?: config('app.name', 'Igrenc');
    $siteTagline = $siteSetting?->tagline ?: 'Fast Game Top Up';
    $siteDescription = $siteSetting?->seo_description ?: 'Platform top up game online cepat, aman, dan mudah digunakan.';
    $siteLogoUrl = $siteSetting?->logo_url;
    $siteFaviconUrl = $siteSetting?->favicon_url;

    $whatsappNumber = $siteSetting?->customer_service_whatsapp;
    $csEmail = $siteSetting?->customer_service_email;
    $csHours = $siteSetting?->customer_service_working_hours;

    $waMessage = urlencode('Halo admin Igrenc, saya butuh bantuan terkait pesanan.');
    $waUrl = $whatsappNumber ? "https://wa.me/{$whatsappNumber}?text={$waMessage}" : null;
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title . ' - ' . $siteName : $siteName }}</title>

    <meta name="description" content="{{ $siteDescription }}">

    @if ($siteFaviconUrl)
        <link rel="icon" href="{{ $siteFaviconUrl }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @livewireStyles
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-950 antialiased">
    <div class="min-h-screen overflow-hidden">
        <header
            x-data="{ open: false }"
            class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl"
        >
            <div class="mx-auto grid h-16 max-w-7xl grid-cols-2 items-center px-4 sm:px-6 md:grid-cols-[1fr_auto_1fr] lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3 justify-self-start">
                    <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-2xl bg-white shadow-lg shadow-indigo-600/10 ring-1 ring-slate-200">
                        @if ($siteLogoUrl)
                            <img
                                src="{{ $siteLogoUrl }}"
                                alt="{{ $siteName }}"
                                class="h-full w-full object-contain p-1.5"
                            >
                        @else
                            <svg class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M8 13H6m1-1v2m9-1h.01M18 15h.01M7.2 18h9.6c2.2 0 4-1.8 4-4v-2c0-2.2-1.8-4-4-4H7.2c-2.2 0-4 1.8-4 4v2c0 2.2 1.8 4 4 4Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />
                            </svg>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm font-extrabold leading-none text-indigo-600 sm:text-base">
                            {{ $siteName }}
                        </p>

                        <p class="hidden text-[11px] font-semibold text-slate-500 sm:block">
                            {{ $siteTagline }}
                        </p>
                    </div>
                </a>

                <nav class="hidden items-center gap-8 md:flex">
                    <a href="{{ route('home') }}" class="text-sm font-bold text-slate-700 transition hover:text-indigo-600">
                        Beranda
                    </a>

                    <a href="{{ route('home') }}#games" class="text-sm font-bold text-slate-700 transition hover:text-indigo-600">
                        Top Up Game
                    </a>

                    <a href="{{ route('home') }}#cek-pesanan" class="text-sm font-bold text-slate-700 transition hover:text-indigo-600">
                        Cek Pesanan
                    </a>
                </nav>

                <div class="hidden items-center justify-end gap-3 md:flex">
                    @guest('customer')
                        <a
                            href="{{ route('customer.login') }}"
                            class="rounded-2xl px-4 py-2 text-sm font-bold text-slate-700 transition hover:text-indigo-600"
                        >
                            Masuk
                        </a>

                        <a
                            href="{{ route('customer.register') }}"
                            class="rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            Daftar
                        </a>
                    @else
                        <a
                            href="{{ route('customer.account') }}"
                            class="rounded-2xl px-4 py-2 text-sm font-bold text-slate-700 transition hover:text-indigo-600"
                        >
                            Akun Saya
                        </a>

                        <a
                            href="{{ route('customer.orders') }}"
                            class="rounded-2xl px-4 py-2 text-sm font-bold text-slate-700 transition hover:text-indigo-600"
                        >
                            Riwayat
                        </a>

                        <form method="POST" action="{{ route('customer.logout') }}">
                            @csrf

                            <button
                                type="submit"
                                class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 transition hover:bg-slate-100"
                            >
                                Keluar
                            </button>
                        </form>
                    @endguest
                </div>

                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center justify-self-end rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-indigo-200 hover:text-indigo-600 md:hidden"
                    @click="open = ! open"
                    aria-label="Toggle menu"
                >
                    <svg x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>

                    <svg x-show="open" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <div
                x-show="open"
                x-cloak
                class="border-t border-slate-200 bg-white px-4 py-4 md:hidden"
            >
                <div class="mx-auto flex max-w-7xl flex-col gap-2">
                    <a href="{{ route('home') }}" class="rounded-2xl px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-100 hover:text-indigo-600">
                        Beranda
                    </a>

                    <a href="{{ route('home') }}#games" class="rounded-2xl px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-100 hover:text-indigo-600">
                        Top Up Game
                    </a>

                    <a href="{{ route('home') }}#cek-pesanan" class="rounded-2xl px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-100 hover:text-indigo-600">
                        Cek Pesanan
                    </a>

                    <div class="my-2 border-t border-slate-200"></div>

                    @guest('customer')
                        <a href="{{ route('customer.login') }}" class="rounded-2xl px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-100 hover:text-indigo-600">
                            Masuk
                        </a>

                        <a href="{{ route('customer.register') }}" class="rounded-2xl bg-indigo-600 px-3 py-2.5 text-center text-sm font-bold text-white transition hover:bg-indigo-700">
                            Daftar
                        </a>
                    @else
                        <a href="{{ route('customer.account') }}" class="rounded-2xl px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-100 hover:text-indigo-600">
                            Akun Saya
                        </a>

                        <a href="{{ route('customer.orders') }}" class="rounded-2xl px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-100 hover:text-indigo-600">
                            Riwayat Pesanan
                        </a>

                        <form method="POST" action="{{ route('customer.logout') }}">
                            @csrf

                            <button
                                type="submit"
                                class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-left text-sm font-bold text-slate-700 transition hover:bg-slate-100"
                            >
                                Keluar
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </header>

        <main>
            {{ $slot }}
        </main>

        <footer id="bantuan" class="bg-slate-950 text-white">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-2xl bg-white ring-1 ring-white/10">
                            @if ($siteLogoUrl)
                                <img
                                    src="{{ $siteLogoUrl }}"
                                    alt="{{ $siteName }}"
                                    class="h-full w-full object-contain p-1.5"
                                >
                            @else
                                <svg class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M8 13H6m1-1v2m9-1h.01M18 15h.01M7.2 18h9.6c2.2 0 4-1.8 4-4v-2c0-2.2-1.8-4-4-4H7.2c-2.2 0-4 1.8-4 4v2c0 2.2 1.8 4 4 4Z"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                    />
                                </svg>
                            @endif
                        </div>

                        <div>
                            <p class="font-extrabold">
                                {{ $siteName }}
                            </p>

                            <p class="text-xs font-medium text-slate-400">
                                {{ $siteTagline }}
                            </p>
                        </div>
                    </div>

                    <p class="mt-4 text-sm leading-6 text-slate-400">
                        Platform top up game online yang tepercaya, cepat, dan aman untuk kebutuhan game favorit Anda.
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-extrabold">Produk</h3>

                    <div class="mt-4 space-y-3 text-sm text-slate-400">
                        <a href="{{ route('home') }}#games" class="block transition hover:text-white">
                            Lihat Semua Game
                        </a>

                        <a href="{{ route('home') }}#cek-pesanan" class="block transition hover:text-white">
                            Cek Pesanan
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-extrabold">Perlu Bantuan?</h3>

                    <div class="mt-4 space-y-3 text-sm text-slate-400">
                        @if ($waUrl)
                            <a
                                href="{{ $waUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex rounded-full border border-slate-700 px-3 py-1.5 text-slate-300 transition hover:border-indigo-400 hover:text-white"
                            >
                                Hubungi Kami
                            </a>
                        @endif

                        <a href="{{ route('faq.index') }}" class="block transition hover:text-white">
                            Pertanyaan Umum
                        </a>

                        <a href="{{ route('terms.show') }}" class="block transition hover:text-white">
                            Syarat dan Ketentuan
                        </a>

                        <a href="{{ route('privacy.show') }}" class="block transition hover:text-white">
                            Kebijakan Privasi
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-extrabold">Layanan Pelanggan</h3>

                    <div class="mt-4 space-y-3 text-sm leading-6 text-slate-400">
                        @if ($whatsappNumber || $csEmail || $csHours)
                            @if ($whatsappNumber)
                                <div>
                                    <span class="block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        WhatsApp
                                    </span>

                                    <a
                                        href="{{ $waUrl }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="font-semibold text-slate-300 transition hover:text-white"
                                    >
                                        {{ $whatsappNumber }}
                                    </a>
                                </div>
                            @endif

                            @if ($csEmail)
                                <div>
                                    <span class="block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Email
                                    </span>

                                    <a
                                        href="mailto:{{ $csEmail }}"
                                        class="font-semibold text-slate-300 transition hover:text-white"
                                    >
                                        {{ $csEmail }}
                                    </a>
                                </div>
                            @endif

                            @if ($csHours)
                                <div>
                                    <span class="block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Jam Operasional
                                    </span>

                                    <span class="font-semibold text-slate-300">
                                        {{ $csHours }}
                                    </span>
                                </div>
                            @endif
                        @else
                            <p>Data layanan pelanggan belum tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="border-t border-white/10 px-4 py-5 text-center text-xs text-slate-500">
                © {{ date('Y') }} {{ $siteName }}. Semua hak dilindungi.
            </div>
        </footer>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
