@extends('layouts.dashboard')

@section('title', 'Demander une Nouvelle Salle')
@section('page-title', 'Dashboard Enseignant')

@section('content')
<div class="space-y-6">
    <!-- Card 1: Demander une Nouvelle Salle -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-2">Demander une Nouvelle Salle</h3>
        <p class="text-gray-600 mb-6">Proposez une salle alternative si la vôtre n'est pas disponible</p>
        <button onclick="openRequestModal()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
            Faire une Demande
        </button>
    </div>

    <!-- Card 2: Demande Annuler un Cours -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-2">Demande Annuler un Cours</h3>
        <p class="text-gray-600 mb-6">Annulez un cours avec notification automatique aux étudiants</p>
        <button onclick="openCancelModal()" class="bg-white border-2 border-blue-600 text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50 transition font-semibold">
            Annuler Un Cours
        </button>
    </div>
</div>

<!-- Modal Demande Salle -->
<div id="requestModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Demander une Nouvelle Salle</h3>
            <button onclick="closeRequestModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('enseignant.reservations.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Salle souhaitée</label>
                <select name="salle_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @foreach(\App\Models\Salle::where('disponible', true)->get() as $salle)
                        <option value="{{ $salle->id }}">{{ $salle->nom }} (Capacité: {{ $salle->capacite }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" name="date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Heure début</label>
                    <input type="time" name="heure_debut" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Heure fin</label>
                    <input type="time" name="heure_fin" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Motif</label>
                <textarea name="motif" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Raison de la demande"></textarea>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeRequestModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Envoyer la demande
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRequestModal() {
        document.getElementById('requestModal').classList.remove('hidden');
    }
    function closeRequestModal() {
        document.getElementById('requestModal').classList.add('hidden');
    }
    function openCancelModal() {
        alert('Fonctionnalité à venir');
    }
</script>
@endsection

