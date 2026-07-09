<div>
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <a
                href="{{ route('games.show', $game->slug) }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-indigo-600"
            >
                ← Kembali ke {{ $game->name }}
            </a>

            <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_380px]">
                <main class="space-y-6">
                    <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 overflow-hidden rounded-2xl bg-slate-100">
                                @if ($game->image_path)
                                    <img
                                        src="{{ asset('storage/' . $game->image_path) }}"
                                        alt="{{ $game->name }}"
                                        class="h-full w-full object-cover"
                                    >
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-2xl font-black text-indigo-600">
                                        {{ strtoupper(substr($game->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-indigo-600">
                                    Checkout
                                </p>

                                <h1 class="mt-1 text-2xl font-extrabold text-slate-950">
                                    {{ $game->name }}
                                </h1>

                                <p class="mt-1 text-sm text-slate-500">
                                    Lengkapi data akun dan pilih metode pembayaran.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-bold uppercase tracking-wide text-indigo-600">
                            1. Data Akun
                        </p>

                        <h2 class="mt-1 text-xl font-extrabold text-slate-950">
                            Masukkan data tujuan top up
                        </h2>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            @foreach ($game->inputFields as $field)
                                <div>
                                    <label class="text-sm font-bold text-slate-700">
                                        {{ $field->label }}

                                        @if ($field->is_required)
                                            <span class="text-rose-500">*</span>
                                        @endif
                                    </label>

                                    <input
                                        type="text"
                                        wire:model.defer="customerInputs.{{ $field->id }}"
                                        placeholder="{{ $field->placeholder ?: $field->label }}"
                                        wire:loading.attr="disabled"
                                        wire:target="placeOrder"
                                        class="mt-2 w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-slate-100"
                                    >

                                    @error('customerInputs.' . $field->id)
                                        <p class="mt-2 text-xs font-semibold text-rose-600">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-bold uppercase tracking-wide text-indigo-600">
                            2. Data Pemesan
                        </p>

                        <h2 class="mt-1 text-xl font-extrabold text-slate-950">
                            Informasi kontak pesanan
                        </h2>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-bold text-slate-700">
                                    Nama
                                </label>

                                <input
                                    type="text"
                                    wire:model.defer="customerName"
                                    placeholder="Nama lengkap Anda"
                                    wire:loading.attr="disabled"
                                    wire:target="placeOrder"
                                    class="mt-2 w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-slate-100"
                                >

                                @error('customerName')
                                    <p class="mt-2 text-xs font-semibold text-rose-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="text-sm font-bold text-slate-700">
                                    WhatsApp
                                </label>

                                <input
                                    type="text"
                                    wire:model.defer="customerPhone"
                                    placeholder="08xxxxxxxxxx"
                                    wire:loading.attr="disabled"
                                    wire:target="placeOrder"
                                    class="mt-2 w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-slate-100"
                                >

                                @error('customerPhone')
                                    <p class="mt-2 text-xs font-semibold text-rose-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-bold text-slate-700">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    wire:model.defer="customerEmail"
                                    placeholder="username@email.com"
                                    wire:loading.attr="disabled"
                                    wire:target="placeOrder"
                                    class="mt-2 w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm font-medium shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-slate-100"
                                >

                                @error('customerEmail')
                                    <p class="mt-2 text-xs font-semibold text-rose-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-bold uppercase tracking-wide text-indigo-600">
                            3. Pilih Produk
                        </p>

                        <h2 class="mt-1 text-xl font-extrabold text-slate-950">
                            Produk tersedia
                        </h2>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            @foreach ($products as $product)
                                <button
                                    type="button"
                                    wire:click="selectProduct({{ $product->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="placeOrder"
                                    class="rounded-3xl border p-5 text-left transition hover:-translate-y-1 hover:shadow-lg disabled:cursor-not-allowed disabled:opacity-60
                                        {{ $productId === $product->id
                                            ? 'border-indigo-500 bg-indigo-50 shadow-indigo-950/10'
                                            : 'border-slate-200 bg-white' }}"
                                >
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-extrabold text-slate-950">
                                                {{ $product->name }}
                                            </p>

                                            @if ($product->description)
                                                <p class="mt-1 line-clamp-2 text-xs text-slate-500">
                                                    {{ $product->description }}
                                                </p>
                                            @endif
                                        </div>

                                        @if ($productId === $product->id)
                                            <span class="rounded-full bg-indigo-600 px-2 py-1 text-[10px] font-bold text-white">
                                                Dipilih
                                            </span>
                                        @endif
                                    </div>

                                    <p class="mt-4 text-lg font-black text-slate-950">
                                        Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                    </p>
                                </button>
                            @endforeach
                        </div>

                        @error('productId')
                            <p class="mt-3 text-xs font-semibold text-rose-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-bold uppercase tracking-wide text-indigo-600">
                            4. Metode Pembayaran
                        </p>

                        <h2 class="mt-1 text-xl font-extrabold text-slate-950">
                            Pilih metode pembayaran
                        </h2>

                        <div class="mt-5 grid gap-3">
                            @forelse ($paymentGateways as $gateway)
                                <button
                                    type="button"
                                    wire:click="selectPaymentGateway({{ $gateway->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="placeOrder"
                                    class="rounded-3xl border p-5 text-left transition hover:-translate-y-1 hover:shadow-lg disabled:cursor-not-allowed disabled:opacity-60
                                        {{ $paymentGatewayId === $gateway->id
                                            ? 'border-indigo-500 bg-indigo-50 shadow-indigo-950/10'
                                            : 'border-slate-200 bg-white' }}"
                                >
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-extrabold text-slate-950">
                                                {{ $gateway->display_label ?: $gateway->name }}
                                            </p>

                                            <p class="mt-1 text-xs font-medium text-slate-500">
                                                Provider: {{ ucfirst($gateway->provider) }}
                                            </p>
                                        </div>

                                        @if ($paymentGatewayId === $gateway->id)
                                            <span class="rounded-full bg-indigo-600 px-3 py-1 text-xs font-bold text-white">
                                                Dipilih
                                            </span>
                                        @endif
                                    </div>
                                </button>
                            @empty
                                <div class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                                    <p class="text-sm font-bold text-slate-700">
                                        Metode pembayaran belum tersedia.
                                    </p>

                                    <p class="mt-2 text-sm text-slate-500">
                                        Aktifkan payment gateway dari admin panel.
                                    </p>
                                </div>
                            @endforelse
                        </div>

                        @error('paymentGatewayId')
                            <p class="mt-3 text-xs font-semibold text-rose-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </main>

                <aside class="lg:sticky lg:top-24 lg:self-start">
                    @php
                        $siteSetting = class_exists(\App\Models\SiteSetting::class)
                            ? \App\Models\SiteSetting::current()
                            : null;

                        $whatsappNumber = $siteSetting?->customer_service_whatsapp;
                        $csEmail = $siteSetting?->customer_service_email;
                    @endphp

                    @if ($whatsappNumber || $csEmail)
                        <div class="mb-6 rounded-[2rem] border border-blue-200 bg-blue-50 p-6 shadow-sm">
                            <p class="text-sm font-bold text-blue-950">
                                Perlu bantuan mengisi data akun?
                            </p>

                            <p class="mt-2 text-sm leading-5 text-blue-900">
                                Hubungi layanan pelanggan kami untuk mendapatkan bantuan.
                            </p>

                            <div class="mt-4 flex flex-col gap-2">
                                @if ($whatsappNumber)
                                    <a
                                        href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode('Halo, saya butuh bantuan mengisi data akun untuk top up game.') }}"
                                        target="_blank"
                                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-900 transition hover:bg-slate-100"
                                    >
                                        Hubungi Layanan Pelanggan
                                    </a>
                                @endif

                                @if ($csEmail && ! $whatsappNumber)
                                    <a
                                        href="mailto:{{ $csEmail }}"
                                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-900 transition hover:bg-slate-100"
                                    >
                                        Email CS
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-indigo-950/5">
                        <p class="text-sm font-bold uppercase tracking-wide text-indigo-600">
                            Ringkasan Pesanan
                        </p>

                        <h2 class="mt-1 text-xl font-extrabold text-slate-950">
                            Detail Transaksi
                        </h2>

                        <div class="mt-5 space-y-4">
                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-slate-500">Game</span>
                                <span class="text-right font-bold text-slate-900">
                                    {{ $game->name }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-slate-500">Produk</span>
                                <span class="text-right font-bold text-slate-900">
                                    {{ $selectedProduct?->name ?: '-' }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-slate-500">Metode Pembayaran</span>
                                <span class="text-right font-bold text-slate-900">
                                    {{ $selectedGateway?->display_label ?: $selectedGateway?->name ?: '-' }}
                                </span>
                            </div>

                            <div class="border-t border-slate-200 pt-4">
                                <div class="flex justify-between gap-4 text-sm">
                                    <span class="text-slate-500">Harga Produk</span>
                                    <span class="font-bold text-slate-900">
                                        Rp {{ number_format($productPrice, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="mt-3 flex justify-between gap-4 text-sm">
                                    <span class="text-slate-500">Biaya Admin</span>
                                    <span class="font-bold text-slate-900">
                                        Rp {{ number_format($adminFee, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <div class="border-t border-slate-200 pt-4">
                                <p class="text-sm font-bold text-slate-900">
                                    Kode Voucher
                                </p>

                                @guest('customer')
                                    <div class="mt-3 rounded-2xl bg-amber-50 p-4 text-sm font-semibold text-amber-700">
                                        Masuk ke akun untuk menggunakan kode voucher.

                                        <a href="{{ route('customer.login') }}" class="font-extrabold underline">
                                            Masuk
                                        </a>
                                    </div>
                                @else
                                    @if ($appliedVoucherCode)
                                        <div class="mt-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="text-sm font-extrabold text-emerald-700">
                                                        {{ $appliedVoucherCode }} diterapkan
                                                    </p>

                                                    <p class="mt-1 text-xs font-semibold text-emerald-700">
                                                        Diskon Rp {{ number_format($discountAmount, 0, ',', '.') }}
                                                    </p>
                                                </div>

                                                <button
                                                    type="button"
                                                    wire:click="removeVoucher"
                                                    wire:loading.attr="disabled"
                                                    wire:target="removeVoucher,placeOrder"
                                                    class="text-xs font-extrabold text-emerald-700 underline disabled:opacity-60"
                                                >
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-3 flex gap-2">
                                            <input
                                                type="text"
                                                wire:model.defer="voucherCode"
                                                placeholder="Contoh: MEMBER10"
                                                wire:loading.attr="disabled"
                                                wire:target="applyVoucher,placeOrder"
                                                class="min-w-0 flex-1 rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm font-bold uppercase shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-slate-100"
                                            >

                                            <button
                                                type="button"
                                                wire:click="applyVoucher"
                                                wire:loading.attr="disabled"
                                                wire:target="applyVoucher,placeOrder"
                                                class="rounded-2xl bg-slate-950 px-4 py-3 text-sm font-extrabold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60"
                                            >
                                                Pakai
                                            </button>
                                        </div>
                                    @endif

                                    @error('voucherCode')
                                        <p class="mt-2 text-xs font-semibold text-rose-600">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                @endguest

                                @if ($discountAmount > 0)
                                    <div class="mt-4 flex justify-between gap-4 text-sm">
                                        <span class="text-slate-500">Diskon voucher</span>
                                        <span class="font-bold text-emerald-600">
                                            - Rp {{ number_format($discountAmount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="rounded-3xl bg-slate-950 p-5 text-white">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-sm font-semibold text-slate-300">
                                        Total Pembayaran Pembayaran
                                    </span>

                                    <span class="text-xl font-black">
                                        Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @error('checkout')
                            <div class="mt-4 rounded-2xl bg-rose-50 p-4 text-sm font-semibold text-rose-600">
                                {{ $message }}
                            </div>
                        @enderror

                        <button
                            type="button"
                            wire:click="placeOrder"
                            wire:loading.attr="disabled"
                            wire:target="placeOrder"
                            @disabled($isSubmitting || ! $selectedProduct || ! $selectedGateway)
                            class="mt-5 inline-flex w-full items-center justify-center rounded-2xl bg-indigo-600 px-6 py-4 text-sm font-extrabold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-70"
                        >
                            <span wire:loading.remove wire:target="placeOrder">
                                Buat Pesanan
                            </span>

                            <span wire:loading wire:target="placeOrder">
                                Memproses pesanan...
                            </span>
                        </button>

                        <p class="mt-4 text-center text-xs leading-5 text-slate-500">
                            Dengan membuat pesanan, transaksi akan dibuat dan Anda akan diarahkan ke halaman pembayaran.
                        </p>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>
