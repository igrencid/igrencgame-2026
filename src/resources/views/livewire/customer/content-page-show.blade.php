<div>
    @if ($page)
        {{-- Hero Section --}}
        <section class="bg-gradient-to-b from-slate-50 to-white py-12 md:py-20">
            <div class="mx-auto max-w-4xl px-4">
                <h1 class="text-4xl md:text-5xl font-black text-slate-950 mb-3">
                    {{ $page->title }}
                </h1>
                <p class="text-sm text-slate-500">
                    Terakhir diperbarui: {{ $page->updated_at->translatedFormat('d M Y') }}
                </p>
            </div>
        </section>

        {{-- Content Section --}}
        <section class="py-8 md:py-12 bg-white">
            <div class="mx-auto max-w-4xl px-4">
                <article class="prose prose-sm md:prose-base max-w-none text-slate-700">
                    {!! $page->content !!}
                </article>
            </div>
        </section>
    @else
        {{-- Fallback Section --}}
        <section class="bg-gradient-to-b from-slate-50 to-white py-12 md:py-20">
            <div class="mx-auto max-w-4xl px-4 text-center">
                <svg class="w-20 h-20 text-slate-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h2 class="text-2xl font-bold text-slate-950 mb-3">
                    Konten halaman ini belum tersedia.
                </h2>
                <p class="text-slate-600 mb-8">
                    Halaman yang Anda cari sedang dalam penyusunan. Mohon coba kembali nanti.
                </p>
                <a href="/" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </section>
    @endif
</div>
