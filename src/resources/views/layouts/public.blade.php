@props([
    'title' => null,
])

@php
    $siteSetting = class_exists(\App\Models\SiteSetting::class)
        ? \App\Models\SiteSetting::current()
        : null;

    $siteName = $siteSetting?->site_name ?: config('app.name', 'IgrencGame');
    $siteTagline = $siteSetting?->tagline ?: 'Fast Game Top Up';
    $siteDescription = $siteSetting?->seo_description ?: 'Platform top up game online cepat, aman, dan mudah digunakan.';
    $siteLogoUrl = $siteSetting?->logo_url;
    $siteFaviconUrl = $siteSetting?->favicon_url;
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
                        Home
                    </a>

                    <a href="{{ route('home') }}#games" class="text-sm font-bold text-slate-700 transition hover:text-indigo-600">
                        Games
                    </a>

                    <a href="{{ route('home') }}#cek-pesanan" class="text-sm font-bold text-slate-700 transition hover:text-indigo-600">
                        Cek Pesanan
                    </a>

                    <a href="{{ route('home') }}#bantuan" class="text-sm font-bold text-slate-700 transition hover:text-indigo-600">
                        Bantuan
                    </a>
                </nav>

                <div class="hidden md:block"></div>

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
                        Home
                    </a>

                    <a href="{{ route('home') }}#games" class="rounded-2xl px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-100 hover:text-indigo-600">
                        Games
                    </a>

                    <a href="{{ route('home') }}#cek-pesanan" class="rounded-2xl px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-100 hover:text-indigo-600">
                        Cek Pesanan
                    </a>

                    <a href="{{ route('home') }}#bantuan" class="rounded-2xl px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-100 hover:text-indigo-600">
                        Bantuan
                    </a>
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
                        {{ $siteDescription }}
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-extrabold">Menu</h3>

                    <div class="mt-4 space-y-3 text-sm text-slate-400">
                        <a href="{{ route('home') }}" class="block transition hover:text-white">
                            Home
                        </a>

                        <a href="{{ route('home') }}#games" class="block transition hover:text-white">
                            Games
                        </a>

                        <a href="{{ route('home') }}#cek-pesanan" class="block transition hover:text-white">
                            Cek Pesanan
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-extrabold">Layanan</h3>

                    <div class="mt-4 space-y-3 text-sm text-slate-400">
                        <p>Top Up Game</p>
                        <p>Pembayaran Online</p>
                        <p>Riwayat Pesanan</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-extrabold">Bantuan</h3>

                    <div class="mt-4 space-y-3 text-sm leading-6 text-slate-400">
                        <p>Gunakan fitur cek pesanan untuk melihat status transaksi.</p>
                        <p>Data layanan, harga, dan metode pembayaran dikelola melalui sistem internal.</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/10 px-4 py-5 text-center text-xs text-slate-500">
                © {{ date('Y') }} {{ $siteName }}. All rights reserved.
            </div>
        </footer>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>