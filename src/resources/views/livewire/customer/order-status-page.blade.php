<div wire:poll.10s>
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <a
                href="{{ route('home') }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-indigo-600"
            >
                ← Kembali ke Home
            </a>

            <div class="mt-6 overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-indigo-950/5">
                <div class="bg-slate-950 p-6 text-white sm:p-8">
                    <p class="text-sm font-bold uppercase tracking-wide text-indigo-300">
                        Cek Pesanan
                    </p>

                    <div class="mt-3 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-extrabold sm:text-3xl">
                                {{ $order->invoice_number }}
                            </h1>

                            <p class="mt-2 text-sm text-slate-300">
                                Dibuat pada {{ $order->created_at->format('d M Y H:i') }}
                            </p>
                        </div>

                        <span class="inline-flex w-fit items-center rounded-full px-4 py-2 text-sm font-extrabold ring-1 {{ $this->statusBadgeClass }}">
                            {{ $this->statusLabel }}
                        </span>
                    </div>
                </div>

                <div class="grid gap-6 p-6 sm:p-8 lg:grid-cols-[1fr_320px]">
                    <main class="space-y-6">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <h2 class="text-lg font-extrabold text-slate-950">
                                Detail Pesanan
                            </h2>

                            <div class="mt-5 space-y-4">
                                <div class="flex justify-between gap-4 text-sm">
                                    <span class="text-slate-500">Game</span>
                                    <span class="text-right font-bold text-slate-900">
                                        {{ $order->game_name }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-4 text-sm">
                                    <span class="text-slate-500">Produk</span>
                                    <span class="text-right font-bold text-slate-900">
                                        {{ $order->product_name }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-4 text-sm">
                                    <span class="text-slate-500">Metode Pembayaran</span>
                                    <span class="text-right font-bold text-slate-900">
                                        {{ $order->paymentGateway?->display_label ?: $order->paymentGateway?->name ?: '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-white p-5">
                            <h2 class="text-lg font-extrabold text-slate-950">
                                Data Akun
                            </h2>

                            @php
                                $customerInputs = is_array($order->customer_inputs)
                                    ? $order->customer_inputs
                                    : (json_decode($order->customer_inputs ?? '[]', true) ?: []);
                            @endphp

                            <div class="mt-5 space-y-4">
                                @forelse ($customerInputs as $label => $value)
                                    <div class="flex justify-between gap-4 text-sm">
                                        <span class="text-slate-500">{{ $label }}</span>
                                        <span class="text-right font-bold text-slate-900">
                                            {{ $value ?: '-' }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">
                                        Tidak ada data akun tersimpan.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-white p-5">
                            <h2 class="text-lg font-extrabold text-slate-950">
                                Data Pemesan
                            </h2>

                            <div class="mt-5 space-y-4">
                                <div class="flex justify-between gap-4 text-sm">
                                    <span class="text-slate-500">Nama</span>
                                    <span class="text-right font-bold text-slate-900">
                                        {{ $order->customer_name ?: '-' }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-4 text-sm">
                                    <span class="text-slate-500">Email</span>
                                    <span class="text-right font-bold text-slate-900">
                                        {{ $order->customer_email ?: '-' }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-4 text-sm">
                                    <span class="text-slate-500">WhatsApp</span>
                                    <span class="text-right font-bold text-slate-900">
                                        {{ $order->customer_phone ?: '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </main>

                    <aside class="space-y-5">
                        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                            <h2 class="text-lg font-extrabold text-slate-950">
                                Ringkasan Pembayaran
                            </h2>

                            <div class="mt-5 space-y-4">
                                <div class="flex justify-between gap-4 text-sm">
                                    <span class="text-slate-500">Harga Produk</span>
                                    <span class="font-bold text-slate-900">
                                        Rp {{ number_format($order->product_price, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="flex justify-between gap-4 text-sm">
                                    <span class="text-slate-500">Biaya Admin</span>
                                    <span class="font-bold text-slate-900">
                                        Rp {{ number_format($order->admin_fee, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="rounded-3xl bg-slate-950 p-5 text-white">
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="text-sm font-semibold text-slate-300">
                                            Total Pembayaran Pembayaran
                                        </span>

                                        <span class="text-xl font-black">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if ($order->status === 'pending' && $order->payment?->redirect_url)
                                <a
                                    href="{{ $order->payment->redirect_url }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="mt-5 inline-flex w-full items-center justify-center rounded-2xl bg-indigo-600 px-6 py-4 text-sm font-extrabold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-700"
                                >
                                    Bayar Sekarang
                                </a>
                            @endif
                        </div>

                        <div class="rounded-3xl border border-indigo-100 bg-indigo-50 p-5">
                            <p class="text-sm font-extrabold text-indigo-950">
                                Status Diperbarui Secara Otomatis
                            </p>

                            <p class="mt-2 text-sm leading-6 text-indigo-700">
                                Halaman ini akan diperbarui secara otomatis setiap beberapa detik. Status pembayaran akan berubah setelah sistem menerima notifikasi dari Midtrans.
                            </p>
                        </div>

                        @php
                            $siteSetting = class_exists(\App\Models\SiteSetting::class)
                                ? \App\Models\SiteSetting::current()
                                : null;

                            $whatsappNumber = $siteSetting?->customer_service_whatsapp;
                            $csEmail = $siteSetting?->customer_service_email;
                            $csHours = $siteSetting?->customer_service_working_hours;
                        @endphp

                        @if ($whatsappNumber || $csEmail || $csHours)
                            <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5">
                                <p class="text-sm font-extrabold text-amber-950">
                                    Perlu Bantuan?
                                </p>

                                <p class="mt-2 text-sm leading-6 text-amber-900">
                                    Jika pesanan Anda belum masuk atau terdapat kendala pembayaran, hubungi layanan pelanggan kami.
                                </p>

                                <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                                    @if ($whatsappNumber)
                                        <a
                                            href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode('Halo admin Igrenc, saya butuh bantuan terkait pesanan ' . $order->invoice_number) }}"
                                            target="_blank"
                                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-900 transition hover:bg-slate-100"
                                        >
                                            WhatsApp
                                        </a>
                                    @endif

                                    @if ($csEmail)
                                        <a
                                            href="mailto:{{ $csEmail }}"
                                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-900 transition hover:bg-slate-100"
                                        >
                                            Email
                                        </a>
                                    @endif
                                </div>

                                @if ($csHours)
                                    <p class="mt-3 text-xs text-amber-800">
                                        <strong>Jam Operasional:</strong> {{ $csHours }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </aside>
                </div>
            </div>
        </div>
    </section>
</div>
