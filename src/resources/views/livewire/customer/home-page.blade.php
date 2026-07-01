<div>
    <section class="gaming-container grid gap-10 py-16 md:grid-cols-2 md:py-24">
        <div>
            <div class="mb-5 inline-flex rounded-full border border-cyan-400/40 px-4 py-2 text-sm font-semibold text-cyan-300">
                Top up cepat, aman, dan otomatis
            </div>

            <h1 class="text-4xl font-extrabold leading-tight md:text-6xl">
                Top Up Game Favorit Kamu Tanpa Ribet
            </h1>

            <p class="mt-5 max-w-xl text-slate-300">
                Pilih game, isi data akun, bayar, lalu pesanan diproses otomatis.
            </p>

            <a href="#games" class="mt-8 inline-flex rounded-2xl gaming-gradient px-6 py-3 font-bold text-white gaming-glow">
                Mulai Top Up
            </a>
        </div>

        <div class="gaming-card p-6">
            <div class="rounded-2xl bg-slate-900 p-6">
                <p class="text-sm text-slate-400">Promo Mingguan</p>
                <h2 class="mt-2 text-3xl font-bold">Diskon sampai 20%</h2>
                <p class="mt-3 text-sm text-slate-400">
                    Berlaku untuk game pilihan dan metode pembayaran tertentu.
                </p>
            </div>
        </div>
    </section>

    <section id="games" class="gaming-container py-12">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase text-cyan-300">Katalog Game</p>
            <h2 class="mt-2 text-3xl font-bold">Pilih Game</h2>

            <input
                type="text"
                wire:model.live="search"
                placeholder="Cari game..."
                class="mt-5 w-full rounded-2xl border border-slate-700 bg-slate-900 px-4 py-3 text-white outline-none focus:border-cyan-400 md:max-w-md"
            >
        </div>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            @forelse ($games as $game)
                <div class="gaming-card p-5">
                    <div class="text-5xl">
                        {{ $game->icon ?: '🎮' }}
                    </div>

                    <div class="mt-4 flex items-center justify-between gap-2">
                        <span class="rounded-full bg-blue-500/20 px-3 py-1 text-xs font-bold text-blue-300">
                            {{ $game->badge ?: 'Game' }}
                        </span>

                        <span class="text-xs text-slate-400">
                            {{ $game->category ?: '-' }}
                        </span>
                    </div>

                    <h3 class="mt-4 font-bold">
                        {{ $game->name }}
                    </h3>

                    <a
                        href="#"
                        class="mt-4 block w-full rounded-xl gaming-gradient px-4 py-2 text-center text-sm font-bold"
                    >
                        Top Up
                    </a>
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-slate-800 bg-slate-900 p-8 text-center">
                    <h3 class="text-lg font-bold text-white">
                        Belum ada game aktif
                    </h3>

                    <p class="mt-2 text-sm text-slate-400">
                        Tambahkan game dari panel admin Filament agar tampil di katalog.
                    </p>
                </div>
            @endforelse
        </div>
    </section>
</div>