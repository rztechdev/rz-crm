<x-app-layout>
    <div class="space-y-6 w-full" x-data="{ openCreateModal: false }">

            <!-- Flash Alert -->
            <x-flash />

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">Langganan Maintenance Bulanan</h1>
                    <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-1">Kelola pendapatan rutin bulanan (MRR) dan pantau pengingat tagihan WhatsApp otomatis H-3.</p>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Card 1: Total MRR -->
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 font-mono">Monthly Recurring Revenue</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1">Rp {{ number_format($totalMRR, 0, ',', '.') }}<span class="text-xs text-zinc-400 font-normal">/bln</span></h3>
                    </div>
                    <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400">
                        <span class="material-symbols-outlined text-[28px]">autorenew</span>
                    </div>
                </div>

                <!-- Card 2: Active Subscribers -->
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 font-mono">Langganan Aktif</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-zinc-900 dark:text-white mt-1">{{ $activeCount }} <span class="text-xs text-zinc-400 font-normal">Klien</span></h3>
                    </div>
                    <div class="p-3 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                        <span class="material-symbols-outlined text-[28px]">supervised_user_circle</span>
                    </div>
                </div>

                <!-- Card 3: Due Soon -->
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 font-mono">Mendekati Jatuh Tempo (H-3)</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-amber-500 mt-1">{{ $needReminderCount }} <span class="text-xs text-zinc-400 font-normal">Tagihan</span></h3>
                    </div>
                    <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-500">
                        <span class="material-symbols-outlined text-[28px]">schedule_send</span>
                    </div>
                </div>
            </div>

            <!-- Maintenance Subscriptions Table Card (Desktop: Table, Mobile: Cards) -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200/80 dark:border-zinc-800 shadow-xs overflow-hidden">
                <!-- Desktop Table (md:block) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-zinc-50/70 dark:bg-zinc-950/50 border-b border-zinc-200/80 dark:border-zinc-800 text-[11px] font-bold font-mono uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                            <tr>
                                <th class="px-6 py-4">Klien / Usaha</th>
                                <th class="px-6 py-4">Tarif Bulanan</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Mulai Langganan</th>
                                <th class="px-6 py-4">Jatuh Tempo Berikutnya</th>
                                <th class="px-6 py-4">Terakhir Diingatkan</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/60 text-xs">
                            @forelse($subscriptions as $sub)
                                @php
                                    $isDueSoon = $sub->isReminderDue();
                                @endphp
                                <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30 transition-colors">
                                    
                                    <!-- Klien / Usaha -->
                                    <td class="px-6 py-4">
                                        <a href="{{ route('leads.show', $sub->lead) }}" class="font-bold text-zinc-900 dark:text-white hover:text-emerald-600">
                                            {{ $sub->lead->nama_usaha }}
                                        </a>
                                        <p class="text-zinc-400 text-[11px] mt-0.5">{{ $sub->lead->kontak_wa }}</p>
                                    </td>

                                    <!-- Tarif Bulanan -->
                                    <td class="px-6 py-4 font-mono font-bold text-zinc-900 dark:text-white">
                                        Rp {{ number_format($sub->harga_bulanan, 0, ',', '.') }}<span class="text-zinc-400 font-normal">/bln</span>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $sub->status === 'aktif' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/50' : 'bg-zinc-100 text-zinc-500' }}">
                                            {{ strtoupper($sub->status) }}
                                        </span>
                                    </td>

                                    <!-- Mulai Langganan -->
                                    <td class="px-6 py-4 font-mono text-zinc-500">
                                        {{ $sub->tanggal_mulai ? $sub->tanggal_mulai->format('d M Y') : '-' }}
                                    </td>

                                    <!-- Jatuh Tempo Berikutnya -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-mono font-bold {{ $isDueSoon ? 'text-amber-600 dark:text-amber-400' : 'text-zinc-700 dark:text-zinc-300' }}">
                                                {{ $sub->tanggal_jatuh_tempo_berikutnya ? $sub->tanggal_jatuh_tempo_berikutnya->format('d M Y') : '-' }}
                                            </span>
                                            @if($isDueSoon)
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase bg-amber-500 text-white">H-3</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Terakhir Diingatkan -->
                                    <td class="px-6 py-4 text-zinc-400 text-[11px] font-mono">
                                        {{ $sub->terakhir_diingatkan_at ? $sub->terakhir_diingatkan_at->diffForHumans() : 'Belum pernah' }}
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @php
                                                $waRemindText = rawurlencode("Halo Kak {$sub->lead->nama_kontak},\n\nKami dari RZ Digital Creative menginfokan bahwa langganan Maintenance Website *{$sub->lead->nama_usaha}* akan jatuh tempo pada *{$sub->tanggal_jatuh_tempo_berikutnya?->format('d M Y')}* (Rp " . number_format($sub->harga_bulanan, 0, ',', '.') . ").\n\nTerima kasih! 🙏");
                                                $waPhone = preg_replace('/[^0-9]/', '', $sub->lead->kontak_wa);
                                            @endphp
                                            <a href="https://wa.me/{{ $waPhone }}?text={{ $waRemindText }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 text-[11px] font-bold hover:bg-emerald-100 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">chat</span>
                                                <span>WhatsApp</span>
                                            </a>

                                            <a href="{{ route('invoices.maintenance', $sub) }}" target="_blank" class="p-1.5 rounded-lg text-zinc-400 hover:text-emerald-600 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors" title="Invoice Maintenance">
                                                <span class="material-symbols-outlined text-[18px]">receipt</span>
                                            </a>

                                            <!-- Toggle Status -->
                                            <form method="POST" action="{{ route('maintenance.toggle', $sub) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="p-1.5 rounded-lg text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200" title="Ubah Aktif/Nonaktif">
                                                    <span class="material-symbols-outlined text-[18px]">{{ $sub->status === 'aktif' ? 'toggle_on' : 'toggle_off' }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-zinc-400 text-xs">
                                        Belum ada langganan maintenance bulanan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards (md:hidden) -->
                <div class="md:hidden divide-y divide-zinc-100 dark:divide-zinc-800/60">
                    @forelse($subscriptions as $sub)
                        @php
                            $isDueSoon = $sub->isReminderDue();
                            $waRemindText = rawurlencode("Halo Kak {$sub->lead->nama_kontak},\n\nKami dari RZ Digital Creative menginfokan bahwa langganan Maintenance Website *{$sub->lead->nama_usaha}* akan jatuh tempo pada *{$sub->tanggal_jatuh_tempo_berikutnya?->format('d M Y')}* (Rp " . number_format($sub->harga_bulanan, 0, ',', '.') . ").\n\nTerima kasih! 🙏");
                            $waPhone = preg_replace('/[^0-9]/', '', $sub->lead->kontak_wa);
                        @endphp
                        <div class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <a href="{{ route('leads.show', $sub->lead) }}" class="font-bold text-xs text-zinc-900 dark:text-white">
                                        {{ $sub->lead->nama_usaha }}
                                    </a>
                                    <p class="text-[11px] text-zinc-500 mt-0.5">{{ $sub->lead->kontak_wa }}</p>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $sub->status === 'aktif' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/50' : 'bg-zinc-100 text-zinc-500' }}">
                                    {{ strtoupper($sub->status) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between text-xs">
                                <span class="text-zinc-500">Tarif Bulanan</span>
                                <span class="font-mono font-bold text-zinc-900 dark:text-white">
                                    Rp {{ number_format($sub->harga_bulanan, 0, ',', '.') }}/bln
                                </span>
                            </div>

                            <div class="flex items-center justify-between text-xs">
                                <span class="text-zinc-500">Jatuh Tempo</span>
                                <div class="flex items-center gap-1 font-mono font-semibold {{ $isDueSoon ? 'text-amber-600' : 'text-zinc-700 dark:text-zinc-300' }}">
                                    <span>{{ $sub->tanggal_jatuh_tempo_berikutnya ? $sub->tanggal_jatuh_tempo_berikutnya->format('d M Y') : '-' }}</span>
                                    @if($isDueSoon)
                                        <span class="px-1 py-0.2 rounded text-[8px] bg-amber-500 text-white font-bold uppercase">H-3</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-zinc-100 dark:border-zinc-800/80 text-xs">
                                <a href="https://wa.me/{{ $waPhone }}?text={{ $waRemindText }}" target="_blank"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-600 text-white font-bold text-[11px]">
                                    <span class="material-symbols-outlined text-[14px]">chat</span>
                                    <span>Reminder WA</span>
                                </a>

                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('invoices.maintenance', $sub) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-[11px] font-bold">
                                        Invoice
                                    </a>
                                    <form method="POST" action="{{ route('maintenance.toggle', $sub) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-1.5 rounded-lg text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                                            <span class="material-symbols-outlined text-[18px]">{{ $sub->status === 'aktif' ? 'toggle_on' : 'toggle_off' }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-zinc-400 text-xs">
                            Belum ada langganan maintenance bulanan.
                        </div>
                    @endforelse
                </div>

                @if($subscriptions->hasPages())
                    <div class="p-4 border-t border-zinc-200/80 dark:border-zinc-800">
                        {{ $subscriptions->links() }}
                    </div>
                @endif
            </div>

        </div>
</x-app-layout>
