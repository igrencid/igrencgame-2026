<div>
    <section class="min-h-screen bg-slate-50 py-8 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav
                aria-label="Breadcrumb"
                class="flex items-center gap-2 text-sm font-semibold"
            >
                <a
                    href="{{ route('home') }}"
                    wire:navigate
                    class="text-slate-500 transition hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    Beranda
                </a>

                <svg
                    class="h-4 w-4 text-slate-300"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                >
                    <path
                        fill-rule="evenodd"
                        d="M7.21 14.77a.75.75 0 0 1 .02-1.06L10.94 10 7.23 6.29a.75.75 0 1 1 1.06-1.06l4.24 4.24a.75.75 0 0 1 0 1.06l-4.24 4.24a.75.75 0 0 1-1.08 0Z"
                        clip-rule="evenodd"
                    />
                </svg>

                <span class="text-slate-900">
                    Top Up Game
                </span>
            </nav>

            {{-- Page heading --}}
            <div class="mt-6 max-w-3xl">
                <p class="text-sm font-extrabold uppercase tracking-wider text-indigo-600">
                    Katalog Game
                </p>

                <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl lg:text-5xl">
                    Pilih game yang ingin Anda top up
                </h1>

                <p class="mt-4 text-base leading-7 text-slate-600 sm:text-lg">
                    Cari game favorit Anda, pilih produk yang tersedia, lalu
                    selesaikan transaksi melalui metode pembayaran yang aman.
                </p>
            </div>

            {{-- Search and filters --}}
            <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end">
                    <div class="flex-1">
                        <label
                            for="game-search"
                            class="mb-2 block text-sm font-bold text-slate-700"
                        >
                            Cari game
                        </label>

                        <div class="relative">
                            <svg
                                class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                aria-hidden="true"
                            >
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="m20 20-3.5-3.5"></path>
                            </svg>

                            <input
                                wire:model.live.debounce.400ms="search"
                                id="game-search"
                                type="search"
                                autocomplete="off"
                                placeholder="Ketik nama game..."
                                class="h-12 w-full rounded-2xl border border-slate-300 bg-white pl-12 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                            >
                        </div>
                    </div>

                    @if ($supportsPopular)
                        <button
                            type="button"
                            wire:click="togglePopular"
                            @class([
                                'inline-flex h-12 items-center justify-center gap-2 rounded-2xl border px-5 text-sm font-extrabold transition focus:outline-none focus:ring-4 focus:ring-indigo-100',
                                'border-indigo-600 bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' => $popularOnly,
                                'border-slate-300 bg-white text-slate-700 hover:border-indigo-300 hover:text-indigo-600' => ! $popularOnly,
                            ])
                        >
                            <svg
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                aria-hidden="true"
                            >
                                <path d="m12 3 2.7 5.47 6.03.88-4.36 4.25 1.03 6-5.4-2.84L6.6 19.6l1.03-6-4.36-4.25 6.03-.88L12 3Z"></path>
                            </svg>

                            {{ $popularOnly ? 'Game Populer Aktif' : 'Game Populer' }}
                        </button>
                    @endif
                </div>

                @if ($supportsCategory && $categories->isNotEmpty())
                    <div class="mt-5 border-t border-slate-100 pt-5">
                        <p class="mb-3 text-sm font-bold text-slate-700">
                            Kategori
                        </p>

                        <div class="flex gap-2 overflow-x-auto pb-1">
                            <button
                                type="button"
                                wire:click="selectCategory('semua')"
                                @class([
                                    'shrink-0 rounded-xl border px-4 py-2 text-sm font-bold transition focus:outline-none focus:ring-4 focus:ring-indigo-100',
                                    'border-indigo-600 bg-indigo-600 text-white' => $category === 'semua',
                                    'border-slate-200 bg-slate-50 text-slate-600 hover:border-indigo-300 hover:text-indigo-600' => $category !== 'semua',
                                ])
                            >
                                Semua
                            </button>

                            @foreach ($categories as $categoryOption)
                                <button
                                    type="button"
                                    wire:click="selectCategory(@js($categoryOption))"
                                    @class([
                                        'shrink-0 rounded-xl border px-4 py-2 text-sm font-bold transition focus:outline-none focus:ring-4 focus:ring-indigo-100',
                                        'border-indigo-600 bg-indigo-600 text-white' => $category === $categoryOption,
                                        'border-slate-200 bg-slate-50 text-slate-600 hover:border-indigo-300 hover:text-indigo-600' => $category !== $categoryOption,
                                    ])
                                >
                                    {{ $categoryOption }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Result heading --}}
            <div class="mt-10 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-extrabold uppercase tracking-wider text-indigo-600">
                        Daftar Game
                    </p>

                    <h2 class="mt-1 text-2xl font-black text-slate-950 sm:text-3xl">
                        {{ $popularOnly ? 'Game Populer' : 'Semua Game Aktif' }}
                    </h2>

                    @if (trim($search) !== '')
                        <p class="mt-2 text-sm text-slate-500">
                            Hasil pencarian untuk
                            <span class="font-bold text-slate-800">
                                “{{ $search }}”
                            </span>
                        </p>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-slate-500">
                        {{ $games->total() }} game ditemukan
                    </span>

                    @if (
                        trim($search) !== ''
                        || $category !== 'semua'
                        || $popularOnly
                    )
                        <button
                            type="button"
                            wire:click="clearFilters"
                            class="text-sm font-extrabold text-indigo-600 transition hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            Hapus filter
                        </button>
                    @endif
                </div>
            </div>

            {{-- Loading skeleton --}}
            <div
                wire:loading.grid
                wire:target="search,category,popularOnly,selectCategory,togglePopular,clearFilters"
                class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"
            >
                @for ($index = 0; $index < 10; $index++)
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                        <div class="aspect-[4/3] animate-pulse bg-slate-200"></div>

                        <div class="space-y-3 p-4">
                            <div class="h-4 w-3/4 animate-pulse rounded bg-slate-200"></div>
                            <div class="h-3 w-1/2 animate-pulse rounded bg-slate-100"></div>
                            <div class="h-10 animate-pulse rounded-xl bg-slate-200"></div>
                        </div>
                    </div>
                @endfor
            </div>

            {{-- Game grid --}}
            <div
                wire:loading.remove
                wire:target="search,category,popularOnly,selectCategory,togglePopular,clearFilters"
            >
                @if ($games->isNotEmpty())
                    <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                        @foreach ($games as $game)
                            <article
                                wire:key="top-up-game-{{ $game->id }}"
                                class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition duration-200 hover:-translate-y-1 hover:border-indigo-300 hover:shadow-xl hover:shadow-indigo-950/10"
                            >
                                <a
                                    href="{{ route('games.show', $game->slug) }}"
                                    wire:navigate
                                    class="relative block overflow-hidden bg-slate-100 focus:outline-none focus:ring-4 focus:ring-inset focus:ring-indigo-300"
                                    aria-label="Pilih {{ $game->name }}"
                                >
                                    <div class="aspect-[4/3]">
                                        @if ($game->display_image_url)
                                            <img
                                                src="{{ $game->display_image_url }}"
                                                alt="Sampul {{ $game->name }}"
                                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-50 to-slate-100">
                                                <span class="text-4xl font-black text-indigo-300">
                                                    {{ Str::upper(Str::substr($game->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    @if ($supportsPopular && $game->is_popular)
                                        <span class="absolute left-3 top-3 inline-flex items-center gap-1 rounded-lg bg-slate-950/85 px-2.5 py-1 text-xs font-extrabold text-white backdrop-blur-sm">
                                            <svg
                                                class="h-3.5 w-3.5"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                                aria-hidden="true"
                                            >
                                                <path d="m12 2.7 2.85 5.78 6.38.93-4.62 4.5 1.09 6.35L12 17.26l-5.7 3 1.09-6.35-4.62-4.5 6.38-.93L12 2.7Z"></path>
                                            </svg>

                                            Populer
                                        </span>
                                    @endif
                                </a>

                                <div class="flex flex-1 flex-col p-4">
                                    <div class="flex-1">
                                        <h3 class="line-clamp-2 text-base font-black text-slate-950">
                                            {{ $game->name }}
                                        </h3>

                                        <p class="mt-1 line-clamp-1 text-xs font-semibold text-slate-500">
                                            {{ $game->publisher ?: ($game->category ?: 'Game Online') }}
                                        </p>

                                        <div class="mt-4">
                                            <p class="text-xs font-semibold text-slate-400">
                                                Harga mulai
                                            </p>

                                            @if ($game->minimum_price !== null)
                                                <p class="mt-1 text-lg font-black text-indigo-600">
                                                    Rp {{ number_format((int) $game->minimum_price, 0, ',', '.') }}
                                                </p>
                                            @else
                                                <p class="mt-1 text-sm font-bold text-slate-500">
                                                    Produk segera tersedia
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <a
                                        href="{{ route('games.show', $game->slug) }}"
                                        wire:navigate
                                        class="mt-4 inline-flex min-h-11 w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-extrabold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200"
                                    >
                                        Pilih Game
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if ($games->hasPages())
                        <div class="mt-10">
                            {{ $games->links() }}
                        </div>
                    @endif
                @else
                    <div class="mt-6 rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                            <svg
                                class="h-8 w-8"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true"
                            >
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="m20 20-3.5-3.5"></path>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-xl font-black text-slate-950">
                            Game tidak ditemukan
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                            Tidak ada game yang sesuai dengan pencarian atau
                            filter saat ini. Coba gunakan kata kunci lain.
                        </p>

                        <button
                            type="button"
                            wire:click="clearFilters"
                            class="mt-6 inline-flex min-h-11 items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-extrabold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200"
                        >
                            Tampilkan Semua Game
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
