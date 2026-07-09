<div>
    {{-- Hero Section --}}
    <section class="bg-gradient-to-b from-slate-50 to-white py-12 md:py-20">
        <div class="mx-auto max-w-4xl px-4 text-center">
            <h1 class="mb-4 text-4xl font-black text-slate-950 md:text-5xl">
                Pertanyaan yang Sering Diajukan
            </h1>

            <p class="mx-auto max-w-2xl text-base leading-relaxed text-slate-600 md:text-lg">
                Temukan jawaban untuk pertanyaan yang sering ditanyakan seputar layanan top up game di Igrenc.
            </p>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="bg-white py-8 md:py-12">
        <div class="mx-auto max-w-4xl px-4">
            {{-- Search Bar --}}
            <div class="mb-10">
                <div class="mx-auto max-w-2xl">
                    <input
                        type="search"
                        wire:model.live.debounce.500ms="search"
                        placeholder="Cari pertanyaan..."
                        class="block h-12 w-full rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100"
                    >
                </div>
            </div>

            {{-- Pertanyaan Umum Items --}}
            @if ($faqs->count() > 0)
                <div class="space-y-3">
                    @foreach ($faqs as $index => $faq)
                        <details
                            class="group cursor-pointer overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:border-indigo-200 hover:shadow-md"
                            @if ($index === 0 && blank($search ?? null)) open @endif
                        >
                            <summary class="flex items-center justify-between gap-4 px-5 py-5 text-left font-bold text-slate-900 transition hover:bg-slate-50 md:px-6">
                                <span>
                                    {{ $faq->question }}
                                </span>

                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 transition group-open:rotate-180">
                                    <svg
                                        class="h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2.2"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m19.5 8.25-7.5 7.5-7.5-7.5"
                                        />
                                    </svg>
                                </span>
                            </summary>

                            <div class="border-t border-slate-100 bg-slate-50 px-5 py-5 text-slate-700 md:px-6">
                                <div class="prose prose-sm max-w-none leading-7 text-slate-700">
                                    {!! $faq->answer !!}
                                </div>
                            </div>
                        </details>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-14 text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-white text-slate-400 shadow-sm">
                        <svg
                            class="h-7 w-7"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v3.75m0 3.75h.008v.008H12V16.5Zm9-4.5a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                            />
                        </svg>
                    </div>

                    <h3 class="mb-2 text-lg font-extrabold text-slate-900">
                        Pertanyaan Umum tidak ditemukan
                    </h3>

                    <p class="mx-auto max-w-md text-sm leading-6 text-slate-600">
                        Coba gunakan kata kunci lain atau hubungi Layanan Pelanggan kami melalui informasi kontak di bagian bawah halaman.
                    </p>
                </div>
            @endif
        </div>
    </section>

    {{-- Layanan Pelanggan CTA --}}
    @if ($siteSetting?->customer_service_whatsapp)
        <section class="bg-slate-50 py-8 md:py-12">
            <div class="mx-auto max-w-4xl px-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <div class="items-center justify-between gap-6 md:flex">
                        <div class="mb-5 md:mb-0">
                            <h3 class="mb-2 text-xl font-extrabold text-slate-950 md:text-2xl">
                                Masih butuh bantuan?
                            </h3>

                            <p class="max-w-2xl text-sm leading-6 text-slate-600 md:text-base">
                                Tim Layanan Pelanggan kami siap membantu kendala pembayaran, data akun, dan status pesanan.
                            </p>
                        </div>

                        <a
                            href="https://wa.me/{{ $siteSetting->customer_service_whatsapp }}?text={{ urlencode('Halo admin Igrenc, saya butuh bantuan terkait pesanan.') }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center rounded-full border border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700"
                        >
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
