

<?php $__env->startSection('title', 'Mon Emploi du Temps'); ?>
<?php $__env->startSection('page-title', 'Dashboard Etudiant'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-blue-600 rounded-lg p-6 text-white">
        <div class="flex items-center space-x-3 mb-2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h2 class="text-2xl font-bold">Mon Emploi Du Temps</h2>
        </div>
        <p class="text-blue-100">Semaine du 3 au 7 novembre 2025</p>
    </div>

    <!-- Info Message -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="text-sm text-blue-800 font-medium">💡 Astuce</p>
                <p class="text-sm text-blue-700 mt-1">Cliquez sur un cours pour réserver votre place dans la salle</p>
            </div>
        </div>
    </div>

    <!-- Weekly Schedule -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <?php
            $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
            $groupes = auth()->user()->groupes;
            $cours = \App\Models\Cour::whereHas('groupes', function($q) use ($groupes) {
                $q->whereIn('groupes.id', $groupes->pluck('id'));
            })->with(['emploisDuTemps.salle', 'emploisDuTemps.groupe', 'enseignant'])->get();
        ?>

        <?php $__currentLoopData = $jours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $jour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-semibold text-gray-800 mb-3"><?php echo e($jour); ?></h3>
                <div class="space-y-3">
                    <?php
                        $emploisJour = collect();
                        foreach($cours as $cour) {
                            $emplois = $cour->emploisDuTemps->where('jour', strtolower($jour))
                                ->whereIn('groupe_id', $groupes->pluck('id'));
                            foreach($emplois as $emploi) {
                                $emploisJour->push([
                                    'cour' => $cour,
                                    'emploi' => $emploi
                                ]);
                            }
                        }
                        $emploisJour = $emploisJour->sortBy(function($item) {
                            return $item['emploi']->heure_debut;
                        });
                    ?>

                    <?php $__empty_1 = true; $__currentLoopData = $emploisJour; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $cour = $item['cour'];
                            $emploi = $item['emploi'];
                        ?>
                        <a href="<?php echo e(route('etudiant.reservations', ['cours_id' => $cour->id, 'emploi_id' => $emploi->id])); ?>" 
                           class="block bg-gray-50 rounded-lg p-3 border-l-4 hover:bg-gray-100 transition cursor-pointer
                            <?php if($emploi->type_seance == 'cours'): ?> border-blue-500 hover:border-blue-600
                            <?php elseif($emploi->type_seance == 'td'): ?> border-green-500 hover:border-green-600
                            <?php else: ?> border-purple-500 hover:border-purple-600
                            <?php endif; ?>">
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="font-semibold text-sm text-gray-800"><?php echo e($cour->nom); ?></h4>
                                <svg class="w-4 h-4 text-gray-400 hover:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="space-y-1 text-xs text-gray-600">
                                <div class="flex items-center space-x-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span><?php echo e(substr($emploi->heure_debut, 0, 5)); ?>-<?php echo e(substr($emploi->heure_fin, 0, 5)); ?></span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span><?php echo e($emploi->salle->nom ?? 'N/A'); ?></span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span><?php echo e($cour->enseignant->name ?? 'N/A'); ?></span>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="px-2 py-1 rounded text-xs font-semibold 
                                        <?php if($emploi->type_seance == 'cours'): ?> bg-blue-100 text-blue-800
                                        <?php elseif($emploi->type_seance == 'td'): ?> bg-green-100 text-green-800
                                        <?php else: ?> bg-purple-100 text-purple-800
                                        <?php endif; ?>">
                                        <?php echo e(strtoupper($emploi->type_seance)); ?>

                                    </span>
                                    <span class="text-xs text-blue-600 font-medium hover:text-blue-700">Réserver une place →</span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-gray-500 text-center py-4">Aucun cours</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\dell\Downloads\plateformehestim\resources\views/etudiant/emploi-du-temps.blade.php ENDPATH**/ ?>