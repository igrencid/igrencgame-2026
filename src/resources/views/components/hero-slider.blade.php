@php
    $images = \App\Support\Branding::heroImageUrls();
    $imageCount = count($images);
    $siteName = \App\Support\Branding::name();
    $tagline = \App\Support\Branding::tagline();
@endphp

<div
    x-data="{
        active: 0,
        total: {{ $imageCount }},
        timer: null,
        paused: false,
        interval: 1500,

        init() {
            const reduceMotion = window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches;

            if (this.total > 1 && ! reduceMotion) {
                this.start();
            }
        },

        start() {
            this.stop();

            if (this.total <= 1) {
                return;
            }

            this.timer = window.setInterval(() => {
                if (! this.paused) {
                    this.next();
                }
            }, this.interval);
        },

        stop() {
            if (this.timer) {
                window.clearInterval(this.timer);
                this.timer = null;
            }
        },

        next() {
            if (this.total <= 1) {
                return;
            }

            this.active = (this.active + 1) % this.total;
        },

        previous() {
            if (this.total <= 1) {
                return;
            }

            this.active =
                (this.active - 1 + this.total) % this.total;
        },

        goTo(index) {
            this.active = index;
            this.start();
        },

        destroy() {
            this.stop();
        }
    }"
    @mouseenter="paused = true"
    @mouseleave="paused = false"
    @focusin="paused = true"
    @focusout="paused = false"
    class="relative w-full lg:justify-self-end"
    role="region"
    aria-roledescription="carousel"
    aria-label="Slider hero {{ $siteName }}"
>
    <div class="absolute -inset-5 rounded-[2.5rem] bg-gradient-to-br from-indigo-200/55 via-violet-200/35 to-fuchsia-200/20 blur-2xl"></div>

    <div class="relative overflow-hidden rounded-[2rem] border border-white bg-white p-3 shadow-2xl shadow-indigo-950/10">
        <div class="relative aspect-[4/3] overflow-hidden rounded-[1.5rem] bg-slate-100">
            @forelse ($images as $index => $imageUrl)
                <div
                    x-show="active === {{ $index }}"
                    x-transition:enter="transition-opacity duration-500 ease-out"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-500 ease-in"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @if (! $loop->first)
                        style="display: none;"
                    @endif
                    class="absolute inset-0"
                    :aria-hidden="active !== {{ $index }}"
                >
                    <img
                        src="{{ $imageUrl }}"
                        alt="Banner {{ $siteName }} {{ $index + 1 }}"
                        class="h-full w-full object-cover"
                        @if ($loop->first)
                            loading="eager"
                            fetchpriority="high"
                        @else
                            loading="lazy"
                        @endif
                        decoding="async"
                    >

                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-950/5 to-transparent"></div>

                    <div class="absolute inset-x-0 bottom-0 p-6 text-white sm:p-7">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-200">
                            {{ $tagline }}
                        </p>

                        <p class="mt-2 max-w-md text-xl font-black leading-tight sm:text-2xl">
                            Top up game dengan proses yang cepat dan mudah dipantau.
                        </p>
                    </div>
                </div>
            @empty
                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-50 via-violet-50 to-slate-100 px-8 text-center">
                    <div class="max-w-sm">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-600">
                            {{ $tagline }}
                        </p>

                        <p class="mt-3 text-2xl font-black text-slate-950">
                            Top up game lebih cepat dan praktis.
                        </p>

                        <p class="mt-3 text-sm leading-6 text-slate-500">
                            Pilih game favorit Anda dan selesaikan transaksi melalui sistem {{ $siteName }}.
                        </p>
                    </div>
                </div>
            @endforelse

            @if ($imageCount > 1)
                {{-- Previous --}}
                <button
                    type="button"
                    @click="previous(); start()"
                    class="absolute left-4 top-1/2 z-20 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/20 bg-slate-950/45 text-white backdrop-blur transition hover:bg-slate-950/70 focus:outline-none focus:ring-2 focus:ring-white"
                    aria-label="Gambar sebelumnya"
                >
                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        aria-hidden="true"
                    >
                        <path d="m15 18-6-6 6-6"></path>
                    </svg>
                </button>

                {{-- Next --}}
                <button
                    type="button"
                    @click="next(); start()"
                    class="absolute right-4 top-1/2 z-20 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/20 bg-slate-950/45 text-white backdrop-blur transition hover:bg-slate-950/70 focus:outline-none focus:ring-2 focus:ring-white"
                    aria-label="Gambar berikutnya"
                >
                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        aria-hidden="true"
                    >
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </button>

                {{-- Indicators --}}
                <div class="absolute bottom-4 right-5 z-20 flex items-center gap-2">
                    @foreach ($images as $index => $imageUrl)
                        <button
                            type="button"
                            @click="goTo({{ $index }})"
                            class="h-2 rounded-full bg-white shadow-sm transition-all duration-300"
                            :class="active === {{ $index }}
                                ? 'w-7 opacity-100'
                                : 'w-2 opacity-50 hover:opacity-80'"
                            aria-label="Tampilkan gambar {{ $index + 1 }}"
                            :aria-current="active === {{ $index }} ? 'true' : 'false'"
                        ></button>
                    @endforeach
                </div>

                {{-- Counter --}}
                <div class="absolute right-5 top-5 z-20 rounded-full border border-white/15 bg-slate-950/45 px-3 py-1.5 text-xs font-bold text-white backdrop-blur">
                    <span x-text="active + 1"></span>
                    <span class="text-white/50">/</span>
                    <span>{{ $imageCount }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
