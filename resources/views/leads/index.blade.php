<x-app-layout>
    <div class="py-6 sm:py-8 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-50 min-h-screen transition-colors duration-300"
         x-data="{ 
            openCreateModal: new URLSearchParams(window.location.search).has('create'),
            openEditModal: false,
            editLead: {}
         }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            <x-flash />

            <!-- Title & Top Action Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">Leads &amp; Pipeline</h1>
                    <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-1">Kelola data prospek, follow-up manual WhatsApp, dan konversi deal ke project.</p>
                </div>
                <button @click="openCreateModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white text-xs font-bold transition-all duration-200 shadow-sm hover:shadow active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    <span>Tambah Lead Baru</span>
                </button>
            </div>

            <!-- Status Filter Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto custom-scrollbar pb-1 text-xs font-bold">
                <a href="{{ route('leads.index') }}" 
                   class="px-3.5 py-2 rounded-xl border transition-all whitespace-nowrap {{ !request('status') && !request('filter') ? 'bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 border-transparent shadow-sm' : 'bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border-zinc-200 dark:border-zinc-800 hover:border-zinc-300' }}">
                    Semua ({{ $statusCounts['all'] }})
                </a>
                <a href="{{ route('leads.index', ['status' => 'belum_dihubungi']) }}" 
                   class="px-3.5 py-2 rounded-xl border transition-all whitespace-nowrap {{ request('status') === 'belum_dihubungi' ? 'bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 border-transparent shadow-sm' : 'bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border-zinc-200 dark:border-zinc-800 hover:border-zinc-300' }}">
                    Belum Dihubungi ({{ $statusCounts['belum_dihubungi'] }})
                </a>
                <a href="{{ route('leads.index', ['status' => 'sudah_chat']) }}" 
                   class="px-3.5 py-2 rounded-xl border transition-all whitespace-nowrap {{ request('status') === 'sudah_chat' ? 'bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 border-transparent shadow-sm' : 'bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border-zinc-200 dark:border-zinc-800 hover:border-zinc-300' }}">
                    Sudah Chat ({{ $statusCounts['sudah_chat'] }})
                </a>
                <a href="{{ route('leads.index', ['status' => 'nego']) }}" 
                   class="px-3.5 py-2 rounded-xl border transition-all whitespace-nowrap {{ request('status') === 'nego' ? 'bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 border-transparent shadow-sm' : 'bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border-zinc-200 dark:border-zinc-800 hover:border-zinc-300' }}">
                    Nego ({{ $statusCounts['nego'] }})
                </a>
                <a href="{{ route('leads.index', ['status' => 'deal']) }}" 
                   class="px-3.5 py-2 rounded-xl border transition-all whitespace-nowrap {{ request('status') === 'deal' ? 'bg-emerald-600 text-white border-transparent shadow-sm' : 'bg-white dark:bg-zinc-900 text-emerald-600 dark:text-emerald-400 border-zinc-200 dark:border-zinc-800 hover:border-emerald-500/30' }}">
                    Deal / Klien ({{ $statusCounts['deal'] }})
                </a>
                <a href="{{ route('leads.index', ['status' => 'tidak_lanjut']) }}" 
                   class="px-3.5 py-2 rounded-xl border transition-all whitespace-nowrap {{ request('status') === 'tidak_lanjut' ? 'bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 border-transparent shadow-sm' : 'bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border-zinc-200 dark:border-zinc-800 hover:border-zinc-300' }}">
                    Tidak Lanjut ({{ $statusCounts['tidak_lanjut'] }})
                </a>
                <a href="{{ route('leads.index', ['filter' => 'overdue']) }}" 
                   class="px-3.5 py-2 rounded-xl border transition-all whitespace-nowrap {{ request('filter') === 'overdue' ? 'bg-rose-600 text-white border-transparent shadow-sm' : 'bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-900/40 hover:bg-rose-100' }}">
                    ⚠️ Follow-Up Telat ({{ $statusCounts['overdue'] }})
                </a>
            </div>

            <!-- Search & Filters Row -->
            <form method="GET" action="{{ route('leads.index') }}" class="bg-white dark:bg-zinc-900 p-4 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm flex flex-col md:flex-row items-center gap-3">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('filter'))
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif

                <div class="relative flex-1 w-full">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-[20px]">search</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama usaha, kontak, nomor WhatsApp..." 
                           class="w-full pl-10 pr-4 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <select name="sumber" onchange="this.form.submit()" 
                            class="w-full md:w-44 px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-700 dark:text-zinc-300 focus:ring-emerald-500">
                        <option value="">Semua Sumber</option>
                        <option value="warm_network" {{ request('sumber') == 'warm_network' ? 'selected' : '' }}>Warm Network</option>
                        <option value="cold_outreach" {{ request('sumber') == 'cold_outreach' ? 'selected' : '' }}>Cold Outreach</option>
                        <option value="komunitas" {{ request('sumber') == 'komunitas' ? 'selected' : '' }}>Komunitas</option>
                        <option value="marketplace" {{ request('sumber') == 'marketplace' ? 'selected' : '' }}>Marketplace</option>
                        <option value="referral" {{ request('sumber') == 'referral' ? 'selected' : '' }}>Referral</option>
                        <option value="website" {{ request('sumber') == 'website' ? 'selected' : '' }}>Website</option>
                    </select>

                    <select name="sort" onchange="this.form.submit()" 
                            class="w-full md:w-40 px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-700 dark:text-zinc-300 focus:ring-emerald-500">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="follow_up" {{ request('sort') == 'follow_up' ? 'selected' : '' }}>Jadwal Follow-up</option>
                    </select>

                    @if(request()->anyFilled(['q', 'sumber', 'sort', 'status', 'filter']))
                        <a href="{{ route('leads.index') }}" class="p-2 rounded-xl text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800" title="Reset Filter">
                            <span class="material-symbols-outlined text-[20px] block">restart_alt</span>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Leads Table Card -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-zinc-50/70 dark:bg-zinc-950/50 border-b border-zinc-200/80 dark:border-zinc-800 text-[11px] font-bold font-mono uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                            <tr>
                                <th class="px-6 py-4">Usaha &amp; Kontak</th>
                                <th class="px-6 py-4">WhatsApp</th>
                                <th class="px-6 py-4">Paket Diminati</th>
                                <th class="px-6 py-4">Sumber</th>
                                <th class="px-6 py-4">Status Lead</th>
                                <th class="px-6 py-4">Jadwal Follow-up</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/60 text-xs">
                            @forelse($leads as $lead)
                                @php
                                    $isOverdue = $lead->isFollowUpOverdue();
                                    $isToday = $lead->isFollowUpToday();
                                    $statusBadges = [
                                        'belum_dihubungi' => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400',
                                        'sudah_chat' => 'bg-sky-50 text-sky-600 dark:bg-sky-950/40 dark:text-sky-400 border border-sky-200/50',
                                        'nego' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200/50',
                                        'deal' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/50 font-bold',
                                        'tidak_lanjut' => 'bg-zinc-100 text-zinc-400 dark:bg-zinc-800 dark:text-zinc-500 line-through',
                                    ];
                                @endphp
                                <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30 transition-colors group">
                                    
                                    <!-- Usaha & Kontak -->
                                    <td class="px-6 py-4">
                                        <a href="{{ route('leads.show', $lead) }}" class="font-bold text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors text-sm">
                                            {{ $lead->nama_usaha }}
                                        </a>
                                        @if($lead->nama_kontak)
                                            <p class="text-zinc-500 dark:text-zinc-400 text-[11px] mt-0.5">{{ $lead->nama_kontak }}</p>
                                        @endif
                                    </td>

                                    <!-- WhatsApp Direct Link -->
                                    <td class="px-6 py-4">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->kontak_wa) }}" target="_blank" 
                                           class="inline-flex items-center gap-1.5 font-mono text-emerald-600 dark:text-emerald-400 hover:underline font-semibold"
                                           title="Buka Chat WhatsApp Web">
                                            <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                                            <span>{{ $lead->kontak_wa }}</span>
                                        </a>
                                    </td>

                                    <!-- Paket Diminati -->
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-zinc-800 dark:text-zinc-200 block">{{ $lead->paket_label }}</span>
                                        <span class="text-[10px] text-zinc-400 font-mono">Rp {{ number_format($lead->getDefaultPackagePrice(), 0, ',', '.') }}</span>
                                    </td>

                                    <!-- Sumber -->
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 text-[10px] font-semibold">
                                            {{ $lead->sumber_label }}
                                        </span>
                                    </td>

                                    <!-- Status Lead -->
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] uppercase font-bold tracking-wider {{ $statusBadges[$lead->status] ?? 'bg-zinc-100 text-zinc-600' }}">
                                            {{ $lead->status_label }}
                                        </span>
                                    </td>

                                    <!-- Follow-up Date -->
                                    <td class="px-6 py-4">
                                        @if($lead->follow_up_date)
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-mono text-zinc-700 dark:text-zinc-300">{{ $lead->follow_up_date->format('d M Y') }}</span>
                                                @if($isOverdue)
                                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase bg-rose-500 text-white">Telat</span>
                                                @elseif($isToday)
                                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase bg-amber-500 text-white">Hari Ini</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-zinc-400 dark:text-zinc-600 italic">-</span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            @if($lead->status !== 'deal')
                                                <form method="POST" action="{{ route('leads.convert-deal', $lead) }}" onsubmit="return confirm('Konfirmasi: Tandai Deal untuk {{ $lead->nama_usaha }} dan buat Project baru?');">
                                                    @csrf
                                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold transition-colors flex items-center gap-1 shadow-sm" title="Konversi ke Proyek Deal">
                                                        <span class="material-symbols-outlined text-[14px]">handshake</span>
                                                        <span>Tandai Deal</span>
                                                    </button>
                                                </form>
                                            @endif

                                            <a href="{{ route('leads.show', $lead) }}" class="p-1.5 rounded-lg text-zinc-500 hover:text-emerald-600 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors" title="Buka Detail & Chat">
                                                <span class="material-symbols-outlined text-[18px]">chat</span>
                                            </a>

                                            <form method="POST" action="{{ route('leads.destroy', $lead) }}" onsubmit="return confirm('Yakin ingin menghapus lead ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg text-zinc-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors" title="Hapus Lead">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-zinc-400 dark:text-zinc-500">
                                        <span class="material-symbols-outlined text-[36px] block mb-1">person_search</span>
                                        <p class="text-xs">Tidak ada data lead yang cocok dengan filter pencarian.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($leads->hasPages())
                    <div class="px-6 py-4 border-t border-zinc-100 dark:border-zinc-800">
                        {{ $leads->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- ============================================================ -->
        <!-- MODAL TAMBAH LEAD BARU -->
        <!-- ============================================================ -->
        <div x-show="openCreateModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/70 backdrop-blur-sm"
             style="display: none;">
            
            <div @click.away="openCreateModal = false" class="bg-white dark:bg-zinc-900 w-full max-w-lg rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-2xl p-6 sm:p-8 space-y-6 max-h-[90vh] overflow-y-auto custom-scrollbar">
                <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400">
                            <span class="material-symbols-outlined text-[20px]">person_add</span>
                        </div>
                        <h3 class="font-extrabold text-zinc-900 dark:text-white text-lg">Tambah Lead / Prospek Baru</h3>
                    </div>
                    <button @click="openCreateModal = false" class="p-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-white rounded-lg">
                        <span class="material-symbols-outlined text-[20px] block">close</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('leads.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1">Nama Usaha / Bisnis UMKM <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_usaha" required placeholder="Contoh: Kopi Kenangan Senja, Bengkel Berkah"
                               class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1">Nama Kontak / Owner</label>
                            <input type="text" name="nama_kontak" placeholder="Contoh: Budi Santoso"
                                   class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                            <input type="text" name="kontak_wa" required placeholder="081234567890"
                                   class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1">Sumber Lead <span class="text-rose-500">*</span></label>
                            <select name="sumber" required class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-700 dark:text-zinc-300 focus:ring-emerald-500">
                                <option value="warm_network">Warm Network / Teman</option>
                                <option value="cold_outreach">Cold Outreach</option>
                                <option value="komunitas">Komunitas Bisnis</option>
                                <option value="marketplace">Marketplace / Iklan</option>
                                <option value="referral">Referral Klien</option>
                                <option value="website">Website / Form Organik</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1">Status Awal <span class="text-rose-500">*</span></label>
                            <select name="status" required class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-700 dark:text-zinc-300 focus:ring-emerald-500">
                                <option value="belum_dihubungi">Belum Dihubungi</option>
                                <option value="sudah_chat">Sudah Chat Manual</option>
                                <option value="nego">Nego / Tertarik</option>
                                <option value="deal">Langsung Deal (Buat Project)</option>
                                <option value="tidak_lanjut">Tidak Lanjut</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1">Paket Diminati <span class="text-rose-500">*</span></label>
                            <select name="paket_diminati" required class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-700 dark:text-zinc-300 focus:ring-emerald-500">
                                <option value="landing_page">Landing Page (Rp 499.000)</option>
                                <option value="company_profile">Company Profile (Rp 999.000)</option>
                                <option value="toko_kasir">Toko &amp; Kasir POS (Rp 1.500.000)</option>
                                <option value="custom">Custom Web App</option>
                                <option value="belum_tahu">Belum Tahu / Konsultasi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1">Jadwal Follow-Up</label>
                            <input type="date" name="follow_up_date" 
                                   class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1">Catatan Kebutuhan Prospek</label>
                        <textarea name="catatan" rows="3" placeholder="Contoh: Butuh website menu resto + fitur cetak struk Bluetooth kasir."
                                  class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"></textarea>
                    </div>

                    <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-end gap-3">
                        <button type="button" @click="openCreateModal = false" class="px-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-700 text-xs font-semibold text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition-colors">
                            Simpan Lead
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
