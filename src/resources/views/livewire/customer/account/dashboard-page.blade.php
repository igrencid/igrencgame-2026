<div class="min-h-screen bg-slate-50 py-12">
    <div class="mx-auto max-w-5xl px-4">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Akun Saya</h1>
            <p class="mt-2 text-sm text-slate-500">
                Halo, {{ $customer->name }}. Di sini kamu bisa melihat ringkasan akun dan pesanan.
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Total Pembayaran Pesanan</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalOrders }}</p>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Pesanan Paid</p>
                <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $paidOrders }}</p>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Pesanan Pending</p>
                <p class="mt-2 text-3xl font-bold text-amber-600">{{ $pendingOrders }}</p>
            </div>
        </div>

        <div class="mt-6 rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">Profil</h2>

            <div class="mt-4 grid gap-4 md:grid-cols-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nama</p>
                    <p class="mt-1 text-sm font-semibold text-slate-800">{{ $customer->name }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Email</p>
                    <p class="mt-1 text-sm font-semibold text-slate-800">{{ $customer->email }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">WhatsApp</p>
                    <p class="mt-1 text-sm font-semibold text-slate-800">{{ $customer->phone ?: '-' }}</p>
                </div>
            </div>

            <div class="mt-6">
                <a
                    href="{{ route('customer.orders') }}"
                    class="inline-flex rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white hover:bg-indigo-700"
                >
                    Lihat Riwayat Pesanan
                </a>
            </div>
        </div>
    </div>
</div>
