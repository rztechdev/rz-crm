<x-app-layout>
    <div class="py-6 sm:py-8 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-50 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            <x-flash />

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">Riwayat Pesan Klien</h1>
                    <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-1">Seluruh riwayat obrolan, notifikasi otomatis, dan pesan masuk dari klien RZ Digital Creative.</p>
                </div>
            </div>

            <!-- Filter Row -->
            <form method="GET" action="{{ route('messages.index') }}" class="bg-white dark:bg-zinc-900 p-4 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm flex flex-col md:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-[20px]">search</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari isi pesan, nomor telepon, nama klien..." 
                           class="w-full pl-10 pr-4 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <select name="arah" onchange="this.form.submit()" 
                            class="w-full md:w-44 px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-700 dark:text-zinc-300 focus:ring-emerald-500">
                        <option value="">Semua Arah</option>
                        <option value="masuk" {{ request('arah') == 'masuk' ? 'selected' : '' }}>Pesan Masuk (Klien)</option>
                        <option value="keluar" {{ request('arah') == 'keluar' ? 'selected' : '' }}>Pesan Keluar (RZ)</option>
                    </select>

                    <select name="tipe" onchange="this.form.submit()" 
                            class="w-full md:w-44 px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-700 dark:text-zinc-300 focus:ring-emerald-500">
                        <option value="">Semua Tipe Pesan</option>
                        <option value="invoice_dp" {{ request('tipe') == 'invoice_dp' ? 'selected' : '' }}>Invoice DP</option>
                        <option value="project_selesai" {{ request('tipe') == 'project_selesai' ? 'selected' : '' }}>Project Selesai</option>
                        <option value="reminder_maintenance" {{ request('tipe') == 'reminder_maintenance' ? 'selected' : '' }}>Reminder Maintenance</option>
                        <option value="manual" {{ request('tipe') == 'manual' ? 'selected' : '' }}>Manual Chat</option>
                        <option value="webhook_masuk" {{ request('tipe') == 'webhook_masuk' ? 'selected' : '' }}>Balasan Webhook</option>
                    </select>
                </div>
            </form>

            <!-- Messages Table Card -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-zinc-50/70 dark:bg-zinc-950/50 border-b border-zinc-200/80 dark:border-zinc-800 text-[11px] font-bold font-mono uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                            <tr>
                                <th class="px-6 py-4">Arah</th>
                                <th class="px-6 py-4">Klien / Kontak</th>
                                <th class="px-6 py-4">Tipe Pesan</th>
                                <th class="px-6 py-4">Isi Pesan</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/60 text-xs">
                            @forelse($messages as $msg)
                                <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        @if($msg->arah === 'keluar')
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                                                <span class="material-symbols-outlined text-[16px]">arrow_outward</span>
                                                <span>Keluar</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-sky-600 dark:text-sky-400">
                                                <span class="material-symbols-outlined text-[16px]">call_received</span>
                                                <span>Masuk</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($msg->lead)
                                            <a href="{{ route('leads.show', $msg->lead) }}" class="font-bold text-zinc-900 dark:text-white hover:text-emerald-600">
                                                {{ $msg->lead->nama_usaha }}
                                            </a>
                                        @else
                                            <span class="text-zinc-500 italic">Klien Belum Terdaftar</span>
                                        @endif
                                        <p class="text-zinc-400 font-mono text-[11px] mt-0.5">{{ $msg->kontak_wa }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-[10px] font-mono uppercase font-semibold">
                                            {{ str_replace('_', ' ', $msg->tipe_pesan) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 max-w-md">
                                        <p class="line-clamp-2 leading-relaxed text-zinc-700 dark:text-zinc-300">
                                            {{ $msg->isi_pesan }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono uppercase font-bold {{ $msg->status_kirim === 'sent' || $msg->status_kirim === 'received' ? 'bg-emerald-50 text-emerald-700' : 'bg-zinc-100 text-zinc-600' }}">
                                            {{ $msg->status_kirim }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-zinc-400 text-[11px] whitespace-nowrap">
                                        {{ $msg->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-zinc-400 text-xs">
                                        Belum ada riwayat pesan WhatsApp.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($messages->hasPages())
                    <div class="px-6 py-4 border-t border-zinc-100 dark:border-zinc-800">
                        {{ $messages->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
