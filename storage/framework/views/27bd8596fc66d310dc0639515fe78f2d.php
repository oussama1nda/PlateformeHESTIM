

<?php $__env->startSection('title', 'Gestion des Salles'); ?>
<?php $__env->startSection('page-title', 'Tableau De bord Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Totale des Salles</p>
                    <p class="text-3xl font-bold text-red-600 mt-2"><?php echo e($salles->count()); ?></p>
                </div>
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Salles Disponibles</p>
                    <p class="text-3xl font-bold text-green-600 mt-2"><?php echo e($salles->where('disponible', true)->count()); ?></p>
                </div>
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Salles Réservées</p>
                    <p class="text-3xl font-bold text-red-600 mt-2"><?php echo e($salles->where('disponible', false)->count()); ?></p>
                </div>
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Capacité Totale</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo e($salles->sum('capacite')); ?></p>
                </div>
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Section Gestion des Salles -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Gestion des Salles</h2>
                    <p class="text-sm text-gray-600 mt-1">Gérez et configurez les salles de cours</p>
                </div>
                <div class="flex space-x-3">
                    <a href="<?php echo e(route('admin.cours.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        + Nouveau Cours
                    </a>
                    <button onclick="openSalleModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>+ Nouvelle Salle</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $salles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $salle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-4">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <h3 class="font-semibold text-gray-800"><?php echo e($salle->nom); ?></h3>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo e($salle->disponible ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'); ?>">
                                        <?php echo e($salle->disponible ? 'Disponible' : 'Réservée'); ?>

                                    </span>
                                </div>
                                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        <span>Capacité: <?php echo e($salle->capacite); ?></span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span>Type: <?php echo e(ucfirst(str_replace('_', ' ', $salle->type))); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="<?php echo e(route('admin.salles.edit', $salle)); ?>" class="text-blue-600 hover:text-blue-800 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="<?php echo e(route('admin.salles.destroy', $salle)); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-gray-600 text-center py-8">Aucune salle disponible</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Création Salle -->
<div id="salleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Créer une nouvelle salle</h3>
            <button onclick="closeSalleModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <p class="text-sm text-gray-600 mb-6">Ajoutez une nouvelle salle de cours au système</p>
        
        <form action="<?php echo e(route('admin.salles.store')); ?>" method="POST" class="space-y-4" id="salleForm">
            <?php echo csrf_field(); ?>
            <?php if($errors->any()): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la salle*</label>
                <input type="text" name="nom" value="<?php echo e(old('nom')); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php echo e($errors->has('nom') ? 'border-red-500' : ''); ?>" placeholder="Ex: Salle 101">
                <?php $__errorArgs = ['nom'];
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de la salle*</label>
                <input type="text" name="numero" value="<?php echo e(old('numero')); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php echo e($errors->has('numero') ? 'border-red-500' : ''); ?>" placeholder="Ex: 101">
                <?php $__errorArgs = ['numero'];
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
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Capacité*</label>
                    <input type="number" name="capacite" value="<?php echo e(old('capacite')); ?>" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php echo e($errors->has('capacite') ? 'border-red-500' : ''); ?>" placeholder="Ex: 30">
                    <?php $__errorArgs = ['capacite'];
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de salle*</label>
                    <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white <?php echo e($errors->has('type') ? 'border-red-500' : ''); ?>">
                        <option value="">Sélectionner un type</option>
                        <option value="amphitheatre" <?php echo e(old('type') == 'amphitheatre' ? 'selected' : ''); ?>>Amphithéâtre</option>
                        <option value="salle_cours" <?php echo e(old('type') == 'salle_cours' ? 'selected' : ''); ?>>Salle de cours</option>
                        <option value="laboratoire" <?php echo e(old('type') == 'laboratoire' ? 'selected' : ''); ?>>Laboratoire</option>
                        <option value="salle_td" <?php echo e(old('type') == 'salle_td' ? 'selected' : ''); ?>>Salle TD</option>
                    </select>
                    <?php $__errorArgs = ['type'];
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Équipements</label>
                <textarea name="equipements" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: Projecteur, Tableau interactif, Ordinateurs..."><?php echo e(old('equipements')); ?></textarea>
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" name="disponible" id="disponible" value="1" <?php echo e(old('disponible', true) ? 'checked' : ''); ?> class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="disponible" class="ml-2 text-sm text-gray-700">Salle disponible</label>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeSalleModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
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
    function openSalleModal() {
        document.getElementById('salleModal').classList.remove('hidden');
        // Réinitialiser le formulaire
        document.getElementById('salleForm').reset();
        // Supprimer les messages d'erreur
        const errorDiv = document.querySelector('#salleForm .bg-red-100');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    function closeSalleModal() {
        document.getElementById('salleModal').classList.add('hidden');
        // Réinitialiser le formulaire
        document.getElementById('salleForm').reset();
    }
    
    // Fermer la modal en cliquant à l'extérieur
    window.onclick = function(event) {
        const modal = document.getElementById('salleModal');
        if (event.target == modal) {
            closeSalleModal();
        }
    }
    
    // Afficher les erreurs de validation si présentes
    <?php if($errors->any()): ?>
        document.addEventListener('DOMContentLoaded', function() {
            openSalleModal();
        });
    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\dell\Downloads\plateformehestim\resources\views/admin/salles/index.blade.php ENDPATH**/ ?>