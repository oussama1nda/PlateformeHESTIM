@extends('layouts.dashboard')

@section('title', 'Demander une Réservation')
@section('page-title', 'Dashboard Enseignant')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Demander une Réservation</h2>
                    <p class="text-blue-100 mt-1">Réservez une salle pour vos besoins spécifiques</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulaire de réservation -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nouvelle Demande de Réservation
                </h3>

                @if($errors->has('conflict'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <p class="font-semibold">⚠ Conflit détecté !</p>
                        <p>{{ $errors->first('conflict') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('enseignant.reservations.store') }}" method="POST" class="space-y-4" id="reservationForm">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Salle*</label>
                            <select name="salle_id" id="salleSelect" required onchange="checkConflict()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white {{ $errors->has('salle_id') ? 'border-red-500' : '' }}">
                                <option value="">Sélectionner une salle</option>
                                @foreach($salles as $salle)
                                    <option value="{{ $salle->id }}" {{ old('salle_id') == $salle->id ? 'selected' : '' }}>
                                        {{ $salle->nom }} - Capacité: {{ $salle->capacite }} 
                                        @if($salle->type)
                                            ({{ $salle->type }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('salle_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date*</label>
                            <input type="date" name="date" id="dateSelect" value="{{ old('date') }}" required min="{{ date('Y-m-d') }}" onchange="checkConflict()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('date') ? 'border-red-500' : '' }}">
                            @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Heure de début*</label>
                            <input type="time" name="heure_debut" id="heureDebut" value="{{ old('heure_debut') }}" required onchange="checkConflict()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('heure_debut') ? 'border-red-500' : '' }}">
                            @error('heure_debut')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Heure de fin*</label>
                            <input type="time" name="heure_fin" id="heureFin" value="{{ old('heure_fin') }}" required onchange="checkConflict()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('heure_fin') ? 'border-red-500' : '' }}">
                            @error('heure_fin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Motif*</label>
                        <input type="text" name="motif" value="{{ old('motif') }}" required placeholder="Ex: Réunion, Examen, Projet..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('motif') ? 'border-red-500' : '' }}">
                        @error('motif')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire (optionnel)</label>
                        <textarea name="commentaire" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Détails supplémentaires...">{{ old('commentaire') }}</textarea>
                    </div>

                    <!-- Message de conflit -->
                    <div id="conflictMessage" class="hidden p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                        <p class="font-semibold">⚠ Conflit détecté !</p>
                        <p class="text-sm">Cette salle est déjà réservée à cette date et heure. Veuillez choisir une autre salle ou un autre créneau.</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                            Envoyer la Demande
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des réservations -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Mes Réservations</h3>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($reservations as $reservation)
                        <div class="border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-sm text-gray-800">{{ $reservation->salle->nom }}</h4>
                                    <p class="text-xs text-gray-600">{{ $reservation->date->format('d/m/Y') }}</p>
                                    <p class="text-xs text-gray-600">{{ substr($reservation->heure_debut, 0, 5) }} - {{ substr($reservation->heure_fin, 0, 5) }}</p>
                                </div>
                                <span class="px-2 py-1 rounded text-xs font-semibold 
                                    @if($reservation->statut == 'approuvee') bg-green-100 text-green-800
                                    @elseif($reservation->statut == 'refusee') bg-red-100 text-red-800
                                    @elseif($reservation->statut == 'annulee') bg-gray-100 text-gray-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    @if($reservation->statut == 'approuvee') ✓ Approuvée
                                    @elseif($reservation->statut == 'refusee') ✗ Refusée
                                    @elseif($reservation->statut == 'annulee') Annulée
                                    @else ⏳ En attente
                                    @endif
                                </span>
                            </div>
                            <p class="text-xs text-gray-700 mt-2">{{ $reservation->motif }}</p>
                            @if($reservation->statut != 'approuvee' && $reservation->statut != 'annulee')
                                <form action="{{ route('enseignant.reservations.destroy', $reservation) }}" method="POST" class="mt-2" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800">Annuler</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Aucune réservation</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Liste complète des réservations -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Historique des Réservations</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Salle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Heure</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motif</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reservations as $reservation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $reservation->salle->nom }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reservation->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ substr($reservation->heure_debut, 0, 5) }} - {{ substr($reservation->heure_fin, 0, 5) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $reservation->motif }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    @if($reservation->statut == 'approuvee') bg-green-100 text-green-800
                                    @elseif($reservation->statut == 'refusee') bg-red-100 text-red-800
                                    @elseif($reservation->statut == 'annulee') bg-gray-100 text-gray-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    @if($reservation->statut == 'approuvee') Approuvée
                                    @elseif($reservation->statut == 'refusee') Refusée
                                    @elseif($reservation->statut == 'annulee') Annulée
                                    @else En attente
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($reservation->statut != 'approuvee' && $reservation->statut != 'annulee')
                                    <form action="{{ route('enseignant.reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Annuler</button>
                                    </form>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Aucune réservation</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let hasConflictDetected = false;
    
    function checkConflict() {
        const salleId = document.getElementById('salleSelect').value;
        const date = document.getElementById('dateSelect').value;
        const heureDebut = document.getElementById('heureDebut').value;
        const heureFin = document.getElementById('heureFin').value;
        const conflictMessage = document.getElementById('conflictMessage');
        const submitButton = document.querySelector('#reservationForm button[type="submit"]');
        
        if (salleId && date && heureDebut && heureFin) {
            if (heureFin <= heureDebut) {
                conflictMessage.classList.remove('hidden');
                conflictMessage.innerHTML = '<p class="font-semibold">⚠ Erreur !</p><p class="text-sm">L\'heure de fin doit être après l\'heure de début.</p>';
                hasConflictDetected = true;
                if (submitButton) submitButton.disabled = true;
                return;
            }
            
            conflictMessage.classList.add('hidden');
            hasConflictDetected = false;
            if (submitButton) submitButton.disabled = false;
        } else {
            conflictMessage.classList.add('hidden');
            hasConflictDetected = false;
            if (submitButton) submitButton.disabled = false;
        }
    }
    
    document.getElementById('reservationForm').addEventListener('submit', function(e) {
        if (hasConflictDetected) {
            e.preventDefault();
            alert('Veuillez résoudre le conflit avant de soumettre le formulaire.');
            return false;
        }
    });
</script>
@endsection

