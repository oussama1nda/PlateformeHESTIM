<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Totale des Salles</p>
                    <p class="text-3xl font-bold text-red-600 mt-2"><?php echo e(\App\Models\Salle::count()); ?></p>
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
                    <p class="text-3xl font-bold text-green-600 mt-2"><?php echo e(\App\Models\Salle::where('disponible', true)->count()); ?></p>
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
                    <p class="text-3xl font-bold text-red-600 mt-2"><?php echo e(\App\Models\Reservation::where('statut', 'approuvee')->count()); ?></p>
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
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo e(\App\Models\Salle::sum('capacite')); ?></p>
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
                    <a href="<?php echo e(route('admin.salles.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        + Nouvelle Salle
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = \App\Models\Salle::latest()->take(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $salle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div>
                                <h3 class="font-semibold text-gray-800"><?php echo e($salle->nom); ?></h3>
                                <p class="text-sm text-gray-600">Capacité: <?php echo e($salle->capacite); ?></p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo e($salle->disponible ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e($salle->disponible ? 'Disponible' : 'Réservée'); ?>

                            </span>
                            <span class="text-sm text-gray-600">Type: <?php echo e(ucfirst($salle->type)); ?></span>
                        </div>
                        <div class="flex space-x-2">
                            <a href="<?php echo e(route('admin.salles.edit', $salle)); ?>" class="text-blue-600 hover:text-blue-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="<?php echo e(route('admin.salles.destroy', $salle)); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-800">
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

<?php /**PATH C:\Users\dell\Desktop\plateformehestim\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>