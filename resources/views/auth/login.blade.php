<x-guest-layout>
    <!-- Floating Card Wrapper (coordinates shadow & hover lift) -->
    <div class="w-full max-w-4xl relative group py-10 flex flex-col justify-center items-center">
        <!-- Realistic dynamic floor shadow -->
        <div class="absolute bottom-4 left-[10%] w-[80%] h-8 bg-zinc-950/20 dark:bg-black/60 rounded-full pointer-events-none transition-all duration-700 ease-out group-hover:opacity-10 group-hover:scale-x-75 group-hover:blur-[64px] animate-floor-shadow z-0"></div>

        <!-- Bobbing float animation container -->
        <div class="w-full relative z-10 animate-float">
            <!-- Interactive Lift & Glow Container -->
            <div id="auth-card" class="w-full super-glass border border-zinc-200/60 dark:border-zinc-800/60 rounded-[2rem] overflow-hidden transition-all duration-700 ease-out group-hover:-translate-y-4 group-hover:shadow-[0_45px_85px_-20px_rgba(0,0,0,0.22),_0_20px_40px_-25px_rgba(0,0,0,0.15),_0_0_60px_0px_rgba(139,155,112,0.12)] dark:group-hover:shadow-[0_55px_100px_-25px_rgba(0,0,0,0.8),_0_35px_60px_-30px_rgba(0,0,0,0.7),_0_0_65px_0px_rgba(139,155,112,0.08)] shadow-2xl">
        
                <div class="grid grid-cols-1 md:grid-cols-12 min-h-[500px]">
                
                <!-- Left Column: Colored Welcome Panel (Desktop: Left, Mobile: Top) -->
                <div id="green-panel" class="md:col-span-5 bg-emerald-600 dark:bg-emerald-700 text-white flex flex-col justify-center items-center text-center p-8 sm:p-10 relative overflow-hidden rounded-b-[2.5rem] md:rounded-b-none md:rounded-r-[6rem] lg:rounded-r-[8rem] shrink-0 min-h-[220px] md:min-h-none">
                    <!-- Background shapes inside colored panel -->
                    <div class="absolute top-[-20%] left-[-20%] w-60 h-60 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-[-10%] right-[-10%] w-52 h-52 bg-white/10 rounded-full blur-xl"></div>
                    
                    <div class="relative z-10 space-y-4 max-w-[280px]">
                        <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">Halo, Selamat Datang!</h2>
                        <p class="text-sm text-emerald-100 font-medium font-sans">Sistem Operasional &amp; CRM RZ Digital Creative.</p>
                        <div class="pt-2">
                            <span class="inline-block px-6 py-2 border-2 border-white/80 text-white text-xs font-bold rounded-xl tracking-wider uppercase font-mono bg-white/10 backdrop-blur-sm">
                                RZ CRM
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Login Form Content (Desktop: Right, Mobile: Bottom) -->
                <div id="form-panel" class="md:col-span-7 flex flex-col justify-center p-8 sm:p-10 lg:p-12">
                    
                    <div class="w-full max-w-md mx-auto space-y-6 flex flex-col">

                        <!-- Heading -->
                        <div class="text-left space-y-1">
                            <h3 class="text-3xl font-black text-zinc-900 dark:text-white tracking-tight">Masuk Akun</h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 font-medium">Masuk ke sistem RZ CRM untuk mengelola prospek, proyek website, dan klien.</p>
                        </div>

                        <x-auth-session-status class="mb-2" :status="session('status')" />

                        <!-- Form -->
                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf

                            <!-- Username / Email -->
                            <div class="space-y-1">
                                <label for="email" class="block text-xs font-bold font-mono tracking-wider text-zinc-500 uppercase">Alamat Email</label>
                                <div class="relative">
                                    <input id="email" type="email" name="email" value="{{ old('email', 'rzcompanyidn@gmail.com') }}" required autofocus autocomplete="username" placeholder="rzcompanyidn@gmail.com"
                                           class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-zinc-50 placeholder-zinc-400 dark:placeholder-zinc-600 focus:border-emerald-600 dark:focus:border-emerald-500 focus:ring focus:ring-emerald-600/20 dark:focus:ring-emerald-500/20 transition-all pl-4 pr-10 py-3 text-sm">
                                    <span class="material-symbols-outlined text-[20px] text-zinc-400 dark:text-zinc-600 absolute right-3.5 top-1/2 -translate-y-1/2 select-none pointer-events-none">person</span>
                                </div>
                                @error('email')
                                    <p class="text-rose-600 dark:text-rose-400 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="space-y-1">
                                <label for="password" class="block text-xs font-bold font-mono tracking-wider text-zinc-500 uppercase">Kata Sandi</label>
                                <div class="relative">
                                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                                           class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-zinc-50 placeholder-zinc-400 dark:placeholder-zinc-600 focus:border-emerald-600 dark:focus:border-emerald-500 focus:ring focus:ring-emerald-600/20 dark:focus:ring-emerald-500/20 transition-all pl-4 pr-10 py-3 text-sm">
                                    <span class="material-symbols-outlined text-[20px] text-zinc-400 dark:text-zinc-600 absolute right-3.5 top-1/2 -translate-y-1/2 select-none pointer-events-none">lock</span>
                                </div>
                                @error('password')
                                    <p class="text-rose-600 dark:text-rose-400 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remember & Forgot Password -->
                            <div class="flex items-center justify-between pt-1">
                                <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                                    <input id="remember_me" type="checkbox" name="remember" checked class="rounded bg-zinc-50 dark:bg-zinc-950 border-zinc-200 dark:border-zinc-800 text-emerald-600 dark:text-emerald-500 focus:ring-emerald-500/40 focus:ring-offset-zinc-50 dark:focus:ring-offset-zinc-900">
                                    <span class="ms-2 text-xs text-zinc-500 dark:text-zinc-400 font-semibold">Ingat Saya</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a class="text-xs text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors font-bold" href="{{ route('password.request') }}">
                                        Lupa Kata Sandi?
                                    </a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2">
                                <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-md shadow-emerald-600/10 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 dark:focus:ring-emerald-500 focus:ring-offset-white dark:focus:ring-offset-zinc-900 transition-all">
                                    Masuk Dashboard
                                </button>
                            </div>
                        </form>

                        <!-- Social Login Divider -->
                        <div class="relative py-2 select-none">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-zinc-150 dark:border-zinc-800/80"></div>
                            </div>
                            <div class="relative flex justify-center text-xs font-semibold uppercase">
                                <span class="bg-white dark:bg-zinc-900 px-3 text-zinc-400 dark:text-zinc-500 transition-colors">atau masuk melalui platform pengembang</span>
                            </div>
                        </div>

                        <!-- Social Icons Row -->
                        <div class="flex justify-center gap-3">
                            <!-- Google -->
                            <a href="#" class="w-10 h-10 border border-zinc-200 dark:border-zinc-800 hover:border-emerald-600 dark:hover:border-emerald-500 rounded-xl flex items-center justify-center transition-colors group">
                                <svg class="w-4 h-4 fill-zinc-500 dark:fill-zinc-400 group-hover:fill-emerald-600 dark:group-hover:fill-emerald-400 transition-colors" viewBox="0 0 24 24">
                                    <path d="M12.24 10.285V14.4h6.887c-.648 2.41-2.519 4.114-6.887 4.114-4.647 0-8.4-3.77-8.4-8.5s3.753-8.5 8.4-8.5c2.25 0 4.168.878 5.6 2.25l3.197-3.19C18.665.963 15.684 0 12.24 0 5.48 0 0 5.48 0 12.24s5.48 12.24 12.24 12.24c6.76 0 11.76-4.76 11.76-11.76 0-.796-.08-1.56-.24-2.435H12.24z"/>
                                </svg>
                            </a>
                            <!-- Facebook -->
                            <a href="#" class="w-10 h-10 border border-zinc-200 dark:border-zinc-800 hover:border-emerald-600 dark:hover:border-emerald-500 rounded-xl flex items-center justify-center transition-colors group">
                                <svg class="w-4 h-4 fill-zinc-500 dark:fill-zinc-400 group-hover:fill-emerald-600 dark:group-hover:fill-emerald-400 transition-colors" viewBox="0 0 24 24">
                                    <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                                </svg>
                            </a>
                            <!-- GitHub -->
                            <a href="#" class="w-10 h-10 border border-zinc-200 dark:border-zinc-800 hover:border-emerald-600 dark:hover:border-emerald-500 rounded-xl flex items-center justify-center transition-colors group">
                                <svg class="w-4 h-4 fill-zinc-500 dark:fill-zinc-400 group-hover:fill-emerald-600 dark:group-hover:fill-emerald-400 transition-colors" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.385.6.11.82-.26.82-.577v-2.234c-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.43.372.82 1.102.82 2.222v3.293c0 .319.22.694.825.576C20.565 21.795 24 17.3 24 12c0-6.63-5.37-12-12-12z"/>
                                </svg>
                            </a>
                            <!-- LinkedIn -->
                            <a href="#" class="w-10 h-10 border border-zinc-200 dark:border-zinc-800 hover:border-emerald-600 dark:hover:border-emerald-500 rounded-xl flex items-center justify-center transition-colors group">
                                <svg class="w-4 h-4 fill-zinc-500 dark:fill-zinc-400 group-hover:fill-emerald-600 dark:group-hover:fill-emerald-400 transition-colors" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-guest-layout>
