<x-app-layout>
    @php
        $vehicles = $featuredVehicles ?? \App\Models\Vehicle::where('status', 'available')
            ->latest()
            ->take(3)
            ->get();

        $stats = [
            'vehicles' => \App\Models\Vehicle::where('status', 'available')->count(),
            'clients'  => \App\Models\User::role('client')->count(),
        ];
    @endphp

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- HERO --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="-mt-24 relative overflow-hidden bg-gradient-to-b from-[#FFF2BA]/50 via-[#FFF2BA]/10 to-white dark:from-[#0F3C65] dark:via-[#0a2a4a] dark:to-gray-950">
        {{-- Decorative blobs --}}
        <div class="absolute top-20 left-1/4 w-96 h-96 bg-[#0F3C65]/10 dark:bg-[#FFF2BA]/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-[#FFF2BA]/40 dark:bg-[#0F3C65] rounded-full blur-[150px] opacity-40 pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-6 pt-40 pb-28 text-center">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 mb-8 rounded-full border border-[#0F3C65]/20 bg-[#0F3C65]/5 text-[#0F3C65] dark:border-[#FFF2BA]/20 dark:bg-[#FFF2BA]/5 dark:text-[#FFF2BA] text-sm">
                <span class="w-2 h-2 rounded-full bg-[#0F3C65] dark:bg-[#FFF2BA] animate-pulse"></span>
                Nouveau : véhicules électriques disponibles
            </div>

            {{-- Headline --}}
            <h1 class="text-5xl md:text-7xl font-bold text-[#0F3C65] dark:text-white tracking-tight leading-[1.05] mb-6">
                Roulez en toute
                <span class="inline-block bg-gradient-to-r from-[#0F3C65] via-[#0a2a4a] to-[#0F3C65] dark:from-[#FFF2BA] dark:via-[#f5e2a0] dark:to-[#FFF2BA] bg-clip-text text-transparent">
                    liberté
                </span>
            </h1>

            <p class="text-lg md:text-xl text-gray-600 dark:text-white/70 max-w-2xl mx-auto mb-10 leading-relaxed">
                Location de voitures premium au Maroc.
                Réservation en 2 minutes, véhicules vérifiés, prix transparents.
            </p>

            {{-- CTAs --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('vehicles.index') }}"
                   class="group inline-flex items-center gap-2 px-8 py-4 rounded-full bg-[#0F3C65] text-white dark:bg-[#FFF2BA] dark:text-[#0F3C65] font-semibold text-base shadow-[0_0_40px_rgba(15,60,101,0.3)] dark:shadow-[0_0_40px_rgba(255,242,186,0.3)] hover:shadow-[0_0_50px_rgba(15,60,101,0.4)] dark:hover:shadow-[0_0_50px_rgba(255,242,186,0.5)] hover:bg-[#0a2a4a] dark:hover:bg-white transition-all duration-300">
                    Découvrir les véhicules
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>

                @guest
                    <a href="{{ route('register') }}"
                       class="px-8 py-4 rounded-full border border-[#0F3C65]/20 dark:border-white/20 text-[#0F3C65] dark:text-white font-medium hover:bg-[#0F3C65]/5 dark:hover:bg-white/10 transition-colors duration-300">
                        Créer un compte gratuit
                    </a>
                @else
                    <a href="{{ route('dashboard') }}"
                       class="px-8 py-4 rounded-full border border-[#0F3C65]/20 dark:border-white/20 text-[#0F3C65] dark:text-white font-medium hover:bg-[#0F3C65]/5 dark:hover:bg-white/10 transition-colors duration-300">
                        Accéder à mon espace
                    </a>
                @endguest
            </div>

            {{-- Trust line --}}
            <p class="mt-10 text-sm text-gray-500 dark:text-white/40">
                ✓ Sans frais cachés &nbsp; · &nbsp; ✓ Annulation gratuite 24h &nbsp; · &nbsp; ✓ Assurance incluse
            </p>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- STATS BAR --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="bg-white dark:bg-gray-950 border-y border-gray-200 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-4xl font-bold text-[#0F3C65] dark:text-[#FFF2BA]">{{ $stats['vehicles'] }}+</div>
                <div class="text-sm text-gray-500 dark:text-white/50 mt-2 uppercase tracking-wider">Véhicules disponibles</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-[#0F3C65] dark:text-[#FFF2BA]">{{ $stats['clients'] }}+</div>
                <div class="text-sm text-gray-500 dark:text-white/50 mt-2 uppercase tracking-wider">Clients satisfaits</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-[#0F3C65] dark:text-[#FFF2BA]">24/7</div>
                <div class="text-sm text-gray-500 dark:text-white/50 mt-2 uppercase tracking-wider">Support client</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-[#0F3C65] dark:text-[#FFF2BA]">4.9★</div>
                <div class="text-sm text-gray-500 dark:text-white/50 mt-2 uppercase tracking-wider">Note moyenne</div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- FEATURED VEHICLES --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section id="featured-vehicles" class="bg-gray-50 dark:bg-gray-950 py-24">
        <div class="max-w-7xl mx-auto px-6">

            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12">
                <div>
                    <p class="text-[#0F3C65] dark:text-[#FFF2BA] text-sm font-medium uppercase tracking-wider mb-2">Notre sélection</p>
                    <h2 class="text-4xl md:text-5xl font-bold text-[#0F3C65] dark:text-white tracking-tight">
                        Véhicules populaires
                    </h2>
                    <p class="text-gray-600 dark:text-white/50 mt-3 max-w-xl">
                        Découvrez notre flotte premium, entretenue et vérifiée avant chaque location.
                    </p>
                </div>

                <a href="{{ route('vehicles.index') }}"
                   class="group inline-flex items-center gap-2 text-[#0F3C65] dark:text-[#FFF2BA] hover:text-[#0a2a4a] dark:hover:text-white transition-colors shrink-0">
                    Voir tout
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            @if ($vehicles->isEmpty())
                <div class="text-center py-16 rounded-2xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5">
                    <p class="text-gray-500 dark:text-white/50">Aucun véhicule disponible pour le moment.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($vehicles as $vehicle)
                        <a href="{{ route('vehicles.show', $vehicle) }}"
                           class="group block overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/10 dark:bg-white/5 hover:border-[#0F3C65]/30 dark:hover:bg-white/10 dark:hover:border-[#FFF2BA]/30 shadow-sm hover:shadow-md dark:shadow-none transition-all duration-300 hover:-translate-y-1">

                            {{-- Image --}}
                            <div class="relative aspect-[4/3] overflow-hidden bg-gray-100 dark:bg-gray-900">
                                @if ($vehicle->image_path)
                                    <img src="{{ asset('storage/' . $vehicle->image_path) }}"
                                         alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[#0F3C65] to-gray-900 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Fuel badge --}}
                                <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-black/60 backdrop-blur-sm text-xs font-medium text-white">
                                    {{ ucfirst($vehicle->fuel_type) }}
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="text-xl font-bold text-[#0F3C65] dark:text-white group-hover:text-[#0F3C65] dark:group-hover:text-[#FFF2BA] transition-colors">
                                            {{ $vehicle->brand }} {{ $vehicle->model }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-white/40 mt-1">
                                            {{ $vehicle->year }} · {{ ucfirst($vehicle->transmission) }} · {{ $vehicle->seats }} places
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-end justify-between mt-5 pt-5 border-t border-gray-100 dark:border-white/10">
                                    <div>
                                        <span class="text-2xl font-bold text-[#0F3C65] dark:text-[#FFF2BA]">
                                            {{ number_format($vehicle->daily_rate, 0) }}
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-white/50 ml-1">MAD / jour</span>
                                    </div>
                                    <span class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-white/60 group-hover:text-[#0F3C65] dark:group-hover:text-[#FFF2BA] transition-colors">
                                        Réserver
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- HOW IT WORKS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section id="how-it-works" class="bg-white dark:bg-gray-950 py-24 border-t border-gray-200 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-16">
                <p class="text-[#0F3C65] dark:text-[#FFF2BA] text-sm font-medium uppercase tracking-wider mb-2">Comment ça marche</p>
                <h2 class="text-4xl md:text-5xl font-bold text-[#0F3C65] dark:text-white tracking-tight">
                    Trois étapes, c'est tout
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $steps = [
                        ['num' => '01', 'title' => 'Choisissez votre véhicule', 'desc' => 'Parcourez notre flotte, filtrez par marque, prix, carburant ou transmission.'],
                        ['num' => '02', 'title' => 'Réservez en ligne',        'desc' => 'Sélectionnez vos dates, vérifiez la disponibilité et confirmez en quelques clics.'],
                        ['num' => '03', 'title' => 'Récupérez les clés',       'desc' => 'Présentez-vous à l\'agence, signez le contrat et prenez la route.'],
                    ];
                @endphp

                @foreach ($steps as $step)
                    <div class="relative p-8 rounded-2xl border border-gray-200 bg-gray-50 dark:border-white/10 dark:bg-white/5 hover:border-[#0F3C65]/30 dark:hover:bg-white/[0.07] transition-colors">
                        <div class="text-6xl font-bold text-[#0F3C65]/10 dark:text-[#FFF2BA]/20 leading-none mb-4">
                            {{ $step['num'] }}
                        </div>
                        <h3 class="text-xl font-bold text-[#0F3C65] dark:text-white mb-3">{{ $step['title'] }}</h3>
                        <p class="text-gray-600 dark:text-white/60 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- WHY US --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="bg-gray-50 dark:bg-gray-950 py-24 border-t border-gray-200 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-16">
                <p class="text-[#0F3C65] dark:text-[#FFF2BA] text-sm font-medium uppercase tracking-wider mb-2">Pourquoi CarRental</p>
                <h2 class="text-4xl md:text-5xl font-bold text-[#0F3C65] dark:text-white tracking-tight">
                    L'excellence à chaque kilomètre
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $features = [
                        ['icon' => 'shield', 'title' => 'Assurance incluse', 'desc' => 'Tous nos véhicules sont couverts, sans frais supplémentaires.'],
                        ['icon' => 'money',  'title' => 'Prix transparents',  'desc' => 'Pas de frais cachés. Ce que vous voyez, c\'est ce que vous payez.'],
                        ['icon' => 'clock',  'title' => 'Annulation gratuite','desc' => 'Changez d\'avis jusqu\'à 24h avant la prise en charge.'],
                        ['icon' => 'headset','title' => 'Support 24/7',       'desc' => 'Une équipe disponible à tout moment, par téléphone ou email.'],
                    ];
                @endphp

                @foreach ($features as $feature)
                    <div class="p-6 rounded-2xl border border-gray-200 bg-white dark:border-white/10 dark:bg-white/5 hover:border-[#0F3C65]/30 dark:hover:border-[#FFF2BA]/30 shadow-sm dark:shadow-none transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-[#0F3C65]/5 dark:bg-[#FFF2BA]/10 flex items-center justify-center mb-4">
                            @switch($feature['icon'])
                                @case('shield')
                                    <svg class="w-6 h-6 text-[#0F3C65] dark:text-[#FFF2BA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    @break
                                @case('money')
                                    <svg class="w-6 h-6 text-[#0F3C65] dark:text-[#FFF2BA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    @break
                                @case('clock')
                                    <svg class="w-6 h-6 text-[#0F3C65] dark:text-[#FFF2BA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    @break
                                @case('headset')
                                    <svg class="w-6 h-6 text-[#0F3C65] dark:text-[#FFF2BA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    @break
                            @endswitch
                        </div>
                        <h3 class="text-lg font-bold text-[#0F3C65] dark:text-white mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-sm text-gray-600 dark:text-white/60 leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- CTA --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-[#0F3C65] dark:bg-gradient-to-br dark:from-[#0F3C65] dark:to-[#0a2a4a] py-24">
        <div class="absolute inset-0 opacity-[0.05]" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 24px 24px;"></div>

        <div class="relative max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 tracking-tight">
                Prêt à prendre la route ?
            </h2>
            <p class="text-lg text-white/70 mb-10 max-w-xl mx-auto">
                Rejoignez des milliers de clients qui nous font confiance pour leurs déplacements au Maroc.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @guest
                    <a href="{{ route('register') }}"
                       class="px-8 py-4 rounded-full bg-[#FFF2BA] text-[#0F3C65] font-semibold shadow-[0_0_40px_rgba(255,242,186,0.3)] hover:bg-white hover:shadow-[0_0_50px_rgba(255,242,186,0.5)] transition-all duration-300">
                        Créer un compte
                    </a>
                    <a href="{{ route('vehicles.index') }}"
                       class="px-8 py-4 rounded-full border border-white/20 text-white hover:bg-white/10 transition-colors">
                        Parcourir les véhicules
                    </a>
                @else
                    <a href="{{ route('vehicles.index') }}"
                       class="px-8 py-4 rounded-full bg-[#FFF2BA] text-[#0F3C65] font-semibold shadow-[0_0_40px_rgba(255,242,186,0.3)] hover:bg-white transition-colors">
                        Réserver maintenant
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- FOOTER --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <footer class="bg-white dark:bg-gray-950 border-t border-gray-200 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-6 py-16">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-full bg-[#0F3C65]/10 dark:bg-[#FFF2BA]/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#0F3C65] dark:text-[#FFF2BA]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.22.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99z"/>
                            </svg>
                        </div>
                        <span class="text-[#0F3C65] dark:text-white font-semibold">CarRental</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-white/50">
                        Location de voitures premium au Maroc. Réservation simple, flotte vérifiée.
                    </p>
                </div>

                <div>
                    <h4 class="text-[#0F3C65] dark:text-white font-semibold text-sm mb-4 uppercase tracking-wider">Navigation</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('vehicles.index') }}" class="text-gray-500 dark:text-white/50 hover:text-[#0F3C65] dark:hover:text-[#FFF2BA] transition-colors">Véhicules</a></li>
                        <li><a href="#" class="text-gray-500 dark:text-white/50 hover:text-[#0F3C65] dark:hover:text-[#FFF2BA] transition-colors">À propos</a></li>
                        <li><a href="#" class="text-gray-500 dark:text-white/50 hover:text-[#0F3C65] dark:hover:text-[#FFF2BA] transition-colors">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-[#0F3C65] dark:text-white font-semibold text-sm mb-4 uppercase tracking-wider">Compte</h4>
                    <ul class="space-y-2 text-sm">
                        @guest
                            <li><a href="{{ route('login') }}" class="text-gray-500 dark:text-white/50 hover:text-[#0F3C65] dark:hover:text-[#FFF2BA] transition-colors">Connexion</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-500 dark:text-white/50 hover:text-[#0F3C65] dark:hover:text-[#FFF2BA] transition-colors">Inscription</a></li>
                        @else
                            <li><a href="{{ route('dashboard') }}" class="text-gray-500 dark:text-white/50 hover:text-[#0F3C65] dark:hover:text-[#FFF2BA] transition-colors">Mon espace</a></li>
                            <li><a href="{{ route('profile.edit') }}" class="text-gray-500 dark:text-white/50 hover:text-[#0F3C65] dark:hover:text-[#FFF2BA] transition-colors">Profil</a></li>
                        @endguest
                    </ul>
                </div>

                <div>
                    <h4 class="text-[#0F3C65] dark:text-white font-semibold text-sm mb-4 uppercase tracking-wider">Légal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-500 dark:text-white/50 hover:text-[#0F3C65] dark:hover:text-[#FFF2BA] transition-colors">CGU</a></li>
                        <li><a href="#" class="text-gray-500 dark:text-white/50 hover:text-[#0F3C65] dark:hover:text-[#FFF2BA] transition-colors">Confidentialité</a></li>
                        <li><a href="#" class="text-gray-500 dark:text-white/50 hover:text-[#0F3C65] dark:hover:text-[#FFF2BA] transition-colors">Mentions légales</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200 dark:border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-500 dark:text-white/40">
                    © {{ date('Y') }} Aya Car. Tous droits réservés.
                </p>
                <p class="text-sm text-gray-500 dark:text-white/40">
                    Fait avec <span class="text-[#0F3C65] dark:text-[#FFF2BA]">♥</span> au Maroc
                </p>
            </div>
        </div>
    </footer>
</x-app-layout>