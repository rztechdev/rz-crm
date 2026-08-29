<x-app-layout>
    <div class="py-6 sm:py-8 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-50 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            <x-flash />

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">Pembayaran &amp; Invoicing</h1>
                    <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-1">Daftar transaksi DP proyek, pelunasan akhir, dan pembayaran maintenance klien.</p>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 font-mono">Total Pembayaran Lunas</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1">Rp {{ number_format($totalLunas, 0, ',', '.') }}</h3>
                    </div>
                    <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400">
                        <span class="material-symbols-outlined text-[28px]">verified</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 font-mono">Total Tagihan Pending</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-amber-500 mt-1">Rp {{ number_format($totalPending, 0, ',', '.') }}</h3>
                    </div>
                    <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-500">
                        <span class="material-symbols-outlined text-[28px]">pending</span>
                    </div>
                </div>
            </div>

            <!-- Payments Table Card -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-zinc-50/70 dark:bg-zinc-950/50 border-b border-zinc-200/80 dark:border-zinc-800 text-[11px] font-bold font-mono uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                            <tr>
                                <th class="px-6 py-4">Proyek &amp; Klien</th>
                                <th class="px-6 py-4">Jenis</th>
                                <th class="px-6 py-4">Nominal</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Catatan</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/60 text-xs">
                            @forelse($payments as $payment)
                                <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('projects.show', $payment->project) }}" class="font-bold text-zinc-900 dark:text-white hover:text-emerald-600">
                                            {{ $payment->project->nama_project }}
                                        </a>
                                        <p class="text-zinc-400 text-[11px] mt-0.5">{{ $payment->project->lead->nama_usaha }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-semibold text-[11px]">
                                            {{ $payment->jenis_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono font-bold text-zinc-900 dark:text-white">
                                        Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $payment->status === 'lunas' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400' }}">
                                            {{ strtoupper($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-zinc-500">
                                        {{ $payment->tanggal ? $payment->tanggal->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-zinc-500 truncate max-w-xs">
                                        {{ $payment->catatan ?: '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            @if($payment->status === 'pending')
                                                <form method="POST" action="{{ route('payments.update-status', $payment) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="lunas">
                                                    <button type="submit" class="px-2.5 py-1 rounded bg-emerald-600 text-white text-[11px] font-bold hover:bg-emerald-700">
                                                        Tandai Lunas
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('payments.destroy', $payment) }}" onsubmit="return confirm('Hapus pembayaran ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 rounded text-zinc-400 hover:text-rose-600">
                                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-zinc-400 text-xs">
                                        Belum ada riwayat transaksi pembayaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($payments->hasPages())
                    <div class="px-6 py-4 border-t border-zinc-100 dark:border-zinc-800">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
