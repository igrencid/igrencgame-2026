<div
    wire:ignore
    x-data="{
        visible: false,
        timer: null,
        previousOverflow: '',

        init() {
            let introSeen = false;

            try {
                introSeen =
                    sessionStorage.getItem('igrenc_intro_seen') === '1';
            } catch (error) {
                introSeen = false;
            }

            const reduceMotion = window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches;

            if (introSeen || reduceMotion) {
                return;
            }

            this.visible = true;
            this.previousOverflow = document.body.style.overflow;
            document.body.style.overflow = 'hidden';

            this.timer = window.setTimeout(() => {
                this.finish();
            }, 5000);
        },

        finish() {
            if (! this.visible) {
                return;
            }

            window.clearTimeout(this.timer);

            try {
                sessionStorage.setItem('igrenc_intro_seen', '1');
            } catch (error) {
                // Intro tetap ditutup jika sessionStorage tidak tersedia.
            }

            this.visible = false;
            document.body.style.overflow = this.previousOverflow;
        },

        destroy() {
            window.clearTimeout(this.timer);
            document.body.style.overflow = this.previousOverflow;
        }
    }"
    x-show="visible"
    x-cloak
    x-transition:enter="transition duration-300 ease-out"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition duration-500 ease-in"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="finish()"
    class="fixed inset-0 z-[9999] flex min-h-screen items-center justify-center overflow-hidden bg-slate-950 px-6"
    role="dialog"
    aria-modal="true"
    aria-label="Memuat {{ \App\Support\Branding::name() }}"
>
    {{-- Background --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -left-40 -top-40 h-[460px] w-[460px] rounded-full bg-indigo-600/25 blur-3xl"></div>

        <div class="absolute -bottom-40 -right-40 h-[460px] w-[460px] rounded-full bg-violet-600/20 blur-3xl"></div>

        <div
            class="absolute inset-0 opacity-[0.04]"
            style="
                background-image:
                    linear-gradient(to right, #ffffff 1px, transparent 1px),
                    linear-gradient(to bottom, #ffffff 1px, transparent 1px);
                background-size: 48px 48px;
            "
        ></div>
    </div>

    {{-- Skip --}}
    <button
        type="button"
        @click="finish()"
        class="absolute right-5 top-5 rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold text-slate-300 backdrop-blur transition hover:border-white/20 hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 sm:right-8 sm:top-8"
    >
        Lewati
    </button>

    {{-- Content --}}
    <div class="relative w-full max-w-md text-center">
        <div class="igrenc-intro-logo mx-auto flex w-fit items-center justify-center rounded-[2rem] border border-white/10 bg-white p-5 shadow-2xl shadow-black/30">
            <x-brand-logo
                variant="intro"
                :show-text="false"
            />
        </div>

        <div class="igrenc-intro-content">
            <p class="mt-7 text-xs font-bold uppercase tracking-[0.28em] text-indigo-300">
                {{ \App\Support\Branding::tagline() }}
            </p>

            <h1 class="mt-3 text-5xl font-black tracking-tight sm:text-6xl">
                <span class="bg-gradient-to-r from-indigo-400 via-violet-400 to-fuchsia-400 bg-clip-text text-transparent">
                    {{ \App\Support\Branding::name() }}
                </span>
            </h1>

            <p class="mx-auto mt-4 max-w-sm text-sm leading-6 text-slate-400">
                Menyiapkan layanan top up game untuk Anda.
            </p>
        </div>

        <div class="mx-auto mt-9 h-1 w-48 overflow-hidden rounded-full bg-white/10">
            <div class="igrenc-intro-progress h-full rounded-full bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500"></div>
        </div>

        <p class="mt-4 text-xs font-medium text-slate-500">
            Mohon tunggu sebentar
        </p>
    </div>
</div>

@once
    <style>
        [x-cloak] {
            display: none !important;
        }

        .igrenc-intro-logo {
            animation: igrenc-logo-in 700ms cubic-bezier(.2, .8, .2, 1) both;
        }

        .igrenc-intro-content {
            animation: igrenc-content-in 700ms 160ms cubic-bezier(.2, .8, .2, 1) both;
        }

        .igrenc-intro-progress {
            transform-origin: left;
            animation: igrenc-progress 4500ms 250ms cubic-bezier(.4, 0, .2, 1) both;
        }

        @keyframes igrenc-logo-in {
            from {
                opacity: 0;
                transform: translateY(18px) scale(.82);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes igrenc-content-in {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes igrenc-progress {
            from {
                transform: scaleX(0);
            }

            to {
                transform: scaleX(1);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .igrenc-intro-logo,
            .igrenc-intro-content,
            .igrenc-intro-progress {
                animation: none !important;
            }
        }
    </style>
@endonce
