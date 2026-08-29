<x-app-layout>
    <div class="py-6 sm:py-8 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-50 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            <x-flash />

            <!-- Header Title Row -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">Dashboard Ikhtisar</h1>
                    <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-1">Pantau pipeline penjualan, closing proyek, dan langganan maintenance RZ Digital Creative.</p>
                </div>
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('leads.index') }}?create=1" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white text-xs font-bold transition-all duration-200 shadow-sm hover:shadow active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">person_add</span>
                        <span>Tambah Lead Baru</span>
                    </a>
                </div>
            </div>

            <!-- Top 4 Metric KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Card 1: Potensi Pipeline -->
                <div class="bg-white dark:bg-zinc-900 p-5 sm:p-6 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 font-mono">Potensi Pipeline</p>
                            <h3 class="text-2xl sm:text-3xl font-black text-zinc-900 dark:text-white mt-1.5">Rp {{ number_format($stats['potensi_pipeline'], 0, ',', '.') }}</h3>
                        </div>
                        <div class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[24px]">filter_alt</span>
                        </div>
                    </div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-3 flex items-center gap-1.5">
                        <span class="inline-block w-2 h-2 rounded-full bg-amber-400"></span>
                        Dari {{ $stats['leads_per_status']['sudah_chat'] + $stats['leads_per_status']['nego'] }} prospek (Sudah Chat + Nego)
                    </p>
                </div>

                <!-- Card 2: Closing Bulan Ini -->
                <div class="bg-white dark:bg-zinc-900 p-5 sm:p-6 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 font-mono">Closing Bulan Ini</p>
                            <h3 class="text-2xl sm:text-3xl font-black text-zinc-900 dark:text-white mt-1.5">Rp {{ number_format($stats['closing_bulan_ini'], 0, ',', '.') }}</h3>
                        </div>
                        <div class="p-2.5 rounded-xl bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[24px]">verified</span>
                        </div>
                    </div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-3 flex items-center gap-1.5">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                        {{ $stats['project_deal_count'] }} proyek Deal berjalan bulan {{ now()->translatedFormat('F') }}
                    </p>
                </div>

                <!-- Card 3: MRR Maintenance -->
                <div class="bg-white dark:bg-zinc-900 p-5 sm:p-6 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 font-mono">MRR Maintenance</p>
                            <h3 class="text-2xl sm:text-3xl font-black text-zinc-900 dark:text-white mt-1.5">Rp {{ number_format($stats['mrr_maintenance'], 0, ',', '.') }}<span class="text-xs text-zinc-400 font-normal">/bln</span></h3>
                        </div>
                        <div class="p-2.5 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[24px]">autorenew</span>
                        </div>
                    </div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-3 flex items-center gap-1.5">
                        <span class="inline-block w-2 h-2 rounded-full bg-indigo-500"></span>
                        {{ $stats['active_maintenance_count'] }} klien berlangganan aktif
                    </p>
                </div>

                <!-- Card 4: Total Leads -->
                <div class="bg-white dark:bg-zinc-900 p-5 sm:p-6 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm hover:shadow-md transition-all duration-200 group">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 font-mono">Total Database Leads</p>
                            <h3 class="text-2xl sm:text-3xl font-black text-zinc-900 dark:text-white mt-1.5">{{ $stats['total_leads'] }} <span class="text-xs text-zinc-400 font-normal">Kontak</span></h3>
                        </div>
                        <div class="p-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[24px]">contacts</span>
                        </div>
                    </div>
                    <!-- Mini Status Bar -->
                    <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-2 rounded-full mt-3 overflow-hidden flex">
                        @php
                            $t = max(1, $stats['total_leads']);
                            $pBelum = ($stats['leads_per_status']['belum_dihubungi'] / $t) * 100;
                            $pChat = ($stats['leads_per_status']['sudah_chat'] / $t) * 100;
                            $pNego = ($stats['leads_per_status']['nego'] / $t) * 100;
                            $pDeal = ($stats['leads_per_status']['deal'] / $t) * 100;
                        @endphp
                        <div style="width: {{ $pDeal }}%" class="bg-emerald-500" title="Deal"></div>
                        <div style="width: {{ $pNego }}%" class="bg-amber-400" title="Nego"></div>
                        <div style="width: {{ $pChat }}%" class="bg-sky-400" title="Sudah Chat"></div>
                        <div style="width: {{ $pBelum }}%" class="bg-zinc-300 dark:bg-zinc-600" title="Belum Dihubungi"></div>
                    </div>
                </div>
            </div>

            <!-- Two Columns: Follow-ups Priority & Active Projects -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Priority Follow-Up Box -->
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-zinc-100 dark:border-zinc-800">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-rose-500">alarm</span>
                                <h3 class="font-bold text-zinc-900 dark:text-white text-base">Prioritas Follow-Up</h3>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($stats['overdue_count'] > 0)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 font-mono">{{ $stats['overdue_count'] }} Terlambat</span>
                                @endif
                                @if($stats['today_count'] > 0)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 font-mono">{{ $stats['today_count'] }} Hari Ini</span>
                                @endif
                            </div>
                        </div>

                        <div class="divide-y divide-zinc-100 dark:divide-zinc-800/60 mt-2">
                            <!-- Overdue Leads -->
                            @foreach($overdueFollowUps as $lead)
                                <div class="py-3 flex items-center justify-between gap-3 hover:bg-zinc-50/60 dark:hover:bg-zinc-800/30 px-2 rounded-xl transition-colors">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('leads.show', $lead) }}" class="font-bold text-zinc-900 dark:text-zinc-100 text-sm hover:text-emerald-600 dark:hover:text-emerald-400 truncate">
                                                {{ $lead->nama_usaha }}
                                            </a>
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-200/50 dark:border-rose-900/40">Telat</span>
                                        </div>
                                        <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5 truncate">{{ $lead->nama_kontak ? $lead->nama_kontak . ' • ' : '' }}{{ $lead->paket_label }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->kontak_wa) }}" target="_blank" class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 transition-colors" title="Chat Manual WA">
                                            <span class="material-symbols-outlined text-[18px]">chat</span>
                                        </a>
                                        <a href="{{ route('leads.show', $lead) }}" class="px-2.5 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-xs font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 transition-colors">Detail</a>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Today Leads -->
                            @foreach($todayFollowUps as $lead)
                                <div class="py-3 flex items-center justify-between gap-3 hover:bg-zinc-50/60 dark:hover:bg-zinc-800/30 px-2 rounded-xl transition-colors">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('leads.show', $lead) }}" class="font-bold text-zinc-900 dark:text-zinc-100 text-sm hover:text-emerald-600 dark:hover:text-emerald-400 truncate">
                                                {{ $lead->nama_usaha }}
                                            </a>
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200/50 dark:border-amber-900/40">Hari Ini</span>
                                        </div>
                                        <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5 truncate">{{ $lead->nama_kontak ? $lead->nama_kontak . ' • ' : '' }}{{ $lead->status_label }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->kontak_wa) }}" target="_blank" class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">chat</span>
                                        </a>
                                        <a href="{{ route('leads.show', $lead) }}" class="px-2.5 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-xs font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 transition-colors">Detail</a>
                                    </div>
                                </div>
                            @endforeach

                            @if($overdueFollowUps->isEmpty() && $todayFollowUps->isEmpty())
                                <div class="py-10 text-center text-zinc-400 dark:text-zinc-500">
                                    <span class="material-symbols-outlined text-[36px] text-emerald-500/80 block mb-1">task_alt</span>
                                    <p class="text-xs">Hebat! Tidak ada follow-up yang terlambat untuk saat ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-zinc-100 dark:border-zinc-800 flex justify-end">
                        <a href="{{ route('leads.index') }}?filter=overdue" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                            <span>Lihat Semua Follow-up</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </div>
                </div>

                <!-- Active Projects in Progress -->
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-zinc-100 dark:border-zinc-800">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-teal-500">engineering</span>
                                <h3 class="font-bold text-zinc-900 dark:text-white text-base">Proyek Sedang Berjalan</h3>
                            </div>
                            <a href="{{ route('projects.index') }}" class="text-xs text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200">Lihat Semua</a>
                        </div>

                        <div class="divide-y divide-zinc-100 dark:divide-zinc-800/60 mt-2">
                            @forelse($activeProjects as $project)
                                <div class="py-3 flex items-center justify-between gap-3 hover:bg-zinc-50/60 dark:hover:bg-zinc-800/30 px-2 rounded-xl transition-colors">
                                    <div class="min-w-0">
                                        <a href="{{ route('projects.show', $project) }}" class="font-bold text-zinc-900 dark:text-zinc-100 text-sm hover:text-emerald-600 dark:hover:text-emerald-400 truncate block">
                                            {{ $project->nama_project }}
                                        </a>
                                        <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">Klien: {{ $project->lead->nama_usaha }} • Rp {{ number_format($project->harga, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="shrink-0 text-right">
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $project->status === 'dikerjakan' ? 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400' : 'bg-sky-50 text-sky-600 dark:bg-sky-950/40 dark:text-sky-400' }}">
                                            {{ $project->status_label }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="py-10 text-center text-zinc-400 dark:text-zinc-500">
                                    <span class="material-symbols-outlined text-[36px] text-zinc-300 dark:text-zinc-600 block mb-1">work_outline</span>
                                    <p class="text-xs">Belum ada proyek dalam tahap pengerjaan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-zinc-100 dark:border-zinc-800 flex justify-end">
                        <a href="{{ route('projects.index') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                            <span>Kelola Pengerjaan Proyek</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Bottom Section: Incoming WhatsApp Messages Feed -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm p-6">
                <div class="flex items-center justify-between pb-4 border-b border-zinc-100 dark:border-zinc-800">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-500">chat_bubble</span>
                        <h3 class="font-bold text-zinc-900 dark:text-white text-base">Balasan WhatsApp Masuk Terbaru</h3>
                    </div>
                    <a href="{{ route('messages.index') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">Semua Riwayat Pesan</a>
                </div>

                <div class="divide-y divide-zinc-100 dark:divide-zinc-800/60 mt-2">
                    @forelse($recentIncomingMessages as $msg)
                        <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 hover:bg-zinc-50/60 dark:hover:bg-zinc-800/30 px-2 rounded-xl transition-colors">
                            <div class="flex items-start gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-[18px]">chat</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-sm text-zinc-900 dark:text-zinc-100 truncate">
                                            {{ $msg->lead ? $msg->lead->nama_usaha : $msg->kontak_wa }}
                                        </span>
                                        <span class="text-[10px] font-mono text-zinc-400 dark:text-zinc-500">{{ $msg->kontak_wa }}</span>
                                    </div>
                                    <p class="text-xs text-zinc-600 dark:text-zinc-300 mt-1 leading-relaxed line-clamp-2">
                                        "{{ $msg->isi_pesan }}"
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0 sm:self-center pl-11 sm:pl-0">
                                <span class="text-[11px] font-mono text-zinc-400 dark:text-zinc-500">{{ $msg->created_at->diffForHumans() }}</span>
                                @if($msg->lead)
                                    <a href="{{ route('leads.show', $msg->lead) }}" class="px-2.5 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 text-xs font-bold hover:bg-emerald-100 transition-colors">Buka Chat</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-zinc-400 dark:text-zinc-500">
                            <span class="material-symbols-outlined text-[32px] text-zinc-300 dark:text-zinc-600 block mb-1">inbox</span>
                            <p class="text-xs">Belum ada balasan WhatsApp masuk dari klien.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
