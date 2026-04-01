@extends('layouts.dashboard')

@section('title', 'Modifier un Emploi du Temps')
@section('page-title', 'Tableau De bord Admin')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Modifier le Créneau</h2>
            <p class="text-sm text-gray-600 mt-1">Modifiez les informations du créneau d'emploi du temps</p>
        </div>

        <form action="{{ route('admin.emploi-du-temps.update', $emploiDuTemps) }}" method="POST" id="emploiForm" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cours -->
                <div>
                    <label for="cours_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Cours <span class="text-red-500">*</span>
                    </label>
                    <select name="cours_id" id="cours_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner un cours</option>
                        @foreach($cours as $cour)
                            <option value="{{ $cour->id }}" {{ old('cours_id', $emploiDuTemps->cours_id) == $cour->id ? 'selected' : '' }}>
                                {{ $cour->nom }} ({{ $cour->code }}) - {{ $cour->enseignant->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('cours_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Salle -->
                <div>
                    <label for="salle_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Salle <span class="text-red-500">*</span>
                    </label>
                    <select name="salle_id" id="salle_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner une salle</option>
                        @foreach($salles as $salle)
                            <option value="{{ $salle->id }}" {{ old('salle_id', $emploiDuTemps->salle_id) == $salle->id ? 'selected' : '' }}>
                                {{ $salle->nom }} ({{ $salle->numero }}) - Capacité: {{ $salle->capacite }}
                            </option>
                        @endforeach
                    </select>
                    @error('salle_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Groupe -->
                <div>
                    <label for="groupe_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Groupe <span class="text-red-500">*</span>
                    </label>
                    <select name="groupe_id" id="groupe_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner un groupe</option>
                        @foreach($groupes as $groupe)
                            <option value="{{ $groupe->id }}" {{ old('groupe_id', $emploiDuTemps->groupe_id) == $groupe->id ? 'selected' : '' }}>
                                {{ $groupe->nom }} - {{ $groupe->departement->nom ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('groupe_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jour -->
                <div>
                    <label for="jour" class="block text-sm font-medium text-gray-700 mb-2">
                        Jour de la semaine <span class="text-red-500">*</span>
                    </label>
                    <select name="jour" id="jour" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner un jour</option>
                        <option value="lundi" {{ old('jour', $emploiDuTemps->jour) == 'lundi' ? 'selected' : '' }}>Lundi</option>
                        <option value="mardi" {{ old('jour', $emploiDuTemps->jour) == 'mardi' ? 'selected' : '' }}>Mardi</option>
                        <option value="mercredi" {{ old('jour', $emploiDuTemps->jour) == 'mercredi' ? 'selected' : '' }}>Mercredi</option>
                        <option value="jeudi" {{ old('jour', $emploiDuTemps->jour) == 'jeudi' ? 'selected' : '' }}>Jeudi</option>
                        <option value="vendredi" {{ old('jour', $emploiDuTemps->jour) == 'vendredi' ? 'selected' : '' }}>Vendredi</option>
                        <option value="samedi" {{ old('jour', $emploiDuTemps->jour) == 'samedi' ? 'selected' : '' }}>Samedi</option>
                        <option value="dimanche" {{ old('jour', $emploiDuTemps->jour) == 'dimanche' ? 'selected' : '' }}>Dimanche</option>
                    </select>
                    @error('jour')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Heure de début -->
                <div>
                    <label for="heure_debut" class="block text-sm font-medium text-gray-700 mb-2">
                        Heure de début <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="heure_debut" id="heure_debut" required
                           value="{{ old('heure_debut', substr($emploiDuTemps->heure_debut, 0, 5)) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('heure_debut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Heure de fin -->
                <div>
                    <label for="heure_fin" class="block text-sm font-medium text-gray-700 mb-2">
                        Heure de fin <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="heure_fin" id="heure_fin" required
                           value="{{ old('heure_fin', substr($emploiDuTemps->heure_fin, 0, 5)) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('heure_fin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de début -->
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de début <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date_debut" id="date_debut" required
                           value="{{ old('date_debut', $emploiDuTemps->date_debut ? $emploiDuTemps->date_debut->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('date_debut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de fin -->
                <div>
                    <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de fin <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date_fin" id="date_fin" required
                           value="{{ old('date_fin', $emploiDuTemps->date_fin ? $emploiDuTemps->date_fin->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('date_fin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type de séance -->
                <div>
                    <label for="type_seance" class="block text-sm font-medium text-gray-700 mb-2">
                        Type de séance <span class="text-red-500">*</span>
                    </label>
                    <select name="type_seance" id="type_seance" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner un type</option>
                        <option value="cours" {{ old('type_seance', $emploiDuTemps->type_seance) == 'cours' ? 'selected' : '' }}>Cours</option>
                        <option value="td" {{ old('type_seance', $emploiDuTemps->type_seance) == 'td' ? 'selected' : '' }}>TD (Travaux Dirigés)</option>
                        <option value="tp" {{ old('type_seance', $emploiDuTemps->type_seance) == 'tp' ? 'selected' : '' }}>TP (Travaux Pratiques)</option>
                    </select>
                    @error('type_seance')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Message d'alerte pour conflit -->
            <div id="conflictAlert" class="hidden bg-red-100 border-l-4 border-red-500 p-4 rounded">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-red-800">Conflit détecté !</p>
                        <p class="text-sm text-red-700 mt-1">La salle est déjà réservée à cette date et heure pour un autre cours.</p>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.emploi-du-temps.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Annuler
                </a>
                <button type="button" id="checkConflictBtn" 
                        class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                    Vérifier les Conflits
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkConflictBtn = document.getElementById('checkConflictBtn');
    const conflictAlert = document.getElementById('conflictAlert');
    
    checkConflictBtn.addEventListener('click', function() {
        const salleId = document.getElementById('salle_id').value;
        const jour = document.getElementById('jour').value;
        const heureDebut = document.getElementById('heure_debut').value;
        const heureFin = document.getElementById('heure_fin').value;
        
        if (!salleId || !jour || !heureDebut || !heureFin) {
            alert('Veuillez remplir tous les champs nécessaires (Salle, Jour, Heures)');
            return;
        }
        
        fetch('{{ route("admin.emploi-du-temps.check-conflict") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                salle_id: salleId,
                jour: jour,
                heure_debut: heureDebut,
                heure_fin: heureFin
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.has_conflict) {
                conflictAlert.classList.remove('hidden');
            } else {
                conflictAlert.classList.add('hidden');
                alert('Aucun conflit détecté. Vous pouvez mettre à jour ce créneau.');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la vérification des conflits');
        });
    });
    
    // Vérification automatique lors du changement des champs
    ['salle_id', 'jour', 'heure_debut', 'heure_fin'].forEach(fieldId => {
        document.getElementById(fieldId).addEventListener('change', function() {
            conflictAlert.classList.add('hidden');
        });
    });
});
</script>
@endsection



