<div class="space-y-6">
    <!-- Section Mes Cours -->
    <div class="bg-blue-600 rounded-lg p-6 text-white mb-6">
        <div class="flex items-center space-x-3 mb-2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h2 class="text-2xl font-bold">Mes Cours</h2>
        </div>
        <p class="text-blue-100">Gestion et modification de vos cours</p>
    </div>

    <!-- Liste des cours -->
    <div class="space-y-4">
        <?php
            $cours = auth()->user()->coursEnseignes()->with(['groupes', 'emploisDuTemps.salle', 'emploisDuTemps.groupe'])->get();
        ?>
        <?php $__empty_1 = true; $__currentLoopData = $cours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <h3 class="text-lg font-semibold text-gray-800"><?php echo e($cour->nom); ?></h3>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                <?php echo e($cour->code); ?>

                            </span>
                            <?php if($cour->emploisDuTemps->count() > 0): ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    Programmé
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    Non programmé
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Groupes assignés -->
                        <div class="mb-3">
                            <p class="text-sm text-gray-600 mb-1">Groupes :</p>
                            <div class="flex flex-wrap gap-2">
                                <?php $__currentLoopData = $cour->groupes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs"><?php echo e($groupe->nom); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        
                        <!-- Planning -->
                        <?php if($cour->emploisDuTemps->count() > 0): ?>
                            <div class="space-y-2">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Planning :</p>
                                <?php $__currentLoopData = $cour->emploisDuTemps->groupBy('jour'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jour => $emplois): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $emplois; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emploi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex flex-wrap gap-4 text-sm text-gray-600 bg-gray-50 p-3 rounded">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="font-semibold"><?php echo e(ucfirst($jour)); ?></span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span><?php echo e(substr($emploi->heure_debut, 0, 5)); ?> - <?php echo e(substr($emploi->heure_fin, 0, 5)); ?></span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                <span><?php echo e($emploi->salle->nom ?? 'N/A'); ?></span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2 py-1 rounded text-xs font-semibold 
                                                    <?php if($emploi->type_seance == 'cours'): ?> bg-blue-100 text-blue-800
                                                    <?php elseif($emploi->type_seance == 'td'): ?> bg-green-100 text-green-800
                                                    <?php else: ?> bg-purple-100 text-purple-800
                                                    <?php endif; ?>">
                                                    <?php echo e(strtoupper($emploi->type_seance)); ?>

                                                </span>
                                            </div>
                                            <?php if($emploi->groupe): ?>
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-xs text-gray-500">Groupe: <?php echo e($emploi->groupe->nom); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-gray-500 italic">Aucun planning défini pour ce cours</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-600">Aucun cours assigné</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php /**PATH C:\Users\dell\Downloads\plateformehestim\resources\views/enseignant/dashboard.blade.php ENDPATH**/ ?>