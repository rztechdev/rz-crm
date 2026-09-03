<x-app-layout>
    <div class="space-y-6 w-full"
         x-data="{ 
            openCreateModal: false,
            openEditModal: false,
            editUser: {},
            editRole: 'sales'
         }">

            <!-- Flash Alert -->
            <x-flash />

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">Kelola Tim &amp; Peran (RBAC)</h1>
                    <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-1">Sistem tata kelola internal RZ Digital Creative. Pengaturan hak akses granular untuk Admin, Sales, PM, dan Finance.</p>
                </div>
                <button @click="openCreateModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-sm hover:shadow active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    <span>Tambah Anggota Tim</span>
                </button>
            </div>

            <!-- Users Table Card (Desktop: Table, Mobile: Cards) -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200/80 dark:border-zinc-800 shadow-xs overflow-hidden">
                <!-- Desktop Table (md:block) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-zinc-50/70 dark:bg-zinc-950/50 border-b border-zinc-200/80 dark:border-zinc-800 text-[11px] font-bold font-mono uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                            <tr>
                                <th class="px-6 py-4">Pengguna</th>
                                <th class="px-6 py-4">Alamat Email</th>
                                <th class="px-6 py-4">Peran Akses (Role)</th>
                                <th class="px-6 py-4">Terdaftar Sejak</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/60 text-xs">
                            @foreach($users as $user)
                                @php
                                    $userRole = $user->roles->first()?->name ?? 'admin';
                                    $roleBadge = match($userRole) {
                                        'admin' => 'bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300 border border-purple-200/50',
                                        'sales' => 'bg-sky-50 text-sky-700 dark:bg-sky-950/40 dark:text-sky-300 border border-sky-200/50',
                                        'project_manager' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/50',
                                        'finance' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200/50',
                                        default => 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300',
                                    };
                                    $roleLabels = [
                                        'admin' => '👑 Super Admin',
                                        'sales' => '💼 Sales & Prospek',
                                        'project_manager' => '🚀 Project Manager',
                                        'finance' => '💰 Finance & Invoicing',
                                    ];
                                @endphp
                                <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold text-xs">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <span class="font-bold text-zinc-900 dark:text-white">{{ $user->name }}</span>
                                                @if($user->id === auth()->id())
                                                    <span class="ml-1.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 border border-emerald-200/50">Akun Anda</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-zinc-600 dark:text-zinc-300">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $roleBadge }}">
                                            {{ $roleLabels[$userRole] ?? ucfirst($userRole) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-zinc-400">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button @click="editUser = {{ json_encode($user) }}; editRole = '{{ $userRole }}'; openEditModal = true;" class="p-1.5 rounded-lg text-zinc-400 hover:text-emerald-600 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors" title="Edit Akun & Peran">
                                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                            </button>

                                            @if($user->id !== auth()->id())
                                                <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 rounded-lg text-zinc-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors" title="Hapus Akun">
                                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards (md:hidden) -->
                <div class="md:hidden divide-y divide-zinc-100 dark:divide-zinc-800/60">
                    @foreach($users as $user)
                        @php
                            $userRole = $user->roles->first()?->name ?? 'admin';
                            $roleBadge = match($userRole) {
                                'admin' => 'bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300 border border-purple-200/50',
                                'sales' => 'bg-sky-50 text-sky-700 dark:bg-sky-950/40 dark:text-sky-300 border border-sky-200/50',
                                'project_manager' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/50',
                                'finance' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200/50',
                                default => 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300',
                            };
                            $roleLabels = [
                                'admin' => '👑 Super Admin',
                                'sales' => '💼 Sales & Prospek',
                                'project_manager' => '🚀 Project Manager',
                                'finance' => '💰 Finance & Invoicing',
                            ];
                        @endphp
                        <div class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-xs text-zinc-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-[11px] font-mono text-zinc-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $roleBadge }}">
                                    {{ $roleLabels[$userRole] ?? ucfirst($userRole) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-zinc-100 dark:border-zinc-800/80 text-[11px] text-zinc-500">
                                <span class="font-mono">Sejak {{ $user->created_at->format('d M Y') }}</span>
                                <div class="flex items-center gap-1.5">
                                    <button @click="editUser = {{ json_encode($user) }}; editRole = '{{ $userRole }}'; openEditModal = true;" class="px-2.5 py-1 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-bold text-[11px]">
                                        Edit
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus {{ $user->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 rounded bg-rose-50 text-rose-600 font-bold text-[11px]">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-zinc-100 dark:border-zinc-800">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- ============================================================ -->
        <!-- MODAL TAMBAH AKUN PENGGUNA -->
        <!-- ============================================================ -->
        <div x-show="openCreateModal" 
             x-transition
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/70 backdrop-blur-sm"
             style="display: none;">
            <div @click.away="openCreateModal = false" class="bg-white dark:bg-zinc-900 w-full max-w-md rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                    <h3 class="font-extrabold text-zinc-900 dark:text-white text-base flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600">person_add</span>
                        <span>Tambah Anggota Tim Internal</span>
                    </h3>
                    <button @click="openCreateModal = false" class="text-zinc-400 hover:text-zinc-600">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('users.store') }}" class="space-y-3">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: Ryan Pratama"
                               class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Alamat Email Login <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required placeholder="user@rzdigitalcreative.com"
                               class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Peran Akses (Role) <span class="text-rose-500">*</span></label>
                        <select name="role" required class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-medium">
                            <option value="sales">💼 Sales (Kelola Prospek &amp; Chat WA)</option>
                            <option value="project_manager">🚀 Project Manager (Kelola Status Proyek)</option>
                            <option value="finance">💰 Finance (Kelola Invoice &amp; Pembayaran)</option>
                            <option value="admin">👑 Administrator (Akses Penuh Seluruh Sistem)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required placeholder="Minimal 8 karakter"
                               class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Konfirmasi Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                               class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white">
                    </div>

                    <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 flex justify-end gap-2">
                        <button type="button" @click="openCreateModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold border text-zinc-600">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white">Simpan Akun</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- MODAL EDIT AKUN PENGGUNA -->
        <!-- ============================================================ -->
        <div x-show="openEditModal" 
             x-transition
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/70 backdrop-blur-sm"
             style="display: none;">
            <div @click.away="openEditModal = false" class="bg-white dark:bg-zinc-900 w-full max-w-md rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                    <h3 class="font-extrabold text-zinc-900 dark:text-white text-base">Ubah Data Akun &amp; Peran</h3>
                    <button @click="openEditModal = false" class="text-zinc-400 hover:text-zinc-600">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>

                <form method="POST" :action="'/users/' + editUser.id" class="space-y-3">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="editUser.name" required
                               class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Alamat Email Login</label>
                        <input type="email" name="email" x-model="editUser.email" required
                               class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Peran Akses (Role)</label>
                        <select name="role" x-model="editRole" class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white font-medium">
                            <option value="sales">💼 Sales (Kelola Prospek &amp; Chat WA)</option>
                            <option value="project_manager">🚀 Project Manager (Kelola Status Proyek)</option>
                            <option value="finance">💰 Finance (Kelola Invoice &amp; Pembayaran)</option>
                            <option value="admin">👑 Administrator (Akses Penuh Seluruh Sistem)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Password Baru (Opsional)</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak ingin diubah"
                               class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 dark:text-zinc-300 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                               class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs text-zinc-900 dark:text-white">
                    </div>

                    <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 flex justify-end gap-2">
                        <button type="button" @click="openEditModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold border text-zinc-600">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
