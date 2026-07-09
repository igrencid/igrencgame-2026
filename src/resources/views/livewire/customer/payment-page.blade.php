<div>
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-indigo-950/5 sm:p-8">
                <div class="text-center">
                    <p class="text-sm font-bold uppercase tracking-wide text-indigo-600">
                        Pembayaran
                    </p>

                    <h1 class="mt-2 text-2xl font-extrabold text-slate-950 sm:text-3xl">
                        Selesaikan Pembayaran
                    </h1>

                    <p class="mt-3 text-sm text-slate-500">
                        Invoice:
                        <span class="font-bold text-slate-900">
                            {{ $order->invoice_number }}
                        </span>
                    </p>
                </div>

                @if ($order->status === 'pending' && $order->expired_at)
                    <div
                        wire:poll.1s="refreshPaymentStatus"
                        class="mt-6 rounded-3xl border border-amber-200 bg-amber-50 p-5"
                    >
                        @php
                            $minutes = str_pad((string) floor($remainingSeconds / 60), 2, '0', STR_PAD_LEFT);
                            $seconds = str_pad((string) ($remainingSeconds % 60), 2, '0', STR_PAD_LEFT);
                        @endphp

                        @if ($remainingSeconds > 0)
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-extrabold text-amber-900">
                                        Batas Waktu Pembayaran
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-amber-700">
                                        Lakukan pembayaran sebelum batas waktu berakhir.
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white px-4 py-2 text-lg font-black text-amber-700 shadow-sm">
                                    {{ $minutes }}:{{ $seconds }}
                                </div>
                            </div>
                        @else
                            <p class="text-sm font-bold text-rose-600">
                                Batas waktu pembayaran telah berakhir. Pesanan otomatis dibatalkan.
                            </p>
                        @endif
                    </div>
                @endif

                @if ($order->status === 'failed')
                    <div class="mt-6 rounded-3xl border border-rose-200 bg-rose-50 p-5 text-sm font-semibold text-rose-700">
                        Pesanan gagal karena batas waktu pembayaran telah berakhir. Silakan buat pesanan baru.
                    </div>
                @endif

                @if ($order->status === 'paid')
                    <div class="mt-6 rounded-3xl border border-emerald-200 bg-emerald-50 p-5 text-sm font-semibold text-emerald-700">
                        Pembayaran berhasil diterima. Pesanan Anda sedang diproses oleh sistem.
                    </div>
                @endif

                <div class="mt-8 rounded-3xl bg-slate-50 p-5">
                    <div class="space-y-4">
                        <div>
                            <h2 class="text-base font-black text-slate-950">
                                Detail Pesanan
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Periksa kembali detail transaksi sebelum melanjutkan pembayaran.
                            </p>
                        </div>

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

                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-slate-500">Status Pembayaran</span>

                            @php
                                $statusColor = match ($order->status) {
                                    'paid' => 'bg-emerald-50 text-emerald-600',
                                    'failed' => 'bg-rose-50 text-rose-600',
                                    'expired' => 'bg-slate-100 text-slate-600',
                                    default => 'bg-amber-50 text-amber-600',
                                };

                                $statusLabel = match ($order->status) {
                                    'paid' => 'DIBAYAR',
                                    'failed' => 'GAGAL',
                                    'expired' => 'KEDALUWARSA',
                                    default => 'MENUNGGU PEMBAYARAN',
                                };
                            @endphp

                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <div class="border-t border-slate-200 pt-4">
                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-slate-500">Harga Produk</span>
                                <span class="font-bold text-slate-900">
                                    Rp {{ number_format((int) $order->product_price, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="mt-3 flex justify-between gap-4 text-sm">
                                <span class="text-slate-500">Biaya Admin</span>
                                <span class="font-bold text-slate-900">
                                    Rp {{ number_format((int) $order->admin_fee, 0, ',', '.') }}
                                </span>
                            </div>

                            @if ((int) ($order->discount_amount ?? 0) > 0)
                                <div class="mt-3 flex justify-between gap-4 text-sm">
                                    <span class="text-slate-500">Subtotal</span>
                                    <span class="font-bold text-slate-900">
                                        Rp {{ number_format((int) $order->product_price + (int) $order->admin_fee, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                                    <div class="flex justify-between gap-4 text-sm">
                                        <span class="font-bold text-emerald-700">
                                            Voucher Digunakan
                                        </span>

                                        <span class="font-extrabold text-emerald-700">
                                            {{ $order->voucher_code ?: '-' }}
                                        </span>
                                    </div>

                                    <div class="mt-2 flex justify-between gap-4 text-sm">
                                        <span class="text-emerald-700">
                                            Potongan Harga
                                        </span>

                                        <span class="font-extrabold text-emerald-700">
                                            - Rp {{ number_format((int) $order->discount_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="rounded-3xl bg-slate-950 p-5 text-white">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-sm font-semibold text-slate-300">
                                    Total Pembayaran Pembayaran
                                </span>

                                <span class="text-2xl font-black">
                                    Rp {{ number_format((int) $order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($order->status === 'pending' && $remainingSeconds > 0 && $order->payment?->redirect_url)
                    <a
                        href="{{ $order->payment->redirect_url }}"
                        target="_blank"
                        rel="noopener"
                        class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-indigo-600 px-6 py-4 text-sm font-extrabold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-700"
                    >
                        Bayar Sekarang melalui Midtrans
                    </a>
                @elseif ($order->status === 'pending' && $remainingSeconds > 0)
                    <div class="mt-6 rounded-2xl bg-rose-50 p-4 text-sm font-semibold text-rose-600">
                        Tautan pembayaran Midtrans belum tersedia. Periksa konfigurasi pembayaran atau buat pesanan ulang.
                    </div>
                @endif

                <a
                    href="{{ route('orders.show', $order->invoice_number) }}"
                    class="mt-4 inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-4 text-sm font-bold text-slate-700 transition hover:border-indigo-200 hover:text-indigo-600"
                >
                    Cek Status Pesanan
                </a>
            </div>
        </div>
    </section>
</div>
