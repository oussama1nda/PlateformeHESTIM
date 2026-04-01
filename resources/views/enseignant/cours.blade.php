@extends('layouts.dashboard')

@section('title', 'Mes cours')
@section('page-title', 'Mes cours')

@section('content')
@php
    $enseignant = auth()->user();
@endphp

<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-6 text-white shadow">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm text-blue-100 uppercase tracking-wide">Espace enseignant</p>
                <h1 class="text-3xl font-bold mt-1">Mes cours</h1>
                <p class="text-blue-100 mt-2">Vue synthétique de vos cours, groupes et séances planifiées.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('enseignant.planning') }}" class="inline-flex items-center px-4 py-2 bg-white text-blue-700 rounded-lg font-semibold shadow hover:bg-blue-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Voir l'emploi du temps
                </a>
                <a href="{{ route('enseignant.reservations.index') }}" class="inline-flex items-center px-4 py-2 border border-white text-white rounded-lg font-semibold hover:bg-white hover:text-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Demander une salle
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow p-5 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Cours assignés</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['cours'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold">C</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Groupes couverts</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['groupes'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-semibold">G</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Séances planifiées</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['seances'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center font-semibold">S</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Liste des cours -->
        <div class="lg:col-span-2 space-y-4">
            @forelse($cours as $cour)
                @php
                    $prochaineSeance = $cour->emploisDuTemps->sortBy(function($emploi) use ($orderedDays) {
                        return ($orderedDays[$emploi->jour] ?? 99) . '_' . $emploi->heure_debut;
                    })->first();
                @endphp
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-semibold text-gray-800">{{ $cour->nom }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">{{ $cour->code }}</span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $cour->emploisDuTemps->count() ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $cour->emploisDuTemps->count() ? 'Programmé' : 'À planifier' }}
                                </span>
                            </div>
                            @if($cour->description)
                                <p class="text-sm text-gray-600 mb-3">{{ $cour->description }}</p>
                            @endif

                            <div class="mb-3">
                                <p class="text-xs uppercase text-gray-500 font-semibold mb-1">Groupes</p>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($cour->groupes as $groupe)
                                        <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs">{{ $groupe->nom }}</span>
                                    @empty
                                        <span class="text-sm text-gray-500">Aucun groupe affecté</span>
                                    @endforelse
                                </div>
                            </div>

                            @if($prochaineSeance)
                                <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 mb-3">
                                    <p class="text-xs uppercase text-gray-500 font-semibold">Prochaine séance</p>
                                    <div class="flex flex-wrap items-center gap-3 mt-1 text-sm text-gray-700">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ ucfirst($prochaineSeance->jour) }}
                                        </span>
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ substr($prochaineSeance->heure_debut, 0, 5) }} - {{ substr($prochaineSeance->heure_fin, 0, 5) }}
                                        </span>
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            {{ $prochaineSeance->salle->nom ?? 'Salle à définir' }}
                                        </span>
                                        <span class="px-2 py-1 rounded text-xs font-semibold
                                            @if($prochaineSeance->type_seance == 'cours') bg-blue-100 text-blue-800
                                            @elseif($prochaineSeance->type_seance == 'td') bg-green-100 text-green-800
                                            @else bg-purple-100 text-purple-800
                                            @endif">
                                            {{ strtoupper($prochaineSeance->type_seance) }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if($cour->emploisDuTemps->count())
                                <div class="space-y-2">
                                    <p class="text-xs uppercase text-gray-500 font-semibold">Toutes les séances</p>
                                    @foreach($cour->emploisDuTemps->groupBy('jour') as $jour => $emplois)
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <div class="flex items-center gap-2 text-sm font-semibold text-gray-800 mb-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ ucfirst($jour) }}
                                            </div>
                                            <div class="space-y-2">
                                                @foreach($emplois->sortBy('heure_debut') as $emploi)
                                                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-700">
                                                        <span class="inline-flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            {{ substr($emploi->heure_debut, 0, 5) }} - {{ substr($emploi->heure_fin, 0, 5) }}
                                                        </span>
                                                        <span class="inline-flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                            </svg>
                                                            {{ $emploi->salle->nom ?? 'Salle à définir' }}
                                                        </span>
                                                        @if($emploi->groupe)
                                                            <span class="text-xs text-gray-500">Groupe : {{ $emploi->groupe->nom }}</span>
                                                        @endif
                                                        <span class="px-2 py-1 rounded text-xs font-semibold
                                                            @if($emploi->type_seance == 'cours') bg-blue-100 text-blue-800
                                                            @elseif($emploi->type_seance == 'td') bg-green-100 text-green-800
                                                            @else bg-purple-100 text-purple-800
                                                            @endif">
                                                            {{ strtoupper($emploi->type_seance) }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic">Aucune séance planifiée pour l'instant.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow p-8 text-center">
                    <p class="text-gray-600">Aucun cours assigné pour le moment.</p>
                    <p class="text-sm text-gray-500 mt-2">Contactez l'administration si vous pensez qu'il s'agit d'une erreur.</p>
                </div>
            @endforelse
        </div>

        <!-- Colonne latérale -->
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow p-5">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-sm text-gray-500">Séances à venir</p>
                        <p class="text-lg font-semibold text-gray-800">Prochaines 6 séances</p>
                    </div>
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-semibold">Live</span>
                </div>
                <div class="space-y-3">
                    @forelse($seances->take(6) as $item)
                        <div class="border border-gray-100 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <h4 class="font-semibold text-gray-800 text-sm">{{ $item['cour']->nom }}</h4>
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    @if($item['emploi']->type_seance == 'cours') bg-blue-100 text-blue-800
                                    @elseif($item['emploi']->type_seance == 'td') bg-green-100 text-green-800
                                    @else bg-purple-100 text-purple-800
                                    @endif">
                                    {{ strtoupper($item['emploi']->type_seance) }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-3 text-xs text-gray-600 mt-2">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ ucfirst($item['emploi']->jour) }}
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ substr($item['emploi']->heure_debut, 0, 5) }} - {{ substr($item['emploi']->heure_fin, 0, 5) }}
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    {{ $item['emploi']->salle->nom ?? 'Salle à définir' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Aucune séance programmée.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-5 space-y-3">
                <h3 class="text-lg font-semibold text-gray-800">Actions rapides</h3>
                <div class="space-y-2">
                    <a href="{{ route('enseignant.reservations.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100">
                        <span>Demander une salle</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('notifications.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:border-blue-200 hover:text-blue-700">
                        <span>Voir les notifications</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

