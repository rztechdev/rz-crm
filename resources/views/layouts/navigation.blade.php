<!-- ========================================================================= -->
<!-- 1. DESKTOP SIDEBAR (Visible ONLY on Desktop lg:flex) -->
<!-- ========================================================================= -->
<aside class="hidden lg:flex fixed top-0 left-0 z-50 h-screen w-64 bg-white/95 dark:bg-zinc-950/95 backdrop-blur-xl border-r border-zinc-200/80 dark:border-zinc-900/80 flex-col shadow-xs">
    
    <!-- Branding Header -->
    <div class="h-16 shrink-0 flex items-center justify-between px-6 border-b border-zinc-200/60 dark:border-zinc-800/60 bg-white/50 dark:bg-zinc-950/50 backdrop-blur-md">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group font-sans">
            <img src="{{ asset('images/logo_rz_teks.png') }}" alt="RZ Digital Creative Logo" class="h-8 w-auto object-contain brightness-0 dark:brightness-100 group-hover:scale-105 transition-transform duration-150">
            <div class="flex flex-col">
                <span class="text-sm font-black text-zinc-900 dark:text-white tracking-tight leading-none">RZ CRM</span>
                <span class="text-[9px] font-mono text-zinc-400 dark:text-zinc-500 font-bold uppercase tracking-wider mt-0.5">Digital Creative</span>
            </div>
        </a>
    </div>

    <!-- Navigation Menu items -->
    <nav class="flex-1 overflow-y-auto custom-scrollbar py-6 space-y-1 px-4">
        
        <!-- 1. Dashboard -->
        @php $isDashboard = request()->routeIs('dashboard'); @endphp
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-3.5 py-2.5 justify-start gap-3 rounded-lg text-xs transition-colors duration-150 group {{ $isDashboard ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900/70 hover:text-zinc-900 dark:hover:text-zinc-100 font-medium' }}">
            <span class="material-symbols-outlined text-[20px] {{ $isDashboard ? 'text-white' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-700 dark:group-hover:text-zinc-300' }} shrink-0">dashboard</span>
            <span class="truncate">Dashboard</span>
        </a>

        <!-- 2. Leads & Pipeline -->
        @php 
            $isLeads = request()->routeIs('leads.*'); 
            $overdueCount = \App\Models\Lead::whereNotNull('follow_up_date')->where('follow_up_date', '<', now()->toDateString())->whereNotIn('status', ['deal', 'tidak_lanjut'])->count();
        @endphp
        <a href="{{ route('leads.index') }}" 
           class="flex items-center px-3.5 py-2.5 justify-start gap-3 rounded-lg text-xs transition-colors duration-150 group {{ $isLeads ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900/70 hover:text-zinc-900 dark:hover:text-zinc-100 font-medium' }}">
            <span class="material-symbols-outlined text-[20px] {{ $isLeads ? 'text-white' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-700 dark:group-hover:text-zinc-300' }} shrink-0">group</span>
            <span class="truncate flex-1">Leads &amp; Pipeline</span>
            @if($overdueCount > 0)
                <span class="px-1.5 py-0.5 text-[10px] font-mono font-bold rounded-md {{ $isLeads ? 'bg-white/20 text-white' : 'bg-rose-500 text-white' }} leading-none">{{ $overdueCount }}</span>
            @endif
        </a>

        <!-- 3. Projects -->
        @php $isProjects = request()->routeIs('projects.*'); @endphp
        <a href="{{ route('projects.index') }}" 
           class="flex items-center px-3.5 py-2.5 justify-start gap-3 rounded-lg text-xs transition-colors duration-150 group {{ $isProjects ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900/70 hover:text-zinc-900 dark:hover:text-zinc-100 font-medium' }}">
            <span class="material-symbols-outlined text-[20px] {{ $isProjects ? 'text-white' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-700 dark:group-hover:text-zinc-300' }} shrink-0">view_kanban</span>
            <span class="truncate">Proyek Website</span>
        </a>

        <!-- 4. Payments & Invoices -->
        @php $isPayments = request()->routeIs('payments.*'); @endphp
        <a href="{{ route('payments.index') }}" 
           class="flex items-center px-3.5 py-2.5 justify-start gap-3 rounded-lg text-xs transition-colors duration-150 group {{ $isPayments ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900/70 hover:text-zinc-900 dark:hover:text-zinc-100 font-medium' }}">
            <span class="material-symbols-outlined text-[20px] {{ $isPayments ? 'text-white' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-700 dark:group-hover:text-zinc-300' }} shrink-0">payments</span>
            <span class="truncate">Pembayaran &amp; DP</span>
        </a>

        <!-- 5. Maintenance Subscriptions -->
        @php $isMaintenance = request()->routeIs('maintenance.*'); @endphp
        <a href="{{ route('maintenance.index') }}" 
           class="flex items-center px-3.5 py-2.5 justify-start gap-3 rounded-lg text-xs transition-colors duration-150 group {{ $isMaintenance ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900/70 hover:text-zinc-900 dark:hover:text-zinc-100 font-medium' }}">
            <span class="material-symbols-outlined text-[20px] {{ $isMaintenance ? 'text-white' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-700 dark:group-hover:text-zinc-300' }} shrink-0">published_with_changes</span>
            <span class="truncate">Maintenance Bulanan</span>
        </a>

        <!-- 6. Message Logs -->
        @php $isMessages = request()->routeIs('messages.*'); @endphp
        <a href="{{ route('messages.index') }}" 
           class="flex items-center px-3.5 py-2.5 justify-start gap-3 rounded-lg text-xs transition-colors duration-150 group {{ $isMessages ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900/70 hover:text-zinc-900 dark:hover:text-zinc-100 font-medium' }}">
            <span class="material-symbols-outlined text-[20px] {{ $isMessages ? 'text-white' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-700 dark:group-hover:text-zinc-300' }} shrink-0">chat</span>
            <span class="truncate">Riwayat Pesan</span>
        </a>

        <!-- Section: Pengaturan Internal -->
        <div class="flex items-center gap-2 px-3.5 py-2 mt-4 mb-2">
            <span class="text-[9px] font-bold font-mono text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Tata Kelola &amp; Tim</span>
            <div class="h-px bg-zinc-200/60 dark:bg-zinc-800/60 flex-1"></div>
        </div>

        <!-- 7. Activity Log Audit Trail -->
        @php $isActivity = request()->routeIs('activity-logs.*'); @endphp
        <a href="{{ route('activity-logs.index') }}" 
           class="flex items-center px-3.5 py-2.5 justify-start gap-3 rounded-lg text-xs transition-colors duration-150 group {{ $isActivity ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900/70 hover:text-zinc-900 dark:hover:text-zinc-100 font-medium' }}">
            <span class="material-symbols-outlined text-[20px] {{ $isActivity ? 'text-white' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-700 dark:group-hover:text-zinc-300' }} shrink-0">history</span>
            <span class="truncate">Activity &amp; Audit Log</span>
        </a>

        <!-- 8. User Management -->
        @php $isUsers = request()->routeIs('users.*'); @endphp
        <a href="{{ route('users.index') }}" 
           class="flex items-center px-3.5 py-2.5 justify-start gap-3 rounded-lg text-xs transition-colors duration-150 group {{ $isUsers ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900/70 hover:text-zinc-900 dark:hover:text-zinc-100 font-medium' }}">
            <span class="material-symbols-outlined text-[20px] {{ $isUsers ? 'text-white' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-700 dark:group-hover:text-zinc-300' }} shrink-0">manage_accounts</span>
            <span class="truncate">Kelola Tim &amp; Peran</span>
        </a>

        <!-- Quick Snippets Shortcut in Sidebar -->
        <div class="pt-4 px-2">
            <button @click="$dispatch('open-quick-snippets')" 
                    class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg bg-zinc-100 dark:bg-zinc-900 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 text-zinc-700 dark:text-zinc-300 hover:text-emerald-700 dark:hover:text-emerald-400 border border-zinc-200 dark:border-zinc-800 hover:border-emerald-500/40 text-xs font-bold transition-all shadow-xs">
                <span class="material-symbols-outlined text-[18px] text-emerald-600">content_paste</span>
                <span>Template Chat WA</span>
            </button>
        </div>
    </nav>
</aside>

<!-- ========================================================================= -->
<!-- 2. TOP HEADER BAR -->
<!-- ========================================================================= -->
<header class="fixed top-0 right-0 left-0 lg:left-64 h-16 bg-white/80 dark:bg-zinc-950/80 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-800/80 flex items-center justify-between px-4 sm:px-8 z-30">
    
    <div class="flex items-center gap-2.5">
        <a href="{{ route('dashboard') }}" class="lg:hidden flex items-center gap-2">
            <img src="{{ asset('images/logo_rz_teks.png') }}" alt="RZ Digital" class="h-6 w-auto brightness-0 dark:brightness-100">
            <span class="text-xs font-bold text-zinc-900 dark:text-white">RZ CRM</span>
        </a>
        <span class="hidden lg:inline text-xs font-semibold text-zinc-500 dark:text-zinc-400">
            RZ Digital Creative CRM
        </span>
    </div>

    <div class="flex items-center gap-2 sm:gap-4">
        
        <!-- Theme Toggle Button -->
        <button @click="toggleTheme()" 
                class="p-2 rounded-lg text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-zinc-100 dark:hover:bg-zinc-800/50 transition-colors focus:outline-none cursor-pointer"
                title="Ganti Tema">
            <span class="material-symbols-outlined text-[22px] block" x-show="!darkMode">light_mode</span>
            <span class="material-symbols-outlined text-[22px] block text-amber-400" x-show="darkMode" style="display: none;">dark_mode</span>
        </button>

        <!-- User Profile Dropdown -->
        <x-dropdown align="right" width="56" contentClasses="py-0 overflow-hidden bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg shadow-lg">
            <x-slot name="trigger">
                <button class="flex items-center gap-2.5 pl-3 sm:pl-4 border-l border-zinc-200 dark:border-zinc-800/80 focus:outline-none group">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors duration-150">{{ Auth::user()->name }}</div>
                        <div class="text-[9px] font-mono text-zinc-400 dark:text-zinc-500 mt-0.5">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=8B9B70&background=F6F8F3&bold=true" alt="Profile" 
                             class="w-8 h-8 rounded-full border border-zinc-200 dark:border-zinc-800 transition-all duration-200">
                        <span class="absolute bottom-0 right-0 w-2 h-2 bg-emerald-500 rounded-full border border-white dark:border-zinc-900"></span>
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="px-4 py-3 bg-zinc-50 dark:bg-zinc-950/40 sm:hidden border-b border-zinc-200 dark:border-zinc-800">
                    <p class="text-xs font-mono text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">Pengguna</p>
                    <p class="text-sm font-bold text-zinc-800 dark:text-zinc-200 truncate mt-0.5">{{ Auth::user()->name }}</p>
                </div>

                <div class="p-1.5 bg-white dark:bg-zinc-900">
                    <x-dropdown-link :href="route('users.index')" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white rounded-md transition-colors">
                        <span class="material-symbols-outlined text-[18px] text-zinc-400 dark:text-zinc-500">group</span>
                        <span>{{ __('Kelola Pengguna') }}</span>
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white rounded-md transition-colors">
                        <span class="material-symbols-outlined text-[18px] text-zinc-400 dark:text-zinc-500">manage_accounts</span>
                        <span>{{ __('Pengaturan Profil') }}</span>
                    </x-dropdown-link>
                </div>

                <div class="p-1.5 bg-white dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-800/50">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();" 
                                        class="flex items-center gap-2.5 px-3 py-2 text-xs text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-md transition-colors font-bold">
                            <span class="material-symbols-outlined text-[18px]">logout</span>
                            <span>{{ __('Keluar Aplikasi') }}</span>
                        </x-dropdown-link>
                    </form>
                </div>
            </x-slot>
        </x-dropdown>
    </div>
</header>

<!-- ========================================================================= -->
<!-- 3. MOBILE BOTTOM NAVIGATION & DRAWER -->
<!-- ========================================================================= -->
<div x-data="{ mobileMenuOpen: false }">
    
    <!-- Fixed Bottom Navigation Bar -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 dark:bg-zinc-950/95 backdrop-blur-lg border-t border-zinc-200 dark:border-zinc-800 h-16 flex items-center justify-around px-2 shadow-lg select-none">
        
        <!-- 1. Dashboard -->
        @php $isDashboardMobile = request()->routeIs('dashboard'); @endphp
        <a href="{{ route('dashboard') }}" 
           class="flex flex-col items-center justify-center flex-1 h-full py-1 transition-colors {{ $isDashboardMobile ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 font-medium' }}">
            <span class="material-symbols-outlined text-[22px] {{ $isDashboardMobile ? 'scale-110 font-bold' : '' }} transition-transform">dashboard</span>
            <span class="text-[10px] mt-0.5 tracking-tight">Dashboard</span>
        </a>

        <!-- 2. Leads & Pipeline -->
        @php $isLeadsMobile = request()->routeIs('leads.*'); @endphp
        <a href="{{ route('leads.index') }}" 
           class="flex flex-col items-center justify-center flex-1 h-full py-1 transition-colors relative {{ $isLeadsMobile ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 font-medium' }}">
            <span class="material-symbols-outlined text-[22px] {{ $isLeadsMobile ? 'scale-110 font-bold' : '' }} transition-transform">group</span>
            <span class="text-[10px] mt-0.5 tracking-tight">Leads</span>
            @php
                $overdueMobile = \App\Models\Lead::whereNotNull('follow_up_date')->where('follow_up_date', '<', now()->toDateString())->whereNotIn('status', ['deal', 'tidak_lanjut'])->count();
            @endphp
            @if($overdueMobile > 0)
                <span class="absolute top-2 right-4 w-2 h-2 rounded-full bg-rose-500"></span>
            @endif
        </a>

        <!-- 3. Projects -->
        @php $isProjectsMobile = request()->routeIs('projects.*'); @endphp
        <a href="{{ route('projects.index') }}" 
           class="flex flex-col items-center justify-center flex-1 h-full py-1 transition-colors {{ $isProjectsMobile ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 font-medium' }}">
            <span class="material-symbols-outlined text-[22px] {{ $isProjectsMobile ? 'scale-110 font-bold' : '' }} transition-transform">view_kanban</span>
            <span class="text-[10px] mt-0.5 tracking-tight">Proyek</span>
        </a>

        <!-- 4. Menu / Lainnya (Paling Kanan) -->
        @php 
            $isOtherActive = request()->routeIs('payments.*', 'maintenance.*', 'messages.*', 'activity-logs.*', 'users.*', 'profile.*'); 
        @endphp
        <button type="button" @click="mobileMenuOpen = true" 
                class="flex flex-col items-center justify-center flex-1 h-full py-1 transition-colors {{ $isOtherActive ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 font-medium' }}">
            <span class="material-symbols-outlined text-[22px]">grid_view</span>
            <span class="text-[10px] mt-0.5 tracking-tight">Menu</span>
        </button>
    </nav>

    <!-- Slide-Up Sheet Modal (Completely separate from Nav flex) -->
    <div x-show="mobileMenuOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex flex-col justify-end"
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-zinc-950/60 backdrop-blur-sm transition-opacity"
             x-show="mobileMenuOpen"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileMenuOpen = false"></div>

        <!-- Slide-Up Sheet Container -->
        <div class="relative bg-white dark:bg-zinc-900 rounded-t-2xl p-5 border-t border-zinc-200 dark:border-zinc-800 shadow-2xl space-y-4 max-h-[85vh] overflow-y-auto z-10"
             x-show="mobileMenuOpen"
             x-transition:enter="ease-out duration-250 transform"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full">
            
            <!-- Drawer Header Handle -->
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600 text-[22px]">apps</span>
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Menu Lainnya</h3>
                </div>
                <button type="button" @click="mobileMenuOpen = false" class="p-1 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 rounded-lg">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Grid Menu Items -->
            <div class="grid grid-cols-2 gap-2.5">
                <!-- 1. Pembayaran -->
                <a href="{{ route('payments.index') }}" 
                   class="flex items-center gap-2.5 p-3 rounded-xl border border-zinc-200/80 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950/50 hover:border-emerald-500 transition-colors {{ request()->routeIs('payments.*') ? 'ring-1 ring-emerald-500 font-bold' : '' }}">
                    <span class="material-symbols-outlined text-emerald-600 text-[20px]">payments</span>
                    <span class="text-xs text-zinc-800 dark:text-zinc-200">Pembayaran &amp; DP</span>
                </a>

                <!-- 2. Maintenance -->
                <a href="{{ route('maintenance.index') }}" 
                   class="flex items-center gap-2.5 p-3 rounded-xl border border-zinc-200/80 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950/50 hover:border-emerald-500 transition-colors {{ request()->routeIs('maintenance.*') ? 'ring-1 ring-emerald-500 font-bold' : '' }}">
                    <span class="material-symbols-outlined text-teal-600 text-[20px]">published_with_changes</span>
                    <span class="text-xs text-zinc-800 dark:text-zinc-200">Maintenance</span>
                </a>

                <!-- 3. Riwayat Pesan -->
                <a href="{{ route('messages.index') }}" 
                   class="flex items-center gap-2.5 p-3 rounded-xl border border-zinc-200/80 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950/50 hover:border-emerald-500 transition-colors {{ request()->routeIs('messages.*') ? 'ring-1 ring-emerald-500 font-bold' : '' }}">
                    <span class="material-symbols-outlined text-sky-600 text-[20px]">chat</span>
                    <span class="text-xs text-zinc-800 dark:text-zinc-200">Riwayat Pesan</span>
                </a>

                <!-- 4. Template Chat WA Modal Trigger -->
                <button type="button" @click="mobileMenuOpen = false; $dispatch('open-quick-snippets')" 
                        class="flex items-center gap-2.5 p-3 rounded-xl border border-zinc-200/80 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950/50 hover:border-emerald-500 text-left transition-colors">
                    <span class="material-symbols-outlined text-emerald-600 text-[20px]">content_paste</span>
                    <span class="text-xs text-zinc-800 dark:text-zinc-200">Template WA</span>
                </button>

                <!-- 5. Activity Log -->
                <a href="{{ route('activity-logs.index') }}" 
                   class="flex items-center gap-2.5 p-3 rounded-xl border border-zinc-200/80 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950/50 hover:border-emerald-500 transition-colors {{ request()->routeIs('activity-logs.*') ? 'ring-1 ring-emerald-500 font-bold' : '' }}">
                    <span class="material-symbols-outlined text-amber-500 text-[20px]">history</span>
                    <span class="text-xs text-zinc-800 dark:text-zinc-200">Audit &amp; Log</span>
                </a>

                <!-- 6. Kelola Pengguna -->
                <a href="{{ route('users.index') }}" 
                   class="flex items-center gap-2.5 p-3 rounded-xl border border-zinc-200/80 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950/50 hover:border-emerald-500 transition-colors {{ request()->routeIs('users.*') ? 'ring-1 ring-emerald-500 font-bold' : '' }}">
                    <span class="material-symbols-outlined text-indigo-500 text-[20px]">manage_accounts</span>
                    <span class="text-xs text-zinc-800 dark:text-zinc-200">Kelola Tim</span>
                </a>
            </div>

            <!-- Profile & Theme & Logout Section -->
            <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 space-y-2">
                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-950/60 text-xs text-zinc-700 dark:text-zinc-300">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">account_circle</span>
                        <span>Profil Saya ({{ Auth::user()->name }})</span>
                    </div>
                    <span class="material-symbols-outlined text-[16px] text-zinc-400">arrow_forward</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 p-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/30 text-xs font-bold text-rose-600 dark:text-rose-400">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

