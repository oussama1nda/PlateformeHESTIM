@extends('layouts.dashboard')

@section('title', 'Gestion des Cours')
@section('page-title', 'Tableau De bord Admin')

@section('content')
<div class="space-y-6">
    <!-- Section Gestion des Cours -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Gestion des Cours</h2>
                    <p class="text-sm text-gray-600 mt-1">Créez et gérez les cours</p>
                </div>
                <button onclick="openCoursModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>+ Nouveau Cours</span>
                </button>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enseignant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Groupe(s)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Planning</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Volume horaire</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($cours as $cour)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $cour->nom }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cour->code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cour->enseignant->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @foreach($cour->groupes as $groupe)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs mr-1">{{ $groupe->nom }}</span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    @if($cour->emploisDuTemps->count() > 0)
                                        @foreach($cour->emploisDuTemps->take(2) as $emploi)
                                            <div class="mb-1">
                                                <span class="font-semibold">{{ ucfirst($emploi->jour) }}</span>
                                                <span class="text-gray-600">{{ substr($emploi->heure_debut, 0, 5) }} - {{ substr($emploi->heure_fin, 0, 5) }}</span>
                                                <span class="text-gray-500">({{ $emploi->salle->nom ?? 'N/A' }})</span>
                                            </div>
                                        @endforeach
                                        @if($cour->emploisDuTemps->count() > 2)
                                            <span class="text-xs text-gray-400">+{{ $cour->emploisDuTemps->count() - 2 }} autres</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Non programmé</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cour->volume_horaire }}h</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.cours.edit', $cour) }}" class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.cours.destroy', $cour) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Aucun cours</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Création Cours -->
<div id="coursModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Créer un nouveau cours</h3>
            <button onclick="closeCoursModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <p class="text-sm text-gray-600 mb-6">Créez un nouveau cours et assignez-le à un enseignant, une salle et un groupe</p>
        
        <form action="{{ route('admin.cours.store') }}" method="POST" class="space-y-4" id="coursForm">
            @csrf
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
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom du cours*</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('nom') ? 'border-red-500' : '' }}" placeholder="Ex: Mathématiques Avancées">
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Code du cours*</label>
                    <input type="text" name="code" value="{{ old('code') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('code') ? 'border-red-500' : '' }}" placeholder="Ex: MATH301">
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Description du cours">{{ old('description') }}</textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Enseignant*</label>
                    <select name="enseignant_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white {{ $errors->has('enseignant_id') ? 'border-red-500' : '' }}">
                        <option value="">Sélectionner un enseignant</option>
                        @foreach(\App\Models\User::where('role', 'enseignant')->get() as $enseignant)
                            <option value="{{ $enseignant->id }}" {{ old('enseignant_id') == $enseignant->id ? 'selected' : '' }}>{{ $enseignant->name }}</option>
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
                            <option value="{{ $departement->id }}" {{ old('departement_id') == $departement->id ? 'selected' : '' }}>{{ $departement->nom }}</option>
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
                        <option value="{{ $groupe->id }}" {{ in_array($groupe->id, old('groupes', [])) ? 'selected' : '' }}>{{ $groupe->nom }}</option>
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
                            <option value="{{ $salle->id }}" {{ old('salle_id') == $salle->id ? 'selected' : '' }}>{{ $salle->nom }} (Capacité: {{ $salle->capacite }})</option>
                        @endforeach
                    </select>
                    @error('salle_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Volume horaire*</label>
                    <input type="number" name="volume_horaire" value="{{ old('volume_horaire') }}" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('volume_horaire') ? 'border-red-500' : '' }}" placeholder="Ex: 30">
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
                            <option value="lundi" {{ old('jour') == 'lundi' ? 'selected' : '' }}>Lundi</option>
                            <option value="mardi" {{ old('jour') == 'mardi' ? 'selected' : '' }}>Mardi</option>
                            <option value="mercredi" {{ old('jour') == 'mercredi' ? 'selected' : '' }}>Mercredi</option>
                            <option value="jeudi" {{ old('jour') == 'jeudi' ? 'selected' : '' }}>Jeudi</option>
                            <option value="vendredi" {{ old('jour') == 'vendredi' ? 'selected' : '' }}>Vendredi</option>
                            <option value="samedi" {{ old('jour') == 'samedi' ? 'selected' : '' }}>Samedi</option>
                        </select>
                        @error('jour')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type de séance*</label>
                        <select name="type_seance" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                            <option value="cours" {{ old('type_seance') == 'cours' ? 'selected' : '' }}>Cours</option>
                            <option value="td" {{ old('type_seance') == 'td' ? 'selected' : '' }}>TD</option>
                            <option value="tp" {{ old('type_seance') == 'tp' ? 'selected' : '' }}>TP</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-4 mt-4">
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
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                        <input type="date" name="date_debut" value="{{ old('date_debut') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                    <input type="date" name="date_fin" value="{{ old('date_fin') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <!-- Message de conflit -->
                <div id="conflictMessage" class="hidden mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                    <p class="font-semibold">⚠ Conflit détecté !</p>
                    <p class="text-sm">Cette salle est déjà réservée à cette date et heure. Veuillez choisir une autre salle ou un autre créneau.</p>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeCoursModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Créer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCoursModal() {
        document.getElementById('coursModal').classList.remove('hidden');
        document.getElementById('coursForm').reset();
        document.getElementById('conflictMessage').classList.add('hidden');
        const errorDiv = document.querySelector('#coursForm .bg-red-100');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    function closeCoursModal() {
        document.getElementById('coursModal').classList.add('hidden');
        document.getElementById('coursForm').reset();
        document.getElementById('conflictMessage').classList.add('hidden');
    }
    
    window.onclick = function(event) {
        const modal = document.getElementById('coursModal');
        if (event.target == modal) {
            closeCoursModal();
        }
    }
    
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
    
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            openCoursModal();
        });
    @endif
</script>
@endsection

