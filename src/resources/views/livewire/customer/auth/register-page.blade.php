<div class="min-h-screen bg-slate-50 py-12">
    <div class="mx-auto max-w-xl px-4">
        <div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900">Daftar Akun Pelanggan</h1>
                <p class="mt-2 text-sm text-slate-500">
                    Buat akun untuk menyimpan riwayat pesanan. Checkout tetap bisa dilakukan tanpa akun.
                </p>
            </div>

            @if (session('error'))
                <div class="mb-5 rounded-2xl bg-rose-50 p-4 text-sm font-semibold text-rose-600">
                    {{ session('error') }}
                </div>
            @endif

            <x-customer.google-auth-button label="Daftar dengan Google" />

            @if (filled(config('services.google.client_id')) && filled(config('services.google.client_secret')) && filled(config('services.google.redirect')))
                <div class="my-5 flex items-center gap-4">
                    <div class="h-px flex-1 bg-slate-200"></div>
                    <span class="text-xs font-bold uppercase tracking-wide text-slate-400">atau</span>
                    <div class="h-px flex-1 bg-slate-200"></div>
                </div>
            @endif

            <form wire:submit.prevent="register" class="space-y-5">
                <div>
                    <label class="text-sm font-semibold text-slate-700">Nama</label>
                    <input
                        type="text"
                        wire:model="name"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Nama lengkap"
                    >
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Email</label>
                    <input
                        type="email"
                        wire:model="email"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="email@example.com"
                    >
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Nomor WhatsApp</label>
                    <input
                        type="text"
                        wire:model="phone"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="08xxxxxxxxxx"
                    >
                    @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Password</label>
                    <input
                        type="password"
                        wire:model="password"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Minimal 8 karakter"
                    >
                    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                    <input
                        type="password"
                        wire:model="password_confirmation"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Ulangi password"
                    >
                </div>

                <label class="flex items-start gap-3 text-sm text-slate-600">
                    <input
                        type="checkbox"
                        wire:model="accepts_marketing"
                        class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >
                    <span>Saya bersedia menerima informasi promo dan pembaruan dari IgrencGame.</span>
                </label>

                <button
                    type="submit"
                    class="w-full rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white hover:bg-indigo-700"
                >
                    Daftar Sekarang
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500">
                Sudah punya akun?
                <a href="{{ route('customer.login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">
                    Masuk
                </a>
            </p>
        </div>
    </div>
</div>
