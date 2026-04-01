

<?php $__env->startSection('title', 'Demandes de Réservation'); ?>
<?php $__env->startSection('page-title', 'Tableau De bord Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center space-x-4">
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold">Demandes de Réservation</h2>
                <p class="text-blue-100 mt-1">Gérez les demandes de réservation des enseignants</p>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    <!-- Statistiques Réservations -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">En attente</p>
                    <p class="text-3xl font-bold text-yellow-600"><?php echo e(\App\Models\Reservation::where('statut', 'en_attente')->count()); ?></p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Approuvées</p>
                    <p class="text-3xl font-bold text-green-600"><?php echo e(\App\Models\Reservation::where('statut', 'approuvee')->count()); ?></p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Refusées</p>
                    <p class="text-3xl font-bold text-red-600"><?php echo e(\App\Models\Reservation::where('statut', 'refusee')->count()); ?></p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des Réservations -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Liste des Demandes</h2>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enseignant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motif</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Salle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date et Heure</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-blue-600 font-semibold"><?php echo e(substr($reservation->user->name, 0, 1)); ?></span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($reservation->user->name); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo e($reservation->user->email); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 font-semibold"><?php echo e($reservation->motif); ?></div>
                                    <?php if($reservation->commentaire): ?>
                                        <div class="text-sm text-gray-500 mt-1"><?php echo e($reservation->commentaire); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo e($reservation->salle->nom); ?></div>
                                    <div class="text-sm text-gray-500">Capacité: <?php echo e($reservation->salle->capacite); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-semibold"><?php echo e($reservation->date->format('d/m/Y')); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e(substr($reservation->heure_debut, 0, 5)); ?> - <?php echo e(substr($reservation->heure_fin, 0, 5)); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php if($reservation->statut == 'en_attente'): ?> bg-yellow-100 text-yellow-800
                                        <?php elseif($reservation->statut == 'approuvee'): ?> bg-green-100 text-green-800
                                        <?php elseif($reservation->statut == 'annulee'): ?> bg-gray-100 text-gray-800
                                        <?php else: ?> bg-red-100 text-red-800
                                        <?php endif; ?>">
                                        <?php if($reservation->statut == 'en_attente'): ?> ⏳ En attente
                                        <?php elseif($reservation->statut == 'approuvee'): ?> ✓ Approuvée
                                        <?php elseif($reservation->statut == 'annulee'): ?> Annulée
                                        <?php else: ?> ✗ Refusée
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <?php if($reservation->statut == 'en_attente'): ?>
                                        <div class="flex space-x-2">
                                            <button onclick="openApproveModal(<?php echo e($reservation->id); ?>, '<?php echo e($reservation->motif); ?>', <?php echo e($reservation->user_id); ?>)" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-xs font-semibold">
                                                Approuver
                                            </button>
                                            <form action="<?php echo e(route('admin.reservations.reject', $reservation)); ?>" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir refuser cette réservation ?')">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-xs font-semibold">
                                                    Refuser
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Aucune réservation</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Approuver -->
<div id="approveModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Approuver la Réservation</h3>
            <button onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form action="" method="POST" id="approveForm" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Département*</label>
                <select name="departement_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                    <option value="">Sélectionner un département</option>
                    <?php $__currentLoopData = \App\Models\Departement::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $departement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($departement->id); ?>"><?php echo e($departement->nom); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Groupe(s)*</label>
                <select name="groupes[]" multiple required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" size="5">
                    <?php $__currentLoopData = \App\Models\Groupe::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($groupe->id); ?>"><?php echo e($groupe->nom); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <p class="mt-1 text-xs text-gray-500">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs groupes</p>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeApproveModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                    Approuver et Créer le Cours
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openApproveModal(reservationId, motif, userId) {
        document.getElementById('approveModal').classList.remove('hidden');
        document.getElementById('approveForm').action = '<?php echo e(route("admin.reservations.approve", ":id")); ?>'.replace(':id', reservationId);
    }
    
    function closeApproveModal() {
        document.getElementById('approveModal').classList.add('hidden');
        document.getElementById('approveForm').reset();
    }
    
    window.onclick = function(event) {
        const modal = document.getElementById('approveModal');
        if (event.target == modal) {
            closeApproveModal();
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\dell\Desktop\plateformehestim\resources\views/admin/reservations/index.blade.php ENDPATH**/ ?>