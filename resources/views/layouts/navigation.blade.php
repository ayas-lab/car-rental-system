<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @guest
                        {{-- Visitor menu --}}
                        <x-nav-link :href="route('vehicles.index')" :active="request()->routeIs('vehicles.*')">
                            {{ __('Véhicules') }}
                        </x-nav-link>
                    @endguest

                    @auth
                        @if (auth()->user()->hasRole('admin'))
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Tableau de bord') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.vehicles.index')" :active="request()->routeIs('admin.vehicles.*')">
                                {{ __('Véhicules') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                {{ __('Utilisateurs') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.reservations.index')" :active="request()->routeIs('admin.reservations.*')">
                                {{ __('Réservations') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.invoices.index')" :active="request()->routeIs('admin.invoices.*')">
                                {{ __('Factures') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                                {{ __('Rapports') }}
                            </x-nav-link>
                        @elseif (auth()->user()->hasRole('agent'))
                            <x-nav-link :href="route('agent.dashboard')" :active="request()->routeIs('agent.dashboard')">
                                {{ __('Tableau de bord') }}
                            </x-nav-link>
                            <x-nav-link :href="route('agent.checkout.index')" :active="request()->routeIs('agent.checkout.*')">
                                {{ __('Check-out') }}
                            </x-nav-link>
                            <x-nav-link :href="route('agent.checkin.index')" :active="request()->routeIs('agent.checkin.*')">
                                {{ __('Check-in') }}
                            </x-nav-link>
                            <x-nav-link :href="route('agent.contracts.index')" :active="request()->routeIs('agent.contracts.*')">
                                {{ __('Contrats') }}
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')">
                                {{ __('Accueil') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.vehicles.index')" :active="request()->routeIs('vehicles.*')">
                                {{ __('Véhicules') }}
                            </x-nav-link>
                            <x-nav-link :href="route('client.reservations.index')" :active="request()->routeIs('client.reservations.*')">
                                {{ __('Mes réservations') }}
                            </x-nav-link>
                            <x-nav-link :href="route('client.invoices.index')" :active="request()->routeIs('client.invoices.*')">
                                {{ __('Mes factures') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    {{-- Notifications Bell --}}
                    @php
                        $unreadCount = auth()->user()->unreadNotifications->count();
                    @endphp

                    <div class="hidden sm:flex sm:items-center ms-4" x-data="{ open: false }">
                        <div class="relative">
                            <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                @if ($unreadCount > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg overflow-hidden z-50">
                                <div class="px-4 py-2 border-b font-semibold text-sm">
                                    Notifications
                                </div>
                                <div class="max-h-80 overflow-y-auto">
                                    @forelse (auth()->user()->notifications->take(10) as $notification)
                                        <div class="px-4 py-3 border-b text-sm hover:bg-gray-50
                                            {{ $notification->read_at ? '' : 'bg-indigo-50' }}">
                                            <p>{{ $notification->data['message'] ?? 'Notification' }}</p>
                                            <span class="text-xs text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    @empty
                                        <div class="px-4 py-6 text-center text-gray-500 text-sm">
                                            Aucune notification
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    {{-- Visitor buttons --}}
                    <a href="{{ route('login') }}"
                       class="text-sm text-gray-700 hover:text-gray-900 px-3 py-2">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}"
                       class="ml-2 text-sm px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Inscription
                    </a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @guest
                <x-responsive-nav-link :href="route('vehicles.index')" :active="request()->routeIs('vehicles.*')">
                    {{ __('Véhicules') }}
                </x-responsive-nav-link>
            @endguest

            @auth
                @if (auth()->user()->hasRole('admin'))
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Tableau de bord') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.vehicles.index')" :active="request()->routeIs('admin.vehicles.*')">
                        {{ __('Véhicules') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('Utilisateurs') }}
                    </x-responsive-nav-link>
                @elseif (auth()->user()->hasRole('agent'))
                    <x-responsive-nav-link :href="route('agent.dashboard')" :active="request()->routeIs('agent.dashboard')">
                        {{ __('Tableau de bord') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('agent.checkout.index')" :active="request()->routeIs('agent.checkout.*')">
                        {{ __('Check-out') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('agent.checkin.index')" :active="request()->routeIs('agent.checkin.*')">
                        {{ __('Check-in') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')">
                        {{ __('Accueil') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('client.reservations.index')" :active="request()->routeIs('client.reservations.*')">
                        {{ __('Mes réservations') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                               onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1 px-4">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Connexion') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Inscription') }}
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>