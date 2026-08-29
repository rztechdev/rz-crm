<!-- Mobile Backdrop Overlay -->
<div x-show="sidebarOpen" 
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-zinc-900/40 dark:bg-zinc-950/80 backdrop-blur-md z-40 lg:hidden" 
     @click="sidebarOpen = false"
     style="display: none;">
</div>

<!-- Sidebar Aside Container (Fixed w-64 on desktop, Drawer on mobile) -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" 
       class="fixed top-0 left-0 z-50 h-screen w-64 bg-white/95 dark:bg-zinc-950/95 backdrop-blur-xl border-r border-zinc-200/80 dark:border-zinc-900/80 -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out flex flex-col shadow-[1px_0_10px_rgba(0,0,0,0.015)] dark:shadow-[1px_0_15px_rgba(0,0,0,0.2)]">
    
    <!-- Branding Header -->
    <div class="h-16 shrink-0 flex items-center justify-between px-6 border-b border-zinc-200/60 dark:border-zinc-800/60 bg-white/50 dark:bg-zinc-950/50 backdrop-blur-md">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group font-sans">
            <img src="{{ asset('images/logo_rz_teks.png') }}" alt="RZ Digital Creative Logo" class="h-8 w-auto object-contain brightness-0 dark:brightness-100 group-hover:scale-105 transition-transform duration-150">
            <div class="flex flex-col">
                <span class="text-sm font-black text-zinc-900 dark:text-white tracking-tight leading-none">RZ CRM</span>
                <span class="text-[9px] font-mono text-zinc-400 dark:text-zinc-500 font-bold uppercase tracking-wider mt-0.5">Digital Creative</span>
            </div>
        </a>
        
        <button @click="sidebarOpen = false" class="lg:hidden p-2 text-zinc-400 dark:text-zinc-500 hover:text-zinc-600 dark:hover:text-white rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800/50 transition-colors">
            <span class="material-symbols-outlined text-[20px] block">close</span>
        </button>
    </div>

    <!-- Navigation Menu items -->
    <nav class="flex-1 overflow-y-auto custom-scrollbar py-6 space-y-1 px-4">
        
        <!-- 1. Dashboard -->
        @php $isDashboard = request()->routeIs('dashboard'); @endphp
        <a href="{{ route('dashboard') }}" 
           class="relative flex items-center px-4 py-2.5 justify-start gap-3 rounded-xl text-xs font-semibold transition-colors duration-150 group {{ $isDashboard ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 font-bold border border-emerald-500/10 dark:border-emerald-500/20 shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/70 dark:hover:bg-zinc-900/50 hover:text-zinc-900 dark:hover:text-zinc-100 border border-transparent' }}">
            @if($isDashboard)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-emerald-500 rounded-r-md"></span>
            @endif
            <span class="material-symbols-outlined text-[19px] {{ $isDashboard ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-zinc-100' }} shrink-0">dashboard</span>
            <span class="truncate">Dashboard</span>
        </a>

        <!-- 2. Leads & Pipeline -->
        @php 
            $isLeads = request()->routeIs('leads.*'); 
            $overdueCount = \App\Models\Lead::whereNotNull('follow_up_date')->where('follow_up_date', '<', now()->toDateString())->whereNotIn('status', ['deal', 'tidak_lanjut'])->count();
        @endphp
        <a href="{{ route('leads.index') }}" 
           class="relative flex items-center px-4 py-2.5 justify-start gap-3 rounded-xl text-xs font-semibold transition-colors duration-150 group {{ $isLeads ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 font-bold border border-emerald-500/10 dark:border-emerald-500/20 shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/70 dark:hover:bg-zinc-900/50 hover:text-zinc-900 dark:hover:text-zinc-100 border border-transparent' }}">
            @if($isLeads)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-emerald-500 rounded-r-md"></span>
            @endif
            <span class="material-symbols-outlined text-[19px] {{ $isLeads ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-zinc-100' }} shrink-0">group</span>
            <span class="truncate flex-1">Leads &amp; Pipeline</span>
            @if($overdueCount > 0)
                <span class="px-1.5 py-0.5 text-[10px] font-black rounded-full bg-rose-500 text-white leading-none">{{ $overdueCount }}</span>
            @endif
        </a>

        <!-- 3. Projects -->
        @php $isProjects = request()->routeIs('projects.*'); @endphp
        <a href="{{ route('projects.index') }}" 
           class="relative flex items-center px-4 py-2.5 justify-start gap-3 rounded-xl text-xs font-semibold transition-colors duration-150 group {{ $isProjects ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 font-bold border border-emerald-500/10 dark:border-emerald-500/20 shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/70 dark:hover:bg-zinc-900/50 hover:text-zinc-900 dark:hover:text-zinc-100 border border-transparent' }}">
            @if($isProjects)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-emerald-500 rounded-r-md"></span>
            @endif
            <span class="material-symbols-outlined text-[19px] {{ $isProjects ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-zinc-100' }} shrink-0">view_kanban</span>
            <span class="truncate">Proyek Website</span>
        </a>

        <!-- 4. Payments & Invoices -->
        @php $isPayments = request()->routeIs('payments.*'); @endphp
        <a href="{{ route('payments.index') }}" 
           class="relative flex items-center px-4 py-2.5 justify-start gap-3 rounded-xl text-xs font-semibold transition-colors duration-150 group {{ $isPayments ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 font-bold border border-emerald-500/10 dark:border-emerald-500/20 shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/70 dark:hover:bg-zinc-900/50 hover:text-zinc-900 dark:hover:text-zinc-100 border border-transparent' }}">
            @if($isPayments)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-emerald-500 rounded-r-md"></span>
            @endif
            <span class="material-symbols-outlined text-[19px] {{ $isPayments ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-zinc-100' }} shrink-0">payments</span>
            <span class="truncate">Pembayaran &amp; DP</span>
        </a>

        <!-- 5. Maintenance Subscriptions -->
        @php $isMaintenance = request()->routeIs('maintenance.*'); @endphp
        <a href="{{ route('maintenance.index') }}" 
           class="relative flex items-center px-4 py-2.5 justify-start gap-3 rounded-xl text-xs font-semibold transition-colors duration-150 group {{ $isMaintenance ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 font-bold border border-emerald-500/10 dark:border-emerald-500/20 shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/70 dark:hover:bg-zinc-900/50 hover:text-zinc-900 dark:hover:text-zinc-100 border border-transparent' }}">
            @if($isMaintenance)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-emerald-500 rounded-r-md"></span>
            @endif
            <span class="material-symbols-outlined text-[19px] {{ $isMaintenance ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-zinc-100' }} shrink-0">published_with_changes</span>
            <span class="truncate">Maintenance Bulanan</span>
        </a>

        <!-- 6. Message Logs -->
        @php $isMessages = request()->routeIs('messages.*'); @endphp
        <a href="{{ route('messages.index') }}" 
           class="relative flex items-center px-4 py-2.5 justify-start gap-3 rounded-xl text-xs font-semibold transition-colors duration-150 group {{ $isMessages ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 font-bold border border-emerald-500/10 dark:border-emerald-500/20 shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/70 dark:hover:bg-zinc-900/50 hover:text-zinc-900 dark:hover:text-zinc-100 border border-transparent' }}">
            @if($isMessages)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-emerald-500 rounded-r-md"></span>
            @endif
            <span class="material-symbols-outlined text-[19px] {{ $isMessages ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-zinc-100' }} shrink-0">chat</span>
            <span class="truncate">Riwayat Pesan</span>
        </a>

        <!-- Section: Pengaturan Internal -->
        <div class="flex items-center gap-2 px-4 py-2 mt-4 mb-2">
            <span class="text-[9px] font-bold font-mono text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Internal</span>
            <div class="h-px bg-zinc-200/60 dark:bg-zinc-800/60 flex-1"></div>
        </div>

        <!-- 7. User Management -->
        @php $isUsers = request()->routeIs('users.*'); @endphp
        <a href="{{ route('users.index') }}" 
           class="relative flex items-center px-4 py-2.5 justify-start gap-3 rounded-xl text-xs font-semibold transition-colors duration-150 group {{ $isUsers ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 font-bold border border-emerald-500/10 dark:border-emerald-500/20 shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100/70 dark:hover:bg-zinc-900/50 hover:text-zinc-900 dark:hover:text-zinc-100 border border-transparent' }}">
            @if($isUsers)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-emerald-500 rounded-r-md"></span>
            @endif
            <span class="material-symbols-outlined text-[19px] {{ $isUsers ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-900 dark:group-hover:text-zinc-100' }} shrink-0">manage_accounts</span>
            <span class="truncate">Kelola Pengguna</span>
        </a>
    </nav>
