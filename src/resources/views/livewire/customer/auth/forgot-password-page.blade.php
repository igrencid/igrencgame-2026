<div>
    <section class="min-h-[70vh] bg-slate-50 py-12">
        <div class="mx-auto max-w-md px-4">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-indigo-950/5 sm:p-8">
                <div class="text-center">
                    <p class="text-sm font-bold uppercase tracking-wide text-indigo-600">
                        Pemulihan Akun
                    </p>

                    <h1 class="mt-2 text-2xl font-black text-slate-950">
                        Lupa kata sandi?
                    </h1>

                    <p class="mt-3 text-sm leading-6 text-slate-500">
                        Masukkan alamat email akun Anda. Kami akan mengirimkan tautan untuk membuat kata sandi baru.
                    </p>
                </div>

                @if ($linkSent)
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm leading-6 text-emerald-700">
                        Jika alamat email tersebut terdaftar, tautan pengaturan ulang kata sandi akan segera dikirim.
                    </div>
                @endif

                <form wire:submit="sendResetLink" class="mt-6 space-y-5">
                    <div>
                        <label for="email" class="mb-2 block text-sm font-bold text-slate-700">
                            Alamat email
                        </label>

                        <input
                            wire:model.blur="email"
                            id="email"
                            type="email"
                            autocomplete="email"
                            placeholder="nama@contoh.com"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                        >

                        @error('email')
                            <p class="mt-2 text-sm font-semibold text-rose-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="sendResetLink"
                        class="inline-flex w-full items-center justify-center rounded-2xl bg-indigo-600 px-5 py-3.5 text-sm font-extrabold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <span wire:loading.remove wire:target="sendResetLink">
                            Kirim tautan pengaturan ulang
                        </span>

                        <span wire:loading wire:target="sendResetLink">
                            Mengirim tautan...
                        </span>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('customer.login') }}" wire:navigate class="text-sm font-bold text-indigo-600 hover:text-indigo-700">
                        Kembali ke halaman masuk
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
