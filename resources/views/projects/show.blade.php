<x-app-layout>
    <div class="py-6 sm:py-8 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-50 min-h-screen transition-colors duration-300"
         x-data="{ 
            openPaymentModal: false,
            openStatusModal: false,
            newStatus: '{{ $project->status }}',
            dpAmount: '{{ $project->harga / 2 }}',
            linkWebsite: '{{ $project->link_website ?: 'https://' . strtolower(str_replace(' ', '', $project->lead->nama_usaha)) . '.com' }}',
            createMaintenance: true,
            sendWa: true
         }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            <x-flash />

            <!-- Breadcrumb Navigation -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-2 text-xs font-semibold text-zinc-400">
                    <a href="{{ route('projects.index') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                        <span>Daftar Proyek</span>
                    </a>
                    <span>/</span>
                    <span class="text-zinc-800 dark:text-zinc-200 font-bold truncate">{{ $project->nama_project }}</span>
                </div>

                <div class="flex items-center gap-2.5">
                    <button @click="openStatusModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 text-xs font-bold transition-all shadow-sm hover:shadow active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">sync_alt</span>
                        <span>Ubah Status Proyek</span>
                    </button>
                    <button @click="openPaymentModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-sm hover:shadow active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">add_card</span>
                        <span>Catat Pembayaran</span>
                    </button>
                </div>
            </div>

            <!-- Project Details Card -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm p-6 sm:p-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/40">
                                {{ $project->status_label }}
                            </span>
                            <span class="text-xs text-zinc-400 font-mono">ID: #PRJ-{{ str_pad($project->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black text-zinc-900 dark:text-white mt-2 tracking-tight">
                            {{ $project->nama_project }}
                        </h1>
                        <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                            Klien: <a href="{{ route('leads.show', $project->lead) }}" class="font-bold text-emerald-600 hover:underline">{{ $project->lead->nama_usaha }}</a>
                            @if($project->lead->nama_kontak)
                                ({{ $project->lead->nama_kontak }})
                            @endif
                        </p>
                    </div>

                    <!-- Financial Summary Pill -->
                    <div class="grid grid-cols-3 gap-4 bg-zinc-50 dark:bg-zinc-950 p-4 rounded-2xl border border-zinc-100 dark:border-zinc-800">
                        <div>
                            <p class="text-[10px] font-mono uppercase text-zinc-400 font-bold">Total Nilai</p>
                            <p class="text-base sm:text-lg font-black text-zinc-900 dark:text-white mt-0.5">Rp {{ number_format($project->harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="border-l border-zinc-200 dark:border-zinc-800 pl-4">
                            <p class="text-[10px] font-mono uppercase text-emerald-600 dark:text-emerald-400 font-bold">Terbayar</p>
                            <p class="text-base sm:text-lg font-black text-emerald-600 dark:text-emerald-400 mt-0.5">Rp {{ number_format($project->total_paid, 0, ',', '.') }}</p>
                        </div>
                        <div class="border-l border-zinc-200 dark:border-zinc-800 pl-4">
                            <p class="text-[10px] font-mono uppercase text-rose-500 font-bold">Sisa Tagihan</p>
                            <p class="text-base sm:text-lg font-black text-rose-500 mt-0.5">Rp {{ number_format($project->remaining_balance, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                @if($project->link_website)
                    <div class="mt-6 pt-6 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="material-symbols-outlined text-emerald-600 text-[18px]">public</span>
                            <span class="text-zinc-500">Website Live:</span>
                            <a href="{{ $project->link_website }}" target="_blank" class="font-mono font-bold text-emerald-600 hover:underline">
                                {{ $project->link_website }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Two Columns: Payments Table & Client WhatsApp Chat History -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Payments History Box -->
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800">
                        <h3 class="font-bold text-zinc-900 dark:text-white text-base flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600">receipt</span>
                            <span>Riwayat Pembayaran</span>
                        </h3>
                        <button @click="openPaymentModal = true" class="text-xs font-bold text-emerald-600 hover:underline">
                            + Tambah
                        </button>
                    </div>

                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse($project->payments as $p)
                            <div class="py-3 flex items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-xs text-zinc-900 dark:text-white">{{ $p->jenis_label }}</span>
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase {{ $p->status === 'lunas' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                            {{ strtoupper($p->status) }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-zinc-400 mt-0.5">{{ $p->tanggal ? $p->tanggal->format('d M Y') : '-' }} • {{ $p->catatan ?: 'Tanpa catatan' }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="font-mono font-bold text-xs text-zinc-900 dark:text-white">
                                        Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-zinc-400 text-xs">
                                Belum ada riwayat pembayaran tercatat untuk proyek ini.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Client Chat Quick Box -->
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800">
                        <h3 class="font-bold text-zinc-900 dark:text-white text-base flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600">chat</span>
                            <span>Riwayat Chat Klien</span>
                        </h3>
                        <a href="{{ route('leads.show', $project->lead) }}" class="text-xs font-bold text-emerald-600 hover:underline">
                            Buka Chat Lengkap
                        </a>
                    </div>

                    <div class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar p-2">
                        @forelse($project->lead->messageLogs->take(4) as $msg)
                            <div class="p-3 rounded-xl {{ $msg->arah === 'keluar' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-900 dark:text-emerald-200' : 'bg-zinc-50 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200' }} text-xs">
                                <div class="flex items-center justify-between font-mono text-[10px] text-zinc-400 mb-1">
                                    <span>{{ $msg->arah === 'keluar' ? 'RZ DIGITAL' : $project->lead->nama_usaha }}</span>
                                    <span>{{ $msg->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="line-clamp-3 leading-relaxed">{{ $msg->isi_pesan }}</p>
                            </div>
                        @empty
                            <div class="py-8 text-center text-zinc-400 text-xs">
                                Belum ada log pesan WhatsApp untuk klien ini.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

        <!-- ============================================================ -->
        <!-- MODAL CATAT PEMBAYARAN -->
        <!-- ============================================================ -->
        <div x-show="openPaymentModal" 
             x-transition
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/70 backdrop-blur-sm"
             style="display: none;">
            <div @click.away="openPaymentModal = false" class="bg-white dark:bg-zinc-900 w-full max-w-md rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                    <h3 class="font-extrabold text-zinc-900 dark:text-white text-base">Catat Pembayaran Proyek</h3>
                    <button @click="openPaymentModal = false" class="text-zinc-400 hover:text-zinc-600">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('payments.store') }}" class="space-y-3">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Jenis Pembayaran</label>
                        <select name="jenis" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border rounded-xl text-xs">
                            <option value="dp">Uang Muka (DP)</option>
                            <option value="pelunasan">Pelunasan Akhir</option>
                            <option value="maintenance">Biaya Maintenance</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Nominal Pembayaran (Rp)</label>
                        <input type="number" name="jumlah" value="{{ $project->remaining_balance > 0 ? $project->remaining_balance : $project->harga / 2 }}" required
                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border rounded-xl text-xs">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Status</label>
                            <select name="status" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border rounded-xl text-xs">
                                <option value="lunas">LUNAS</option>
                                <option value="pending">PENDING</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ now()->toDateString() }}" required
                                   class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border rounded-xl text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Catatan / No. Ref Transfer</label>
                        <input type="text" name="catatan" placeholder="Contoh: Transfer BCA a.n Budi"
                               class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border rounded-xl text-xs">
                    </div>

                    <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 flex justify-end gap-2">
                        <button type="button" @click="openPaymentModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold border text-zinc-600">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- MODAL UBAH STATUS PROYEK -->
        <!-- ============================================================ -->
        <div x-show="openStatusModal" 
             x-transition
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/70 backdrop-blur-sm"
             style="display: none;">
            <div @click.away="openStatusModal = false" class="bg-white dark:bg-zinc-900 w-full max-w-lg rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-2xl p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-4">
                    <div>
                        <h3 class="font-extrabold text-zinc-900 dark:text-white text-base">Ubah Status Proyek</h3>
                        <p class="text-xs text-zinc-400 mt-0.5">{{ $project->nama_project }}</p>
                    </div>
                    <button @click="openStatusModal = false" class="text-zinc-400 hover:text-zinc-600">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('projects.update-status', $project) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Pilih Status Baru <span class="text-rose-500">*</span></label>
                        <select name="status" x-model="newStatus" required class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-800 dark:text-zinc-200 font-bold focus:ring-emerald-500">
                            <option value="draft">Draft / Baru</option>
                            <option value="dp_diterima">DP Diterima (Picu WA Invoice DP)</option>
                            <option value="dikerjakan">Sedang Dikerjakan</option>
                            <option value="review">Review Klien</option>
                            <option value="selesai">Selesai &amp; Live (Picu WA Live + Maintenance)</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>

                    <!-- Additional inputs for DP Diterima -->
                    <div x-show="newStatus === 'dp_diterima'" class="p-4 rounded-xl bg-sky-50 dark:bg-sky-950/40 border border-sky-200/60 dark:border-sky-800/40 space-y-3">
                        <p class="text-xs font-bold text-sky-800 dark:text-sky-300 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">receipt_long</span>
                            <span>Pencatatan Pembayaran Uang Muka (DP)</span>
                        </p>
                        <div>
                            <label class="block text-[11px] font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Nominal DP Diterima (Rp)</label>
                            <input type="number" name="dp_amount" x-model="dpAmount" 
                                   class="w-full px-3 py-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg text-xs">
                        </div>
                    </div>

                    <!-- Additional inputs for Selesai -->
                    <div x-show="newStatus === 'selesai'" class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200/60 dark:border-emerald-800/40 space-y-3">
                        <p class="text-xs font-bold text-emerald-800 dark:text-emerald-300 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">public</span>
                            <span>Informasi Website Live</span>
                        </p>
                        <div>
                            <label class="block text-[11px] font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Link URL Website Live</label>
                            <input type="url" name="link_website" x-model="linkWebsite" placeholder="https://domainklien.com"
                                   class="w-full px-3 py-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg text-xs">
                        </div>

                        <label class="flex items-center gap-2 cursor-pointer pt-1">
                            <input type="checkbox" name="create_maintenance" value="1" x-model="createMaintenance" class="rounded text-emerald-600 focus:ring-emerald-500">
                            <span class="text-xs text-zinc-700 dark:text-zinc-300 font-semibold">Otomatis buat langganan maintenance bulanan aktif</span>
                        </label>
                    </div>

                    <!-- WhatsApp Auto-Send Toggle -->
                    <div class="p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600">chat</span>
                            <div>
                                <p class="text-xs font-bold text-zinc-900 dark:text-white">Kirim WhatsApp Otomatis</p>
                                <p class="text-[10px] text-zinc-400">Pesan transactional sesuai template Flustra</p>
                            </div>
                        </div>
                        <input type="checkbox" name="send_wa" value="1" x-model="sendWa" class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4">
                    </div>

                    <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800 flex justify-end gap-2">
                        <button type="button" @click="openStatusModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold border text-zinc-600">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm">Simpan Status</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
