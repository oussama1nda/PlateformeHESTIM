

<?php $__env->startSection('title', 'Demander une Réservation'); ?>
<?php $__env->startSection('page-title', 'Dashboard Enseignant'); ?>

<?php $__env->startSection('content'); ?>
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

                <?php if($errors->has('conflict')): ?>
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <p class="font-semibold">⚠ Conflit détecté !</p>
                        <p><?php echo e($errors->first('conflict')); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('enseignant.reservations.store')); ?>" method="POST" class="space-y-4" id="reservationForm">
                    <?php echo csrf_field(); ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Salle*</label>
                            <select name="salle_id" id="salleSelect" required onchange="checkConflict()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white <?php echo e($errors->has('salle_id') ? 'border-red-500' : ''); ?>">
                                <option value="">Sélectionner une salle</option>
                                <?php $__currentLoopData = $salles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $salle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($salle->id); ?>" <?php echo e(old('salle_id') == $salle->id ? 'selected' : ''); ?>>
                                        <?php echo e($salle->nom); ?> - Capacité: <?php echo e($salle->capacite); ?> 
                                        <?php if($salle->type): ?>
                                            (<?php echo e($salle->type); ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['salle_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date*</label>
                            <input type="date" name="date" id="dateSelect" value="<?php echo e(old('date')); ?>" required min="<?php echo e(date('Y-m-d')); ?>" onchange="checkConflict()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php echo e($errors->has('date') ? 'border-red-500' : ''); ?>">
                            <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Heure de début*</label>
                            <input type="time" name="heure_debut" id="heureDebut" value="<?php echo e(old('heure_debut')); ?>" required onchange="checkConflict()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php echo e($errors->has('heure_debut') ? 'border-red-500' : ''); ?>">
                            <?php $__errorArgs = ['heure_debut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Heure de fin*</label>
                            <input type="time" name="heure_fin" id="heureFin" value="<?php echo e(old('heure_fin')); ?>" required onchange="checkConflict()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php echo e($errors->has('heure_fin') ? 'border-red-500' : ''); ?>">
                            <?php $__errorArgs = ['heure_fin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Motif*</label>
                        <input type="text" name="motif" value="<?php echo e(old('motif')); ?>" required placeholder="Ex: Réunion, Examen, Projet..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php echo e($errors->has('motif') ? 'border-red-500' : ''); ?>">
                        <?php $__errorArgs = ['motif'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire (optionnel)</label>
                        <textarea name="commentaire" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Détails supplémentaires..."><?php echo e(old('commentaire')); ?></textarea>
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
                    <?php $__empty_1 = true; $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-sm text-gray-800"><?php echo e($reservation->salle->nom); ?></h4>
                                    <p class="text-xs text-gray-600"><?php echo e($reservation->date->format('d/m/Y')); ?></p>
                                    <p class="text-xs text-gray-600"><?php echo e(substr($reservation->heure_debut, 0, 5)); ?> - <?php echo e(substr($reservation->heure_fin, 0, 5)); ?></p>
                                </div>
                                <span class="px-2 py-1 rounded text-xs font-semibold 
                                    <?php if($reservation->statut == 'approuvee'): ?> bg-green-100 text-green-800
                                    <?php elseif($reservation->statut == 'refusee'): ?> bg-red-100 text-red-800
                                    <?php elseif($reservation->statut == 'annulee'): ?> bg-gray-100 text-gray-800
                                    <?php else: ?> bg-yellow-100 text-yellow-800
                                    <?php endif; ?>">
                                    <?php if($reservation->statut == 'approuvee'): ?> ✓ Approuvée
                                    <?php elseif($reservation->statut == 'refusee'): ?> ✗ Refusée
                                    <?php elseif($reservation->statut == 'annulee'): ?> Annulée
                                    <?php else: ?> ⏳ En attente
                                    <?php endif; ?>
                                </span>
                            </div>
                            <p class="text-xs text-gray-700 mt-2"><?php echo e($reservation->motif); ?></p>
                            <?php if($reservation->statut != 'approuvee' && $reservation->statut != 'annulee'): ?>
                                <form action="<?php echo e(route('enseignant.reservations.destroy', $reservation)); ?>" method="POST" class="mt-2" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800">Annuler</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-gray-500 text-center py-4">Aucune réservation</p>
                    <?php endif; ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($reservation->salle->nom); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($reservation->date->format('d/m/Y')); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e(substr($reservation->heure_debut, 0, 5)); ?> - <?php echo e(substr($reservation->heure_fin, 0, 5)); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($reservation->motif); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    <?php if($reservation->statut == 'approuvee'): ?> bg-green-100 text-green-800
                                    <?php elseif($reservation->statut == 'refusee'): ?> bg-red-100 text-red-800
                                    <?php elseif($reservation->statut == 'annulee'): ?> bg-gray-100 text-gray-800
                                    <?php else: ?> bg-yellow-100 text-yellow-800
                                    <?php endif; ?>">
                                    <?php if($reservation->statut == 'approuvee'): ?> Approuvée
                                    <?php elseif($reservation->statut == 'refusee'): ?> Refusée
                                    <?php elseif($reservation->statut == 'annulee'): ?> Annulée
                                    <?php else: ?> En attente
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <?php if($reservation->statut != 'approuvee' && $reservation->statut != 'annulee'): ?>
                                    <form action="<?php echo e(route('enseignant.reservations.destroy', $reservation)); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-900">Annuler</button>
                                    </form>
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
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\dell\Downloads\plateformehestim\resources\views/enseignant/reservations.blade.php ENDPATH**/ ?>