<div class="min-h-screen bg-slate-50 py-12">
    <div class="mx-auto max-w-6xl px-4">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Riwayat Pesanan</h1>
                <p class="mt-2 text-sm text-slate-500">
                    Semua pesanan yang dibuat saat akun kamu login akan tampil di sini.
                </p>
            </div>

            <a
                href="{{ route('customer.account') }}"
                class="inline-flex rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-100"
            >
                Kembali ke Akun
            </a>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Invoice</th>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Game</th>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Produk</th>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Total Pembayaran</th>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Status</th>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Tanggal</th>
                            <th class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wide text-slate-500">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($orders as $order)
                            <tr>
                                <td class="px-5 py-4 text-sm font-bold text-slate-900">
                                    {{ $order->invoice_number }}
                                </td>
                                <td class="px-5 py-4 text-sm text-slate-600">
                                    {{ $order->game_name }}
                                </td>
                                <td class="px-5 py-4 text-sm text-slate-600">
                                    {{ $order->product_name }}
                                </td>
                                <td class="px-5 py-4 text-sm font-semibold text-slate-900">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-bold
                                        @class([
                                            'bg-amber-100 text-amber-700' => $order->status === 'pending',
                                            'bg-emerald-100 text-emerald-700' => $order->status === 'paid',
                                            'bg-red-100 text-red-700' => in_array($order->status, ['failed', 'expired', 'cancelled']),
                                            'bg-slate-100 text-slate-700' => ! in_array($order->status, ['pending', 'paid', 'failed', 'expired', 'cancelled']),
                                        ])
                                    ">
                                        {{ strtoupper($order->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm text-slate-500">
                                    {{ $order->created_at?->format('d M Y H:i') }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a
                                        href="{{ route('orders.show', $order->invoice_number) }}"
                                        class="text-sm font-bold text-indigo-600 hover:text-indigo-700"
                                    >
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-sm text-slate-500">
                                    Belum ada pesanan di akun ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
