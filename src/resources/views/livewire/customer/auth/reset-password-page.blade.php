<div>
    <section class="min-h-[70vh] bg-slate-50 py-12">
        <div class="mx-auto max-w-md px-4">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-indigo-950/5 sm:p-8">
                <div class="text-center">
                    <p class="text-sm font-bold uppercase tracking-wide text-indigo-600">
                        Keamanan Akun
                    </p>

                    <h1 class="mt-2 text-2xl font-black text-slate-950">
                        Buat kata sandi baru
                    </h1>

                    <p class="mt-3 text-sm leading-6 text-slate-500">
                        Gunakan kata sandi baru yang kuat dan mudah Anda ingat.
                    </p>
                </div>

                <form wire:submit="resetPassword" class="mt-6 space-y-5">
                    <div>
                        <label for="email" class="mb-2 block text-sm font-bold text-slate-700">
                            Alamat email
                        </label>

                        <input
                            wire:model="email"
                            id="email"
                            type="email"
                            autocomplete="email"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                        >

                        @error('email')
                            <p class="mt-2 text-sm font-semibold text-rose-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-bold text-slate-700">
                            Kata sandi baru
                        </label>

                        <input
                            wire:model="password"
                            id="password"
                            type="password"
                            autocomplete="new-password"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                        >

                        <p class="mt-2 text-xs leading-5 text-slate-500">
                            Minimal 8 karakter serta mengandung huruf besar, huruf kecil, dan angka.
                        </p>

                        @error('password')
                            <p class="mt-2 text-sm font-semibold text-rose-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-bold text-slate-700">
                            Konfirmasi kata sandi
                        </label>

                        <input
                            wire:model="password_confirmation"
                            id="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                        >
                    </div>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="resetPassword"
                        class="inline-flex w-full items-center justify-center rounded-2xl bg-indigo-600 px-5 py-3.5 text-sm font-extrabold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <span wire:loading.remove wire:target="resetPassword">
                            Simpan kata sandi baru
                        </span>

                        <span wire:loading wire:target="resetPassword">
                            Menyimpan kata sandi...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>
