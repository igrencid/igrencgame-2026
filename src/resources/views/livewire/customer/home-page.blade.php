<div>
    <section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-slate-50">
        <div class="absolute inset-0 -z-10">
            <div class="absolute left-1/2 top-0 h-72 w-72 -translate-x-1/2 rounded-full bg-indigo-300/30 blur-3xl"></div>
            <div class="absolute right-0 top-20 h-72 w-72 rounded-full bg-violet-300/30 blur-3xl"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
            <div class="grid items-center gap-10 lg:grid-cols-2">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-white px-4 py-2 text-xs font-bold text-indigo-600 shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Top Up Game Cepat dan Aman
                    </div>

                    <h1 class="mt-6 max-w-2xl text-4xl font-extrabold tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                        Top up game favorit lu dengan proses yang simpel.
                    </h1>

                    <p class="mt-5 max-w-xl text-base leading-8 text-slate-600 sm:text-lg">
                        Pilih game, masukkan data akun, pilih nominal, lalu bayar melalui metode pembayaran yang tersedia.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a
                            href="#games"
                            class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-600/20 transition hover:-translate-y-0.5 hover:bg-indigo-700"
                        >
                            Mulai Top Up
                        </a>

                        <a
                            href="#cek-pesanan"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-700 transition hover:-translate-y-0.5 hover:border-indigo-200 hover:text-indigo-600"
                        >
                            Cek Pesanan
                        </a>
                    </div>
                </div>

                <div class="relative">
                    <div class="rounded-[2rem] border border-slate-200 bg-white p-4 shadow-2xl shadow-indigo-950/10">
                        <div class="rounded-[1.5rem] bg-slate-950 p-5 text-white">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs font-semibold text-slate-400">
                                        Dashboard Layanan
                                    </p>

                                    <p class="mt-1 text-xl font-extrabold">
                                        Sistem IgrencGame
                                    </p>
                                </div>

                                <div class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-300">
                                    Aktif
                                </div>
                            </div>

                            <div class="mt-6 grid grid-cols-2 gap-3">
                                <div class="rounded-2xl bg-white/10 p-4">
                                    <p class="text-xs font-semibold text-slate-400">
                                        Game Aktif
                                    </p>

                                    <p class="mt-2 text-2xl font-extrabold text-white">
                                        {{ number_format($stats['active_games']) }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-indigo-500 p-4">
                                    <p class="text-xs font-semibold text-indigo-100">
                                        Nominal
                                    </p>

                                    <p class="mt-2 text-2xl font-extrabold text-white">
                                        {{ number_format($stats['active_products']) }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white/10 p-4">
                                    <p class="text-xs font-semibold text-slate-400">
                                        Pembayaran
                                    </p>

                                    <p class="mt-2 text-2xl font-extrabold text-white">
                                        {{ number_format($stats['active_payment_gateways']) }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white/10 p-4">
                                    <p class="text-xs font-semibold text-slate-400">
                                        Diproses
                                    </p>

                                    <p class="mt-2 text-2xl font-extrabold text-white">
                                        {{ number_format($stats['processing_orders']) }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 rounded-2xl bg-white p-4 text-slate-950">
                                <p class="text-xs font-bold text-slate-500">
                                    Status Sistem
                                </p>

                                <p class="mt-1 text-lg font-extrabold text-emerald-600">
                                    Siap menerima pesanan
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="games" class="bg-slate-50 py-12 sm:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wide text-indigo-600">
                        Katalog Game
                    </p>

                    <h2 class="mt-2 text-2xl font-extrabold text-slate-950 sm:text-3xl">
                        Pilih game yang mau lu top up
                    </h2>

                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                        Semua game di bawah ini diambil langsung dari admin panel.
                    </p>
                </div>

                <div class="w-full md:max-w-sm">
                    <label class="sr-only" for="search-game">Cari game</label>

                    <input
                        id="search-game"
                        type="text"
                        wire:model.live.debounce.400ms="search"
                        placeholder="Cari game..."
                        class="w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                @forelse ($games as $game)
                    <a
                        href="{{ route('games.show', $game->slug) }}"
                        class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-950/10"
                    >
                        <div class="aspect-[4/5] bg-slate-100">
                            @if ($game->image_path)
                                <img
                                    src="{{ asset('storage/' . $game->image_path) }}"
                                    alt="{{ $game->name }}"
                                    class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                >
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 to-indigo-100">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-white text-indigo-600 shadow-sm">
                                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M8 13H6m1-1v2m9-1h.01M18 15h.01M7.2 18h9.6c2.2 0 4-1.8 4-4v-2c0-2.2-1.8-4-4-4H7.2c-2.2 0-4 1.8-4 4v2c0 2.2 1.8 4 4 4Z"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                            />
                                        </svg>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="line-clamp-2 text-sm font-extrabold text-slate-950">
                                        {{ $game->name }}
                                    </h3>

                                    @if ($game->category)
                                        <p class="mt-1 text-xs font-medium text-slate-500">
                                            {{ $game->category }}
                                        </p>
                                    @endif
                                </div>

                                @if ($game->badge)
                                    <span class="shrink-0 rounded-full bg-indigo-50 px-2 py-1 text-[10px] font-bold text-indigo-600">
                                        {{ $game->badge }}
                                    </span>
                                @endif
                            </div>

                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">
                                    Top Up
                                </span>

                                <span class="text-xs font-extrabold text-indigo-600">
                                    Pilih
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-8 text-center">
                        <p class="text-sm font-bold text-slate-700">
                            Belum ada game aktif.
                        </p>

                        <p class="mt-2 text-sm text-slate-500">
                            Tambahkan game dari admin panel terlebih dahulu.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="cek-pesanan" class="bg-white py-12 sm:py-16">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-slate-200 bg-slate-50 p-6 shadow-sm sm:p-8">
                <div class="text-center">
                    <p class="text-sm font-bold uppercase tracking-wide text-indigo-600">
                        Cek Pesanan
                    </p>

                    <h2 class="mt-2 text-2xl font-extrabold text-slate-950">
                        Lihat status pesanan
                    </h2>

                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        Masukkan nomor invoice untuk melihat status transaksi.
                    </p>
                </div>

                <form wire:submit.prevent="checkOrder" class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <input
                        type="text"
                        wire:model.defer="invoice"
                        placeholder="Contoh: INV-20260706-000001"
                        class="min-h-12 flex-1 rounded-2xl border-slate-200 bg-white px-4 text-sm font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    <button
                        type="submit"
                        class="rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-600/20 transition hover:-translate-y-0.5 hover:bg-indigo-700"
                    >
                        Cek Pesanan
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>