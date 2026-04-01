@extends('layouts.dashboard')

@section('title', 'Mon Emploi du Temps')
@section('page-title', 'Dashboard Enseignant')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-blue-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <div>
                    <h2 class="text-2xl font-bold">Mon Emploi Du Temps</h2>
                    <p class="text-blue-100 mt-1">Semaine du 3 au 7 novembre 2025</p>
                </div>
            </div>
            <button class="bg-white text-blue-600 px-6 py-2 rounded-lg hover:bg-blue-50 transition font-semibold">
                Consulter
            </button>
        </div>
    </div>

    <!-- Weekly Schedule -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        @php
            $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
            $cours = auth()->user()->coursEnseignes()->with(['emploisDuTemps.salle', 'emploisDuTemps.groupe'])->get();
        @endphp

        @foreach($jours as $index => $jour)
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-semibold text-gray-800 mb-3">{{ $jour }}</h3>
                <div class="space-y-3">
                    @php
                        $emploisJour = collect();
                        foreach($cours as $cour) {
                            $emplois = $cour->emploisDuTemps->where('jour', strtolower($jour));
                            foreach($emplois as $emploi) {
                                $emploisJour->push([
                                    'cour' => $cour,
                                    'emploi' => $emploi
                                ]);
                            }
                        }
                        $emploisJour = $emploisJour->sortBy(function($item) {
                            return $item['emploi']->heure_debut;
                        });
                    @endphp

                    @forelse($emploisJour as $item)
                        @php
                            $cour = $item['cour'];
                            $emploi = $item['emploi'];
                        @endphp
                        <div class="bg-gray-50 rounded-lg p-3 border-l-4 
                            @if($emploi->type_seance == 'cours') border-blue-500
                            @elseif($emploi->type_seance == 'td') border-green-500
                            @else border-purple-500
                            @endif">
                            <h4 class="font-semibold text-sm text-gray-800 mb-2">{{ $cour->nom }}</h4>
                            <div class="space-y-1 text-xs text-gray-600">
                                <div class="flex items-center space-x-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ substr($emploi->heure_debut, 0, 5) }}-{{ substr($emploi->heure_fin, 0, 5) }}</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span>{{ $emploi->salle->nom ?? 'N/A' }}</span>
                                </div>
                                @if($emploi->groupe)
                                    <div class="flex items-center space-x-1">
                                        <span class="text-xs text-gray-500">Groupe: {{ $emploi->groupe->nom }}</span>
                                    </div>
                                @endif
                            </div>
                            <span class="inline-block mt-2 px-2 py-1 rounded-full text-xs font-semibold 
                                @if($emploi->type_seance == 'cours') bg-blue-100 text-blue-800
                                @elseif($emploi->type_seance == 'td') bg-green-100 text-green-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ strtoupper($emploi->type_seance) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Aucun cours</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

