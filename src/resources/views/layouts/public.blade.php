<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'IgrencGame' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-slate-950 text-white">
    <header class="border-b border-slate-800 bg-slate-950">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4">
            <a href="{{ route('home') }}" class="text-lg font-bold">
                IGRENCGAME
            </a>

            <nav class="hidden gap-6 text-sm text-slate-300 md:flex">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <a href="#" class="hover:text-white">Promo</a>
                <a href="#" class="hover:text-white">Bantuan</a>
            </nav>

            <a href="#" class="rounded-xl border border-cyan-400 px-4 py-2 text-sm font-semibold text-cyan-300">
                Cek Order
            </a>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="border-t border-slate-800">
        <div class="mx-auto max-w-7xl px-4 py-8 text-sm text-slate-400">
            © {{ date('Y') }} IgrencGame
        </div>
    </footer>

    @livewireScripts
</body>
</html>
