<div class="flex min-h-screen flex-col bg-slate-50">

    {{-- CONTENT --}}
    <main class="flex-1">

        <section class="py-14 sm:py-20">

            <div class="mx-auto max-w-7xl px-6">


                {{-- HEADER --}}
                <div class="mx-auto max-w-3xl text-center">

                    <h1
                        class="mt-5 text-4xl font-extrabold tracking-tight text-slate-950 sm:text-5xl"
                    >
                        Promo Terbaru
                    </h1>


                    <p
                        class="mt-4 text-lg leading-8 text-slate-600"
                    >
                        Dapatkan promo menarik untuk top up game favorit Anda.
                    </p>

                </div>



                {{-- PROMO LIST --}}
                <div
                    class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3"
                >

                    @forelse($promos as $promo)


                    <article
                        class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl"
                    >


                        {{-- IMAGE --}}
                        <div class="aspect-[16/9] overflow-hidden bg-slate-100">


                            @if($promo->gambar)

                                <img
                                    src="{{ asset('storage/'.$promo->gambar) }}"
                                    alt="{{ $promo->judul }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                >

                            @else

                                <div
                                    class="flex h-full items-center justify-center text-slate-400"
                                >
                                    Tidak ada gambar
                                </div>

                            @endif


                        </div>



                        {{-- CONTENT --}}
                        <div class="p-6">


                            <div class="flex items-start justify-between gap-3">


                                <h2
                                    class="text-xl font-extrabold text-slate-950"
                                >
                                    {{ $promo->judul }}
                                </h2>



                                @if($promo->diskon > 0)

                                <span
                                    class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700"
                                >
                                    -{{ $promo->diskon }}%
                                </span>

                                @endif


                            </div>



                            <p
                                class="mt-3 text-sm leading-6 text-slate-600"
                            >
                                {{ $promo->deskripsi }}
                            </p>



                            @if($promo->kode_promo)

                            <div
                                class="mt-5 rounded-2xl bg-indigo-50 p-4"
                            >

                                <p
                                    class="text-xs font-bold uppercase text-indigo-600"
                                >
                                    Kode Promo
                                </p>


                                <p
                                    class="mt-1 text-lg font-extrabold text-indigo-700"
                                >
                                    {{ $promo->kode_promo }}
                                </p>

                            </div>

                            @endif



                            <div
                                class="mt-5 flex items-center justify-between text-xs text-slate-500"
                            >

                                <span>
                                    Berlaku:
                                </span>


                                <span class="font-semibold">
                                    {{ \Carbon\Carbon::parse($promo->tanggal_mulai)->format('d M Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($promo->tanggal_akhir)->format('d M Y') }}
                                </span>

                            </div>



                        </div>


                    </article>


                    @empty


                    <div
                        class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center"
                    >

                        <h3
                            class="font-bold text-slate-800"
                        >
                            Belum ada promo tersedia
                        </h3>


                        <p
                            class="mt-2 text-sm text-slate-500"
                        >
                            Promo terbaru akan muncul di halaman ini.
                        </p>

                    </div>


                    @endforelse


                </div>


            </div>


        </section>


    </main>



</div>