<div class="bg-slate-50 py-12 sm:py-16">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <nav class="text-sm font-semibold text-slate-500" aria-label="Breadcrumb">
            <ol class="flex items-center gap-2">
                <li>
                    <a href="{{ route('home') }}" class="transition hover:text-indigo-600">Beranda</a>
                </li>

                <li aria-hidden="true">/</li>

                <li class="text-slate-900">Cek Pesanan</li>
            </ol>
        </nav>

        <div class="mt-6 max-w-2xl">
            <h1 class="text-4xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">
                Cek Pesanan
            </h1>

            <p class="mt-4 text-base leading-7 text-slate-600 sm:text-lg">
                Masukkan nomor invoice untuk melihat status pesanan Anda secara cepat dan aman.
            </p>
        </div>

        <div class="mt-10 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm sm:p-10">
            <form wire:submit.prevent="checkOrder" class="grid gap-6">
                <div>
                    <label for="invoice" class="text-sm font-semibold text-slate-700">Nomor Invoice</label>
                    <input
                        id="invoice"
                        type="text"
                        wire:model.defer="invoice"
                        placeholder="Contoh: INV-20260711-001"
                        aria-describedby="invoice-helper"
                        class="mt-2 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                    >
                    <p id="invoice-helper" class="mt-2 text-sm text-slate-500">
                        Nomor invoice dapat ditemukan pada halaman pembayaran atau email transaksi.
                    </p>

                    @error('invoice')
                        <p class="mt-2 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            {{ $message }}
                        </p>
                    @enderror

                    @if ($orderNotFound)
                        <p class="mt-2 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            Pesanan tidak ditemukan. Pastikan nomor invoice yang Anda masukkan sudah benar.
                        </p>
                    @endif
                </div>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex min-h-[44px] items-center justify-center rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-70"
                >
                    <span wire:loading.remove>Periksa Status</span>
                    <span wire:loading>Memeriksa...</span>
                </button>
            </form>
        </div>
    </div>
</div>
