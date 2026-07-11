<section class="border-y border-slate-200 bg-slate-50 py-14 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 border-b border-slate-200 pb-8 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-3xl">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-indigo-600">
                    Promo Terbaru
                </p>

                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">
                    Penawaran menarik untuk
                    <span class="bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-500 bg-clip-text text-transparent">
                        game favorit Anda
                    </span>
                </h2>

                <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                    Lihat promo aktif dan nikmati penawaran terbaik untuk transaksi top up Anda.
                </p>
            </div>

            <a
                href="{{ route('promo.index') }}"
                wire:navigate
                class="inline-flex min-h-11 shrink-0 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus:ring-4 focus:ring-indigo-100"
            >
                Lihat Semua Promo
            </a>
        </div>

        @if ($promos->isNotEmpty())
            <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($promos as $promo)
                    <article
                        wire:key="homepage-promo-{{ $promo->id }}"
                        class="group flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white transition duration-200 hover:-translate-y-1 hover:border-indigo-300 hover:shadow-xl hover:shadow-slate-950/10"
                    >
                        <div class="relative aspect-[16/9] overflow-hidden bg-slate-100">
                            @if ($promo->gambar)
                                <img
                                    src="{{ asset('storage/' . $promo->gambar) }}"
                                    alt="{{ $promo->judul }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                    loading="lazy"
                                >
                            @else
                                <div class="flex h-full items-center justify-center bg-gradient-to-br from-slate-100 via-indigo-50 to-violet-100 px-8 text-center">
                                    <span class="text-lg font-black text-indigo-700">
                                        {{ $promo->judul }}
                                    </span>
                                </div>
                            @endif

                            <div class="absolute inset-x-0 top-0 flex items-start justify-between gap-3 p-4">
                                <span class="rounded-lg bg-slate-950/85 px-3 py-1.5 text-xs font-bold text-white backdrop-blur">
                                    Promo Aktif
                                </span>

                                @if ((int) $promo->diskon > 0)
                                    <span class="rounded-lg bg-emerald-500 px-3 py-1.5 text-xs font-bold text-white">
                                        Diskon {{ number_format($promo->diskon) }}%
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex-1">
                                <h3 class="text-xl font-black text-slate-950">
                                    {{ $promo->judul }}
                                </h3>

                                <p class="mt-3 text-sm leading-6 text-slate-600">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($promo->deskripsi), 130) }}
                                </p>

                                @if ($promo->kode_promo)
                                    <div class="mt-5 rounded-2xl border border-dashed border-indigo-200 bg-indigo-50 px-4 py-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-indigo-500">
                                            Kode Promo
                                        </p>

                                        <code class="mt-1 block text-lg font-black tracking-wider text-indigo-700">
                                            {{ $promo->kode_promo }}
                                        </code>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6 flex items-center justify-between gap-4 border-t border-slate-100 pt-4">
                                <div>
                                    <p class="text-xs font-medium text-slate-400">
                                        Berlaku hingga
                                    </p>

                                    <p class="mt-1 text-sm font-bold text-slate-700">
                                        {{ \Illuminate\Support\Carbon::parse($promo->tanggal_akhir)->translatedFormat('d M Y') }}
                                    </p>
                                </div>

                                <a
                                    href="{{ route('promo.index') }}"
                                    wire:navigate
                                    class="inline-flex min-h-10 items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                                >
                                    Lihat Promo
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="mt-8 rounded-3xl border border-slate-200 bg-white px-6 py-12 text-center">
                <h3 class="text-lg font-bold text-slate-950">
                    Belum ada promo aktif
                </h3>

                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                    Penawaran terbaru akan ditampilkan di bagian ini ketika promo tersedia.
                </p>

                <a
                    href="{{ route('promo.index') }}"
                    wire:navigate
                    class="mt-6 inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:border-indigo-300 hover:text-indigo-600 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                >
                    Buka Halaman Promo
                </a>
            </div>
        @endif
    </div>
</section>