</aside>

<!-- Top Header Bar -->
<header class="fixed top-0 right-0 left-0 lg:left-64 h-16 bg-white/80 dark:bg-zinc-950/80 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-800/80 flex items-center justify-between px-4 sm:px-8 z-30">
    
    <div class="flex items-center gap-3">
        <!-- Mobile Drawer Toggle -->
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white focus:outline-none transition-colors">
            <span class="material-symbols-outlined text-[24px]">menu</span>
        </button>
        <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">
            RZ Digital Creative CRM
        </span>
    </div>

    <div class="flex items-center gap-2 sm:gap-4">
        
        <!-- Theme Toggle Button -->
        <button @click="toggleTheme()" 
                class="p-2 rounded-xl text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-zinc-100 dark:hover:bg-zinc-800/50 transition-colors focus:outline-none cursor-pointer"
                title="Ganti Tema">
            <span class="material-symbols-outlined text-[24px] block" x-show="!darkMode">light_mode</span>
            <span class="material-symbols-outlined text-[24px] block text-amber-400" x-show="darkMode" style="display: none;">dark_mode</span>
        </button>

        <!-- User Profile Dropdown -->
        <x-dropdown align="right" width="56" contentClasses="py-0 overflow-hidden bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-xl">
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
                    <x-dropdown-link :href="route('users.index')" class="flex items-center gap-2.5 px-3 py-2 text-sm text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-[18px] text-zinc-400 dark:text-zinc-500">group</span>
                        <span>{{ __('Kelola Pengguna') }}</span>
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2.5 px-3 py-2 text-sm text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-[18px] text-zinc-400 dark:text-zinc-500">manage_accounts</span>
                        <span>{{ __('Pengaturan Profil') }}</span>
                    </x-dropdown-link>
                </div>

                <div class="p-1.5 bg-white dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-800/50">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();" 
                                        class="flex items-center gap-2.5 px-3 py-2 text-sm text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-lg transition-colors font-bold">
                            <span class="material-symbols-outlined text-[18px]">logout</span>
                            <span>{{ __('Keluar Aplikasi') }}</span>
                        </x-dropdown-link>
                    </form>
                </div>
            </x-slot>
        </x-dropdown>
    </div>
</header>
