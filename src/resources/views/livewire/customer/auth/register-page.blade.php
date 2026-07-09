<div class="min-h-screen bg-slate-50 py-12">
    <div class="mx-auto max-w-xl px-4">
        <div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm">
            <div class="mb-8">
                <h1 class="text-2xl font-black text-slate-950">
                    Daftar Akun Pelanggan
                </h1>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Buat akun untuk menyimpan riwayat pesanan dan menikmati proses checkout yang lebih praktis.
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

            <form wire:submit.prevent="register" class="space-y-5">
                <div>
                    <label class="text-sm font-bold text-slate-700">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        wire:model="name"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Nama lengkap Anda"
                        autocomplete="name"
                    >

                    @error('name')
                        <p class="mt-2 text-sm font-bold" style="color: #dc2626;">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-bold text-slate-700">
                        Email
                    </label>

                    <input
                        type="email"
                        wire:model="email"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
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
                        Nomor WhatsApp
                    </label>

                    <input
                        type="text"
                        wire:model="phone"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="08xxxxxxxxxx"
                        autocomplete="tel"
                    >

                    @error('phone')
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
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Minimal 8 karakter"
                        autocomplete="new-password"
                    >

                    @error('password')
                        <p class="mt-2 text-sm font-bold" style="color: #dc2626;">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-bold text-slate-700">
                        Konfirmasi Kata Sandi
                    </label>

                    <input
                        type="password"
                        wire:model="password_confirmation"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Ulangi kata sandi"
                        autocomplete="new-password"
                    >

                    @error('password_confirmation')
                        <p class="mt-2 text-sm font-bold" style="color: #dc2626;">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <label class="flex items-start gap-3 text-sm font-semibold text-slate-600">
                    <input
                        type="checkbox"
                        wire:model="accepts_marketing"
                        class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >

                    <span>Saya bersedia menerima informasi promo dan pembaruan dari Igrenc.</span>
                </label>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="register"
                    class="inline-flex w-full items-center justify-center rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-70"
                >
                    <span wire:loading.remove wire:target="register">
                        Daftar Sekarang
                    </span>

                    <span wire:loading wire:target="register">
                        Memproses...
                    </span>
                </button>
            </form>

            <div class="my-5 flex items-center gap-4">
                <div class="h-px flex-1 bg-slate-200"></div>
                <span class="text-xs font-bold uppercase tracking-wide text-slate-400">atau</span>
                <div class="h-px flex-1 bg-slate-200"></div>
            </div>

            <x-customer.google-auth-button label="Daftar dengan Google" />

            <p class="mt-6 text-center text-sm text-slate-500">
                Sudah memiliki akun?
                <a href="{{ route('customer.login') }}" class="font-bold text-indigo-600 hover:text-indigo-700">
                    Masuk
                </a>
            </p>
        </div>
    </div>
</div>
