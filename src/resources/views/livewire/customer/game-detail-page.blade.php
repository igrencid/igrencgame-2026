<div>
    <section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-slate-50">
        <div class="absolute inset-0 -z-10">
            <div class="absolute left-1/2 top-0 h-72 w-72 -translate-x-1/2 rounded-full bg-indigo-300/30 blur-3xl"></div>
            <div class="absolute right-0 top-20 h-72 w-72 rounded-full bg-violet-300/30 blur-3xl"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <a
                href="{{ route('home') }}#games"
                class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 transition hover:text-indigo-600"
            >
                <span>←</span>
                Kembali ke katalog
            </a>

            <div class="mt-6 overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-indigo-950/5">
                <div class="relative h-56 bg-slate-100 sm:h-72 lg:h-80">
                    @if ($game->banner_path)
                        <img
                            src="{{ asset('storage/' . $game->banner_path) }}"
                            alt="{{ $game->name }}"
                            class="h-full w-full object-cover"
                        >
                    @elseif ($game->image_path)
                        <img
                            src="{{ asset('storage/' . $game->image_path) }}"
                            alt="{{ $game->name }}"
                            class="h-full w-full object-cover"
                        >
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-100 via-white to-slate-100">
                            <div class="rounded-3xl bg-white px-5 py-3 text-sm font-extrabold text-indigo-600 shadow-sm">
                                {{ $game->name }}
                            </div>
                        </div>
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-slate-950/20 to-transparent"></div>

                    <div class="absolute bottom-0 left-0 right-0 p-5 sm:p-8">
                        <div class="flex flex-col gap-5 sm:flex-row sm:items-end">
                            <div class="h-24 w-24 overflow-hidden rounded-3xl border-4 border-white bg-white shadow-lg">
                                @if ($game->image_path)
                                    <img
                                        src="{{ asset('storage/' . $game->image_path) }}"
                                        alt="{{ $game->name }}"
                                        class="h-full w-full object-cover"
                                    >
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-indigo-50 text-3xl font-black text-indigo-600">
                                        {{ strtoupper(substr($game->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    @if ($game->category)
                                        <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-bold text-white ring-1 ring-white/20">
                                            {{ $game->category }}
                                        </span>
                                    @endif

                                    @if ($game->badge)
                                        <span class="rounded-full bg-indigo-500 px-3 py-1 text-xs font-bold text-white">
                                            {{ $game->badge }}
                                        </span>
                                    @endif
                                </div>

                                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                                    {{ $game->name }}
                                </h1>

                                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-200">
                                    {{ $game->description ?: 'Pilih nominal top up yang tersedia, lalu lanjutkan pembayaran.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 p-5 sm:p-8 lg:grid-cols-[1fr_360px]">
                    <div>
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-bold uppercase tracking-wide text-indigo-600">
                                    Produk Top Up
                                </p>

                                <h2 class="mt-1 text-2xl font-extrabold text-slate-950">
                                    Pilih produk
                                </h2>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            @forelse ($game->products as $product)
                                <a
                                    href="{{ route('checkout.show', $game->slug) }}?product={{ $product->id }}"
                                    class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-950/10"
                                >
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h3 class="text-sm font-extrabold text-slate-950">
                                                {{ $product->name }}
                                            </h3>

                                            @if ($product->description)
                                                <p class="mt-2 line-clamp-2 text-xs leading-5 text-slate-500">
                                                    {{ $product->description }}
                                                </p>
                                            @endif
                                        </div>

                                        <span class="shrink-0 rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-600">
                                            Pilih
                                        </span>
                                    </div>

                                    <div class="mt-5 flex items-end justify-between">
                                        <div>
                                            <p class="text-xs font-semibold text-slate-500">
                                                Harga
                                            </p>

                                            <p class="mt-1 text-lg font-black text-slate-950">
                                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <span class="text-sm font-extrabold text-indigo-600 transition group-hover:translate-x-1">
                                            Lanjut →
                                        </span>
                                    </div>
                                </a>
                            @empty
                                <div class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center sm:col-span-2">
                                    <p class="text-sm font-bold text-slate-700">
                                        Produk belum tersedia.
                                    </p>

                                    <p class="mt-2 text-sm text-slate-500">
                                        Tambahkan produk top up dari admin panel.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <aside class="space-y-4">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-sm font-extrabold text-slate-950">
                                Data yang dibutuhkan
                            </p>

                            <div class="mt-4 space-y-3">
                                @forelse ($game->inputFields as $field)
                                    <div class="rounded-2xl bg-white p-4 shadow-sm">
                                        <div class="flex items-center justify-between gap-3">
                                            <p class="text-sm font-bold text-slate-800">
                                                {{ $field->label }}
                                            </p>

                                            @if ($field->is_required)
                                                <span class="rounded-full bg-rose-50 px-2 py-1 text-[10px] font-bold text-rose-600">
                                                    Wajib
                                                </span>
                                            @else
                                                <span class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-500">
                                                    Opsional
                                                </span>
                                            @endif
                                        </div>

                                        @if ($field->placeholder)
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $field->placeholder }}
                                            </p>
                                        @endif
                                    </div>
                                @empty
                                    <div class="rounded-2xl bg-white p-4 text-sm text-slate-500 shadow-sm">
                                        Belum ada konfigurasi input field.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="rounded-3xl border border-indigo-100 bg-indigo-50 p-5">
                            <p class="text-sm font-extrabold text-indigo-950">
                                Proses cepat
                            </p>

                            <p class="mt-2 text-sm leading-6 text-indigo-700">
                                Setelah memilih nominal, lu akan diarahkan ke checkout untuk mengisi data akun dan memilih pembayaran.
                            </p>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
</div>
