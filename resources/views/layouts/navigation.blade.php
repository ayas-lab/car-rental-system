{{-- <nav x-data="{ open: false }" class="fixed top-4 left-1/2 z-50 -translate-x-1/2 w-full max-w-7xl px-4"> --}}
<nav x-data="{ 
        open: false,
        activeSection: '{{ request()->routeIs('vehicles.*') ? 'vehicles' : 'home' }}',
        checkScroll() {
            // Only run scroll spy on the home page
            if (window.location.pathname !== '/' && window.location.pathname !== '/welcome') return;

            const scrollPosition = window.scrollY + 150; // Offset for the fixed nav
            const vehiclesSection = document.getElementById('featured-vehicles');
            const aboutSection = document.getElementById('how-it-works');

            if (aboutSection && scrollPosition >= aboutSection.offsetTop) {
                this.activeSection = 'about';
            } else if (vehiclesSection && scrollPosition >= vehiclesSection.offsetTop) {
                this.activeSection = 'vehicles';
            } else {
                this.activeSection = 'home';
            }
        }
     }" 
     x-init="checkScroll()"
     @scroll.window.throttle.100ms="checkScroll()"
     class="fixed top-4 left-1/2 z-50 -translate-x-1/2 w-full max-w-7xl px-4">

    {{-- Main floating glassmorphic navbar --}}
    {{-- Swapped: Light mode gets white/70, Dark mode gets gray-900/70 --}}
    <div class="pointer-events-auto mx-auto flex items-center justify-between rounded-full border border-gray-200/50 dark:border-white/20
                bg-white/70 dark:bg-gray-900/70
                px-2 py-2 shadow-lg shadow-gray-200/50 dark:shadow-black/20 backdrop-blur-xl backdrop-saturate-150">
        
        {{-- Logo --}}
        <div class="flex items-center justify-start">
            <a href="{{ route('home') }}" class="flex items-center gap-2 rounded-full px-3 py-1.5 text-gray-900 dark:text-white/90 transition-colors duration-200 hover:text-black dark:hover:text-white">
                <div class="flex h-7 w-7 items-center justify-center rounded-full bg-gray-900/5 dark:bg-white/20">
                    <img src="{{ asset('logo.png') }}" alt="AyaCar" class="h-7 w-7 rounded-full object-cover">
                </div>
                <span class="text-2xl font-bold italic tracking-wide bg-gradient-to-r from-[#0F3C65] to-[#1a5c99] dark:from-[#FFF2BA] dark:to-[#f5e2a0] bg-clip-text text-transparent" style="font-family: 'Playfair Display', serif;">
                    Aya Car
                </span>
            </a>
        </div>
        
        <div class="mx-2 hidden h-6 w-px bg-gray-300 dark:bg-white/20 md:block"></div>
        
        {{-- Desktop Nav Links --}}
        <div class="hidden items-center gap-1 md:flex">
            @guest
                {{-- Véhicules --}}
                <a href="{{ url('/#featured-vehicles') }}"
                class="rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200"
                :class="activeSection === 'vehicles' ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/80 dark:hover:text-white dark:hover:bg-white/10'">
                    Véhicules
                </a>

                {{-- À propos --}}
                <a href="{{ url('/#how-it-works') }}"
                class="rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200"
                :class="activeSection === 'about' ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/80 dark:hover:text-white dark:hover:bg-white/10'">
                    À propos
                </a>
            @endguest
            @auth
                @if (auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}"
                       class="rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/80 dark:hover:text-white dark:hover:bg-white/10' }}">Tableau de bord</a>
                    <a href="{{ route('admin.vehicles.index') }}"
                       class="rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.vehicles.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/80 dark:hover:text-white dark:hover:bg-white/10' }}">Véhicules</a>
                    <a href="{{ route('admin.users.index') }}"
                       class="rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/80 dark:hover:text-white dark:hover:bg-white/10' }}">Utilisateurs</a>
                    <a href="{{ route('admin.reservations.index') }}"
                       class="rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.reservations.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/80 dark:hover:text-white dark:hover:bg-white/10' }}">Réservations</a>
                    <a href="{{ route('admin.reports.index') }}"
                       class="rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/80 dark:hover:text-white dark:hover:bg-white/10' }}">Rapports</a>
                @elseif (auth()->user()->hasRole('agent'))
                    <a href="{{ route('agent.dashboard') }}"
                       class="rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('agent.dashboard') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/80 dark:hover:text-white dark:hover:bg-white/10' }}">Tableau de bord</a>
                    <a href="{{ route('agent.checkout.index') }}"
                       class="rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('agent.checkout.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/80 dark:hover:text-white dark:hover:bg-white/10' }}">Remises</a>
                    <a href="{{ route('agent.checkin.index') }}"
                       class="rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('agent.checkin.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/80 dark:hover:text-white dark:hover:bg-white/10' }}">Retours</a>
                    <a href="{{ route('agent.contracts.index') }}"
                       class="rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('agent.contracts.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/80 dark:hover:text-white dark:hover:bg-white/10' }}">Contrats</a>
                @else
                    <a href="{{ route('client.dashboard') }}"
                       class="rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('client.dashboard') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/80 dark:hover:text-white dark:hover:bg-white/10' }}">Accueil</a>
                    <a href="{{ route('vehicles.index') }}" 
                       class="rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('vehicles.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/80 dark:hover:text-white dark:hover:bg-white/10' }}">Véhicules</a>
                    <a href="{{ route('client.reservations.index') }}"
                       class="rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('client.reservations.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/80 dark:hover:text-white dark:hover:bg-white/10' }}">Mes réservations</a>
                    <a href="{{ route('client.invoices.index') }}"
                       class="rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('client.invoices.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/80 dark:hover:text-white dark:hover:bg-white/10' }}">Mes factures</a>
                @endif
            @endauth
        </div>
        
        <div class="mx-2 hidden h-6 w-px bg-gray-300 dark:bg-white/20 md:block"></div>
        
        {{-- Right Side Actions --}}
        <div class="flex items-center justify-end gap-2">
            
            {{-- Theme Toggle --}}
            <div x-data="{ 
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                    setTheme(mode) {
                        this.theme = mode;
                        localStorage.setItem('theme', mode);
                        if (mode === 'dark') {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    }
                }" 
                class="flex items-center rounded-full bg-gray-200/50 dark:bg-black/20 p-1 border border-gray-300/50 dark:border-white/10">
                
                {{-- Light Mode Button --}}
                <button type="button" @click="setTheme('light')" 
                        :class="theme === 'light' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-900 dark:text-white/50 dark:hover:text-white'"
                        class="flex items-center justify-center rounded-full p-1.5 transition-all duration-200"
                        title="Light Mode">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                        <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z"/>
                    </svg>
                </button>

                {{-- Dark Mode Button --}}
                <button type="button" @click="setTheme('dark')" 
                        :class="theme === 'dark' ? 'bg-gray-800 shadow-sm text-white' : 'text-gray-500 hover:text-gray-900 dark:text-white/50 dark:hover:text-white'"
                        class="flex items-center justify-center rounded-full p-1.5 transition-all duration-200"
                        title="Dark Mode">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            @guest
                <a href="{{ route('login') }}" 
                class="inline-flex items-center justify-center px-5 py-2 rounded-full bg-[#0F3C65] text-white dark:bg-[#FFF2BA] dark:text-[#0F3C65] font-semibold text-sm shadow-[0_0_20px_rgba(15,60,101,0.3)] dark:shadow-[0_0_20px_rgba(255,242,186,0.3)] hover:shadow-[0_0_30px_rgba(15,60,101,0.5)] dark:hover:shadow-[0_0_30px_rgba(255,242,186,0.5)] hover:bg-[#0a2a4a] dark:hover:bg-white transition-all duration-300">
                    Connexion
                </a>
            @endguest
            
            @auth
                @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                
                {{-- Notification Bell --}}
                <div x-data="{ bellOpen: false }" class="relative">
                    <button @click="bellOpen = !bellOpen" class="relative flex items-center justify-center rounded-full p-2 text-gray-600 transition-colors duration-200 hover:text-gray-900 dark:text-white/80 dark:hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        @if ($unreadCount > 0)
                            <span class="absolute top-0 right-0 inline-flex h-4 min-w-4 items-center justify-center rounded-full bg-red-600 px-1 text-xs font-bold leading-none text-white">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>
                    
                    {{-- Notification Dropdown --}}
                    <div x-show="bellOpen" @click.away="bellOpen = false" x-cloak class="absolute right-0 mt-2 w-80 overflow-hidden rounded-2xl border border-gray-200 dark:border-white/20 bg-white dark:bg-gray-900 shadow-xl backdrop-blur-xl backdrop-saturate-150">
                        <div class="border-b border-gray-100 dark:border-white/10 px-4 py-2 text-sm font-medium text-gray-900 dark:text-white/90">Notifications</div>
                        <div class="max-h-80 overflow-y-auto">
                            @php $notifications = auth()->user()->notifications->take(10); @endphp
                            @if ($notifications->isEmpty())
                                <div class="px-4 py-6 text-center text-sm text-gray-500 dark:text-white/50">Aucune notification</div>
                            @else
                                @foreach ($notifications as $notification)
                                    <div class="px-4 py-3 border-b border-gray-100 dark:border-white/5 text-sm transition {{ $notification->read_at ? '' : 'bg-indigo-50/50 dark:bg-white/5' }}">
                                        <p class="text-gray-800 dark:text-white/90">{{ $notification->data['message'] ?? 'Notification' }}</p>
                                        <span class="text-xs text-gray-500 dark:text-white/40">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- User Dropdown --}}
                <div x-data="{ userOpen: false }" @click.away="userOpen = false" class="relative">
                    <button @click="userOpen = !userOpen" class="flex items-center gap-1 rounded-full px-3 py-2 text-sm font-medium text-gray-700 transition-colors duration-200 hover:text-gray-900 dark:text-white/90 dark:hover:text-white">
                        <span>{{ auth()->user()->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                    </button>
                    <div x-show="userOpen" x-cloak class="absolute right-0 mt-2 w-48 overflow-hidden rounded-2xl border border-gray-200 dark:border-white/20 bg-white dark:bg-gray-900 shadow-xl backdrop-blur-xl backdrop-saturate-150">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-white/90 transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-white/10">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700 dark:text-white/90 transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-white/10">Log Out</button>
                        </form>
                    </div>
                </div>
            @endauth
            
            {{-- Hamburger --}}
            <button @click="open = !open" class="flex items-center justify-center rounded-full p-2 text-gray-600 transition-colors duration-200 hover:text-gray-900 dark:text-white/80 dark:hover:text-white md:hidden">
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
    
    {{-- Mobile Menu --}}
    <div x-show="open" x-cloak class="mt-2 w-full rounded-2xl border border-gray-200/50 dark:border-white/20 bg-white/80 dark:bg-gray-900/90 p-4 shadow-xl backdrop-blur-xl backdrop-saturate-150 md:hidden">
        <div class="flex flex-col gap-1">
            @guest
                {{-- Véhicules --}}
                <a href="{{ url('/#featured-vehicles') }}"
                class="rounded-full px-4 py-2 text-sm font-medium transition-colors duration-200"
                :class="activeSection === 'vehicles' ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10'">
                    Véhicules
                </a>

                {{-- À propos --}}
                <a href="{{ url('/#how-it-works') }}"
                class="rounded-full px-4 py-2 text-sm font-medium transition-colors duration-200"
                :class="activeSection === 'about' ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10'">
                    À propos
                </a>

                {{-- Connexion Button --}}
                <a href="{{ route('login') }}" 
                class="mt-2 inline-flex items-center justify-center w-full px-5 py-2.5 rounded-full bg-[#0F3C65] text-white dark:bg-[#FFF2BA] dark:text-[#0F3C65] font-semibold text-sm shadow-[0_0_20px_rgba(15,60,101,0.3)] dark:shadow-[0_0_20px_rgba(255,242,186,0.3)] hover:bg-[#0a2a4a] dark:hover:bg-white transition-all duration-300">
                    Connexion
                </a>
            @endguest
            @auth
                @if (auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}"
                       class="rounded-full px-4 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10' }}">Tableau de bord</a>
                    <a href="{{ route('admin.vehicles.index') }}"
                       class="rounded-full px-4 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.vehicles.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10' }}">Véhicules</a>
                    <a href="{{ route('admin.users.index') }}"
                       class="rounded-full px-4 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10' }}">Utilisateurs</a>
                    <a href="{{ route('admin.reservations.index') }}"
                       class="rounded-full px-4 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.reservations.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10' }}">Réservations</a>
                    <a href="{{ route('admin.reports.index') }}"
                       class="rounded-full px-4 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10' }}">Rapports</a>
                @elseif (auth()->user()->hasRole('agent'))
                    <a href="{{ route('agent.dashboard') }}"
                       class="rounded-full px-4 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('agent.dashboard') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10' }}">Tableau de bord</a>
                    <a href="{{ route('agent.checkout.index') }}"
                       class="rounded-full px-4 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('agent.checkout.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10' }}">Remises</a>
                    <a href="{{ route('agent.checkin.index') }}"
                       class="rounded-full px-4 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('agent.checkin.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10' }}">Retours</a>
                    <a href="{{ route('agent.contracts.index') }}"
                       class="rounded-full px-4 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('agent.contracts.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10' }}">Contrats</a>
                @else
                    <a href="{{ route('client.dashboard') }}"
                       class="rounded-full px-4 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('client.dashboard') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10' }}">Accueil</a>
                    <a href="{{ route('vehicles.index') }}" 
                        class="rounded-full px-4 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('vehicles.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10' }}">Véhicules</a>
                    <a href="{{ route('client.reservations.index') }}"
                       class="rounded-full px-4 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('client.reservations.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10' }}">Mes réservations</a>
                    <a href="{{ route('client.invoices.index') }}"
                       class="rounded-full px-4 py-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('client.invoices.*') ? 'bg-[#0F3C65] text-[#FFF2BA] shadow-[0_0_20px_rgba(15,60,101,0.6)] dark:bg-[#FFF2BA] dark:text-[#0F3C65] dark:shadow-[0_0_20px_rgba(255,242,186,0.35)]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10' }}">Mes factures</a>
                @endif
                <div class="mt-4 border-t border-gray-200 dark:border-white/20 pt-4">
                    <div class="px-2 py-1"><div class="text-sm font-medium text-gray-900 dark:text-white/90">{{ auth()->user()->name }}</div></div>
                    <a href="{{ route('profile.edit') }}" class="mt-2 rounded-full px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10 transition-colors duration-200">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mt-1 w-full rounded-full px-4 py-2 text-left text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-white/90 dark:hover:text-white dark:hover:bg-white/10 transition-colors duration-200">Log Out</button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>