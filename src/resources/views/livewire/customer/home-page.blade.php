<div class="bg-white text-slate-950">
    {{-- INTRO SCREEN --}}
    <x-intro-screen />

    {{-- HERO --}}
<section class="relative overflow-hidden border-b border-slate-200 bg-slate-50">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute -left-40 -top-40 h-[440px] w-[440px] rounded-full bg-indigo-200/35 blur-3xl"></div>
        <div class="absolute -right-40 top-20 h-[440px] w-[440px] rounded-full bg-violet-200/25 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24">
        <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">

            {{-- HERO CONTENT --}}
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-white px-4 py-2 shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                        Top Up Game Cepat dan Aman
                    </span>
                </div>

                <h1 class="mt-6 text-4xl font-black leading-[1.08] tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                    Top up game favorit Anda,
                    <span class="block bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-500 bg-clip-text text-transparent">
                        lebih cepat dan praktis.
                    </span>
                </h1>

                <p class="mt-6 max-w-xl text-base leading-7 text-slate-600 sm:text-lg sm:leading-8">
                    Pilih game, tentukan produk, lalu selesaikan pembayaran.
                    Pesanan dapat dipantau dengan mudah melalui sistem Igrenc.
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    <a
                        href="{{ route('topup.index') }}"
                        wire:navigate
                        class="inline-flex min-h-12 items-center justify-center rounded-xl bg-indigo-600 px-7 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-600/20 transition hover:-translate-y-0.5 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                    >
                        Top Up Sekarang
                    </a>

                    <a
                        href="{{ route('orders.lookup') }}"
                        wire:navigate
                        class="inline-flex min-h-12 items-center justify-center rounded-xl border border-slate-300 bg-white px-7 py-3 text-sm font-bold text-slate-700 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                    >
                        Cek Pesanan
                    </a>

                    <a
                        href="{{ route('promo.index') }}"
                        wire:navigate
                        class="inline-flex min-h-12 items-center justify-center rounded-xl border border-indigo-200 bg-indigo-50 px-7 py-3 text-sm font-bold text-indigo-700 transition hover:border-indigo-300 hover:bg-indigo-100 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                    >
                        Lihat Promo
                    </a>
                </div>
            </div>

            {{-- HERO SLIDER --}}
            <x-hero-slider />

        </div>
    </div>
</section>

{{-- KATALOG GAME --}}
    <section id="games" class="bg-white py-14 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-6 border-b border-slate-200 pb-8 sm:flex-row sm:items-end sm:justify-between">
                <div class="max-w-2xl">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-indigo-600">
                        Katalog Game
                    </p>

                    <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">
                        Pilih game yang ingin Anda top up
                    </h2>

                    <p class="mt-3 text-base leading-7 text-slate-600">
                        Temukan game favorit Anda dan lanjutkan ke pilihan produk
                        yang tersedia.
                    </p>
                </div>

                <a
                    href="{{ route('topup.index') }}"
                    wire:navigate
                    class="inline-flex min-h-11 shrink-0 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                >
                    Lihat Semua Game
                </a>
            </div>

            @if ($games->isNotEmpty())
                <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                    @foreach ($games as $game)
                        <a
                            href="{{ route('topup.index', ['game' => $game->slug]) }}"
                            wire:navigate
                            wire:key="home-game-{{ $game->id }}"
                            class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition duration-200 hover:-translate-y-1 hover:border-indigo-300 hover:shadow-xl hover:shadow-slate-950/10 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                        >
                            <div class="relative aspect-[4/5] overflow-hidden bg-slate-100">
                                @if ($game->image_path)
                                    <img
                                        src="{{ asset('storage/' . $game->image_path) }}"
                                        alt="{{ $game->name }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="flex h-full items-center justify-center bg-gradient-to-br from-slate-100 to-indigo-50 px-6 text-center">
                                        <span class="text-sm font-bold text-slate-500">
                                            {{ $game->name }}
                                        </span>
                                    </div>
                                @endif

                                @if ($game->badge)
                                    <span class="absolute left-3 top-3 rounded-lg bg-slate-950/85 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white backdrop-blur">
                                        {{ $game->badge }}
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-1 flex-col p-4">
                                <div class="flex-1">
                                    <h3 class="line-clamp-2 text-sm font-bold leading-5 text-slate-950 sm:text-base">
                                        {{ $game->name }}
                                    </h3>

                                    <p class="mt-1.5 line-clamp-1 text-xs font-medium text-slate-500">
                                        {{ data_get($game, 'category.name') ?? data_get($game, 'category') ?? 'Top Up Game' }}
                                    </p>
                                </div>

                                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                                    <span class="text-xs font-medium text-slate-500">
                                        Tersedia
                                    </span>

                                    <span class="text-xs font-bold text-indigo-600">
                                        Pilih Game
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="mt-8 rounded-3xl border border-slate-200 bg-slate-50 px-6 py-12 sm:px-10">
                    <div class="mx-auto max-w-lg text-center">
                        <p class="text-lg font-bold text-slate-950">
                            Game belum tersedia
                        </p>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Daftar game sedang kami siapkan. Silakan periksa kembali
                            dalam beberapa waktu.
                        </p>

                        <a
                            href="{{ route('topup.index') }}"
                            wire:navigate
                            class="mt-6 inline-flex min-h-11 items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                        >
                            Buka Halaman Top Up
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- PROMO SETELAH KATALOG --}}
    <livewire:promo-section />

    {{-- CEK PESANAN --}}
    <section id="cek-pesanan" class="bg-white py-14 sm:py-20">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 px-6 py-10 sm:px-10">
                <div class="flex flex-col gap-8 md:flex-row md:items-center md:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-indigo-600">
                            Cek Pesanan
                        </p>

                        <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950">
                            Pantau status transaksi Anda
                        </h2>

                        <p class="mt-3 text-base leading-7 text-slate-600">
                            Gunakan nomor invoice untuk melihat status pembayaran
                            dan proses pesanan.
                        </p>
                    </div>

                    <a
                        href="{{ route('orders.lookup') }}"
                        wire:navigate
                        class="inline-flex min-h-12 shrink-0 items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-600/20 transition hover:-translate-y-0.5 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                    >
                        Cek Pesanan
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
