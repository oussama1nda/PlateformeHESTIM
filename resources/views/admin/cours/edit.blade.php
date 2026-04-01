@extends('layouts.dashboard')

@section('title', 'Modifier le Cours')
@section('page-title', 'Tableau De bord Admin')

@section('content')
<div class="space-y-6">
    <!-- Section Modification du Cours -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Modifier le Cours</h2>
                    <p class="text-sm text-gray-600 mt-1">Modifiez les informations du cours</p>
                </div>
                <a href="{{ route('admin.cours.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    Retour
                </a>
            </div>
        </div>
        
        <div class="p-6">
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if($errors->has('conflict'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <p class="font-semibold">⚠ Conflit détecté !</p>
                    <p>{{ $errors->first('conflict') }}</p>
                </div>
            @endif
            
            <form action="{{ route('admin.cours.update', $cour) }}" method="POST" class="space-y-4" id="coursForm">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom du cours*</label>
                        <input type="text" name="nom" value="{{ old('nom', $cour->nom) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('nom') ? 'border-red-500' : '' }}" placeholder="Ex: Mathématiques Avancées">
                        @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Code du cours*</label>
                        <input type="text" name="code" value="{{ old('code', $cour->code) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('code') ? 'border-red-500' : '' }}" placeholder="Ex: MATH301">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Description du cours">{{ old('description', $cour->description) }}</textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Enseignant*</label>
                        <select name="enseignant_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white {{ $errors->has('enseignant_id') ? 'border-red-500' : '' }}">
                            <option value="">Sélectionner un enseignant</option>
                            @foreach(\App\Models\User::where('role', 'enseignant')->get() as $enseignant)
                                <option value="{{ $enseignant->id }}" {{ old('enseignant_id', $cour->enseignant_id) == $enseignant->id ? 'selected' : '' }}>{{ $enseignant->name }}</option>
                            @endforeach
                        </select>
                        @error('enseignant_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Département*</label>
                        <select name="departement_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white {{ $errors->has('departement_id') ? 'border-red-500' : '' }}">
                            <option value="">Sélectionner un département</option>
                            @foreach(\App\Models\Departement::all() as $departement)
                                <option value="{{ $departement->id }}" {{ old('departement_id', $cour->departement_id) == $departement->id ? 'selected' : '' }}>{{ $departement->nom }}</option>
                            @endforeach
                        </select>
                        @error('departement_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Groupe(s)*</label>
                    <select name="groupes[]" multiple required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white {{ $errors->has('groupes') ? 'border-red-500' : '' }}" size="5">
                        @foreach(\App\Models\Groupe::all() as $groupe)
                            <option value="{{ $groupe->id }}" {{ in_array($groupe->id, old('groupes', $cour->groupes->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $groupe->nom }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs groupes</p>
                    @error('groupes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Salle*</label>
                        <select name="salle_id" id="salleSelect" required onchange="checkConflict()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white {{ $errors->has('salle_id') ? 'border-red-500' : '' }}">
                            <option value="">Sélectionner une salle</option>
                            @foreach(\App\Models\Salle::where('disponible', true)->get() as $salle)
                                <option value="{{ $salle->id }}" {{ old('salle_id', $emploi->salle_id ?? '') == $salle->id ? 'selected' : '' }}>{{ $salle->nom }} (Capacité: {{ $salle->capacite }})</option>
                            @endforeach
                        </select>
                        @error('salle_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Volume horaire*</label>
                        <input type="number" name="volume_horaire" value="{{ old('volume_horaire', $cour->volume_horaire) }}" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('volume_horaire') ? 'border-red-500' : '' }}" placeholder="Ex: 30">
                        @error('volume_horaire')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Section Planning -->
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Planning du cours</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jour de la semaine*</label>
                            <select name="jour" id="jourSelect" required onchange="checkConflict()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white {{ $errors->has('jour') ? 'border-red-500' : '' }}">
                                <option value="">Sélectionner un jour</option>
                                <option value="lundi" {{ old('jour', $emploi->jour ?? '') == 'lundi' ? 'selected' : '' }}>Lundi</option>
                                <option value="mardi" {{ old('jour', $emploi->jour ?? '') == 'mardi' ? 'selected' : '' }}>Mardi</option>
                                <option value="mercredi" {{ old('jour', $emploi->jour ?? '') == 'mercredi' ? 'selected' : '' }}>Mercredi</option>
                                <option value="jeudi" {{ old('jour', $emploi->jour ?? '') == 'jeudi' ? 'selected' : '' }}>Jeudi</option>
                                <option value="vendredi" {{ old('jour', $emploi->jour ?? '') == 'vendredi' ? 'selected' : '' }}>Vendredi</option>
                                <option value="samedi" {{ old('jour', $emploi->jour ?? '') == 'samedi' ? 'selected' : '' }}>Samedi</option>
                            </select>
                            @error('jour')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type de séance*</label>
                            <select name="type_seance" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                                <option value="cours" {{ old('type_seance', $emploi->type_seance ?? 'cours') == 'cours' ? 'selected' : '' }}>Cours</option>
                                <option value="td" {{ old('type_seance', $emploi->type_seance ?? '') == 'td' ? 'selected' : '' }}>TD</option>
                                <option value="tp" {{ old('type_seance', $emploi->type_seance ?? '') == 'tp' ? 'selected' : '' }}>TP</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Heure de début*</label>
                            <input type="time" name="heure_debut" id="heureDebut" value="{{ old('heure_debut', $emploi ? substr($emploi->heure_debut, 0, 5) : '') }}" required onchange="checkConflict()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('heure_debut') ? 'border-red-500' : '' }}">
                            @error('heure_debut')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Heure de fin*</label>
                            <input type="time" name="heure_fin" id="heureFin" value="{{ old('heure_fin', $emploi ? substr($emploi->heure_fin, 0, 5) : '') }}" required onchange="checkConflict()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('heure_fin') ? 'border-red-500' : '' }}">
                            @error('heure_fin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                            <input type="date" name="date_debut" value="{{ old('date_debut', $emploi ? $emploi->date_debut?->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                        <input type="date" name="date_fin" value="{{ old('date_fin', $emploi ? $emploi->date_fin?->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Message de conflit -->
                    <div id="conflictMessage" class="hidden mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                        <p class="font-semibold">⚠ Conflit détecté !</p>
                        <p class="text-sm">Cette salle est déjà réservée à cette date et heure. Veuillez choisir une autre salle ou un autre créneau.</p>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('admin.cours.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Annuler
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Modifier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let hasConflictDetected = false;
    
    // Vérifier les conflits en temps réel
    function checkConflict() {
        const salleId = document.getElementById('salleSelect').value;
        const jour = document.getElementById('jourSelect').value;
        const heureDebut = document.getElementById('heureDebut').value;
        const heureFin = document.getElementById('heureFin').value;
        const conflictMessage = document.getElementById('conflictMessage');
        const submitButton = document.querySelector('#coursForm button[type="submit"]');
        
        if (salleId && jour && heureDebut && heureFin) {
            // Vérifier que l'heure de fin est après l'heure de début
            if (heureFin <= heureDebut) {
                conflictMessage.classList.remove('hidden');
                conflictMessage.innerHTML = '<p class="font-semibold">⚠ Erreur !</p><p class="text-sm">L\'heure de fin doit être après l\'heure de début.</p>';
                hasConflictDetected = true;
                if (submitButton) submitButton.disabled = true;
                return;
            }
            
            // Vérifier le conflit via AJAX
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
                    conflictMessage.classList.remove('hidden');
                    const jourNames = {
                        'lundi': 'Lundi',
                        'mardi': 'Mardi',
                        'mercredi': 'Mercredi',
                        'jeudi': 'Jeudi',
                        'vendredi': 'Vendredi',
                        'samedi': 'Samedi'
                    };
                    conflictMessage.innerHTML = '<p class="font-semibold">⚠ Conflit détecté !</p><p class="text-sm">Cette salle est déjà réservée le ' + (jourNames[jour] || jour) + ' de ' + heureDebut + ' à ' + heureFin + '. Veuillez choisir une autre salle ou un autre créneau.</p>';
                    hasConflictDetected = true;
                    if (submitButton) submitButton.disabled = true;
                } else {
                    conflictMessage.classList.add('hidden');
                    hasConflictDetected = false;
                    if (submitButton) submitButton.disabled = false;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        } else {
            conflictMessage.classList.add('hidden');
            hasConflictDetected = false;
            if (submitButton) submitButton.disabled = false;
        }
    }
    
    // Empêcher la soumission si conflit détecté
    document.getElementById('coursForm').addEventListener('submit', function(e) {
        if (hasConflictDetected) {
            e.preventDefault();
            alert('Veuillez résoudre le conflit avant de soumettre le formulaire.');
            return false;
        }
    });
</script>
@endsection

