<div class="min-h-screen bg-slate-50 py-12">
    <div class="mx-auto max-w-xl px-4">
        <div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm">
            <div class="mb-8">
                <h1 class="text-2xl font-black text-slate-950">
                    Masuk ke Akun Pelanggan
                </h1>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Kelola riwayat pesanan, pantau status transaksi, dan lanjutkan checkout dengan lebih cepat.
                </p>
            </div>

            @if (session('error'))
                <div
                    class="mb-5 rounded-2xl border p-4 text-sm font-bold"
                    style="border-color: #fecaca; background-color: #fef2f2; color: #dc2626;"
                >
                    {{ session('error') }}
                </div>
            @endif

            @error('auth')
                <div
                    class="mb-5 rounded-2xl border p-4 text-sm font-bold"
                    style="border-color: #fecaca; background-color: #fef2f2; color: #dc2626;"
                >
                    {{ $message }}
                </div>
            @enderror

            <form wire:submit.prevent="login" class="space-y-5">
                <div>
                    <label class="text-sm font-bold text-slate-700">
                        Email
                    </label>

                    <input
                        type="email"
                        wire:model="email"
                        class="mt-2 w-full rounded-2xl border px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="username@email.com"
                        autocomplete="email"
                    >

                    @error('email')
                        <p class="mt-2 text-sm font-bold" style="color: #dc2626;">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-bold text-slate-700">
                        Kata Sandi
                    </label>

                    <input
                        type="password"
                        wire:model="password"
                        class="mt-2 w-full rounded-2xl border px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Masukkan kata sandi akun Anda"
                        autocomplete="current-password"
                    >

                    @error('password')
                        <p class="mt-2 text-sm font-bold" style="color: #dc2626;">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <label class="flex items-center gap-3 text-sm font-semibold text-slate-600">
                    <input
                        type="checkbox"
                        wire:model="remember"
                        class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >

                    <span>Ingat saya</span>
                </label>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="login"
                    class="inline-flex w-full items-center justify-center rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-70"
                >
                    <span wire:loading.remove wire:target="login">
                        Masuk
                    </span>

                    <span wire:loading wire:target="login">
                        Memproses...
                    </span>
                </button>
            </form>

            <div class="my-5 flex items-center gap-4">
                <div class="h-px flex-1 bg-slate-200"></div>
                <span class="text-xs font-bold uppercase tracking-wide text-slate-400">atau</span>
                <div class="h-px flex-1 bg-slate-200"></div>
            </div>

            <x-customer.google-auth-button label="Masuk dengan Google" />

            <p class="mt-6 text-center text-sm text-slate-500">
                Belum memiliki akun?
                <a href="{{ route('customer.register') }}" class="font-bold text-indigo-600 hover:text-indigo-700">
                    Daftar sekarang
                </a>
            </p>
        </div>
    </div>
</div>
