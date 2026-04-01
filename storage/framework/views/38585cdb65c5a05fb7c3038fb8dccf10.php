

<?php $__env->startSection('title', 'Mes Cours'); ?>
<?php $__env->startSection('page-title', 'Dashboard Etudiant'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Mes Cours</h2>
                    <p class="text-blue-100 mt-1">Liste complète de tous vos cours</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold"><?php echo e(auth()->user()->groupes->count()); ?></div>
                <div class="text-blue-100 text-sm">Groupe(s)</div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <?php
        $groupes = auth()->user()->groupes;
        $cours = \App\Models\Cour::whereHas('groupes', function($q) use ($groupes) {
            $q->whereIn('groupes.id', $groupes->pluck('id'));
        })->with(['enseignant', 'emploisDuTemps.salle', 'emploisDuTemps.groupe'])->get();
        
        $coursProgrammes = $cours->filter(function($cour) use ($groupes) {
            return $cour->emploisDuTemps->whereIn('groupe_id', $groupes->pluck('id'))->count() > 0;
        })->count();
    ?>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total des cours</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e($cours->count()); ?></p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Cours programmés</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e($coursProgrammes); ?></p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Volume horaire</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e($cours->sum('volume_horaire')); ?>h</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des cours -->
    <div class="space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $cours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $emploisEtudiant = $cour->emploisDuTemps->whereIn('groupe_id', $groupes->pluck('id'));
            ?>
            
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="bg-blue-100 rounded-lg p-2">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800"><?php echo e($cour->nom); ?></h3>
                                    <p class="text-sm text-gray-500"><?php echo e($cour->code); ?></p>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                    <?php echo e($cour->code); ?>

                                </span>
                                <?php if($emploisEtudiant->count() > 0): ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                        ✓ Programmé
                                    </span>
                                <?php else: ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        ⏳ Non programmé
                                    </span>
                                <?php endif; ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                    <?php echo e($cour->volume_horaire); ?>h
                                </span>
                            </div>
                            
                            <!-- Informations enseignant -->
                            <div class="flex items-center space-x-2 mb-4 p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">
                                    <span class="font-semibold">Enseignant :</span> <?php echo e($cour->enseignant->name ?? 'Non assigné'); ?>

                                </span>
                            </div>
                            
                            <!-- Planning -->
                            <?php if($emploisEtudiant->count() > 0): ?>
                                <div class="mt-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Planning
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <?php $__currentLoopData = $emploisEtudiant->groupBy('jour'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jour => $emplois): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $__currentLoopData = $emplois; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emploi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200 hover:shadow-md transition-shadow">
                                                    <div class="flex items-start justify-between mb-2">
                                                        <div class="flex items-center space-x-2">
                                                            <div class="bg-blue-600 rounded-full p-1.5">
                                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                            </div>
                                                            <span class="font-bold text-gray-800"><?php echo e(ucfirst($jour)); ?></span>
                                                        </div>
                                                        <span class="px-2 py-1 rounded text-xs font-semibold 
                                                            <?php if($emploi->type_seance == 'cours'): ?> bg-blue-600 text-white
                                                            <?php elseif($emploi->type_seance == 'td'): ?> bg-green-600 text-white
                                                            <?php else: ?> bg-purple-600 text-white
                                                            <?php endif; ?>">
                                                            <?php echo e(strtoupper($emploi->type_seance)); ?>

                                                        </span>
                                                    </div>
                                                    
                                                    <div class="space-y-2 mt-3">
                                                        <div class="flex items-center space-x-2 text-sm text-gray-700">
                                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            <span class="font-semibold"><?php echo e(substr($emploi->heure_debut, 0, 5)); ?> - <?php echo e(substr($emploi->heure_fin, 0, 5)); ?></span>
                                                        </div>
                                                        
                                                        <div class="flex items-center space-x-2 text-sm text-gray-700">
                                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                            </svg>
                                                            <span><?php echo e($emploi->salle->nom ?? 'N/A'); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <p class="text-sm text-yellow-800 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        Aucun planning défini pour ce cours dans vos groupes
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <div class="max-w-md mx-auto">
                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Aucun cours disponible</h3>
                    <p class="text-gray-600">Vous n'avez pas encore de cours assignés à vos groupes.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\dell\Desktop\plateformehestim\resources\views/etudiant/cours.blade.php ENDPATH**/ ?>