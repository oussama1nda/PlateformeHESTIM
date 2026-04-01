

<?php $__env->startSection('title', 'Réserver une Place'); ?>
<?php $__env->startSection('page-title', 'Dashboard Etudiant'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center space-x-4">
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold">Réserver une Place</h2>
                <p class="text-blue-100 mt-1">Réservez votre place pour un cours</p>
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

    <?php if($selectedCours && $selectedEmploi): ?>
        <!-- Bouton retour -->
        <div class="flex items-center space-x-2">
            <a href="<?php echo e(route('etudiant.emploi-du-temps')); ?>" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="text-sm font-medium">Retour à l'emploi du temps</span>
            </a>
        </div>
    <?php endif; ?>

    <!-- Sélection du cours -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Sélectionner un cours</h3>
            <?php if($selectedCours && $selectedEmploi): ?>
                <button type="button" onclick="toggleCourseSelection()" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    <span id="toggleText">Changer de cours</span>
                    <svg id="toggleIcon" class="w-4 h-4 inline-block ml-1 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            <?php endif; ?>
        </div>
        
        <?php if($cours->isEmpty()): ?>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-yellow-800">
                    <strong>Attention :</strong> Vous n'avez aucun cours assigné. Veuillez contacter l'administrateur pour être assigné à un groupe.
                </p>
            </div>
        <?php else: ?>
        <div id="courseSelectionForm" class="<?php echo e($selectedCours && $selectedEmploi ? 'hidden' : ''); ?>">
        <form method="GET" action="<?php echo e(route('etudiant.reservations')); ?>" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cours</label>
                    <select name="cours_id" id="coursSelect" onchange="updateEmplois()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                        <option value="">Sélectionner un cours</option>
                        <?php $__currentLoopData = $cours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($c->id); ?>" <?php echo e($selectedCours && $selectedCours->id == $c->id ? 'selected' : ''); ?>>
                                <?php echo e($c->nom); ?> (<?php echo e($c->code); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Séance</label>
                    <select name="emploi_id" id="emploiSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                        <option value="">Sélectionner une séance</option>
                        <?php if($selectedCours): ?>
                            <?php if($selectedCours->emploisDuTemps->isEmpty()): ?>
                                <option value="" disabled>Aucune séance disponible pour ce cours</option>
                            <?php else: ?>
                                <?php $__currentLoopData = $selectedCours->emploisDuTemps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emploi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($emploi->id); ?>" <?php echo e($selectedEmploi && $selectedEmploi->id == $emploi->id ? 'selected' : ''); ?>>
                                        <?php echo e(ucfirst($emploi->jour)); ?> - <?php echo e(substr($emploi->heure_debut, 0, 5)); ?> à <?php echo e(substr($emploi->heure_fin, 0, 5)); ?> - <?php echo e($emploi->salle->nom ?? 'N/A'); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </select>
                    <?php if($selectedCours && $selectedCours->emploisDuTemps->isEmpty()): ?>
                        <p class="mt-1 text-sm text-yellow-600">Ce cours n'a pas encore d'emploi du temps défini.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                Afficher la salle
            </button>
        </form>
        </div>
        <?php endif; ?>
    </div>

    <?php if($selectedCours && $selectedEmploi): ?>
        <!-- Course Info Header -->
        <div class="bg-blue-600 rounded-lg p-6 text-white">
            <h2 class="text-xl font-bold mb-2">Réserver une Place pour le Cours :</h2>
            <div class="space-y-1">
                <p class="text-lg"><?php echo e($selectedCours->nom); ?> (<?php echo e($selectedCours->code); ?>)</p>
                <p class="text-blue-100"><?php echo e(ucfirst($selectedEmploi->jour)); ?> - <?php echo e(substr($selectedEmploi->heure_debut, 0, 5)); ?> à <?php echo e(substr($selectedEmploi->heure_fin, 0, 5)); ?></p>
                <p class="text-blue-100">Salle: <?php echo e($selectedEmploi->salle->nom ?? 'N/A'); ?> - Capacité: <?php echo e($capacite); ?> places</p>
                <p class="text-blue-100">Enseignant: <?php echo e($selectedCours->enseignant->name ?? 'N/A'); ?></p>
            </div>
        </div>

        <!-- Classroom Layout -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="max-w-4xl mx-auto">
                <!-- Board -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-4 mb-6 text-white text-center shadow-lg">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-semibold">Tableau et Professeur</span>
                    </div>
                </div>

                <!-- Seating Layout -->
                <div id="seatingLayout" class="space-y-4">
                    <?php
                        $placesReservees = $reservations->pluck('numero_place')->toArray();
                        $maReservation = $reservations->where('etudiant_id', auth()->id())->first();
                        $maPlace = $maReservation ? $maReservation->numero_place : null;
                        $rows = ceil($capacite / 4); // 4 places par rangée
                    ?>
                    
                    <?php for($row = 1; $row <= $rows; $row++): ?>
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-2">Rang <?php echo e($row); ?></p>
                            <div class="grid grid-cols-4 gap-3">
                                <?php for($place = 1; $place <= 4; $place++): ?>
                                    <?php
                                        $placeNum = ($row - 1) * 4 + $place;
                                        $isReserved = in_array($placeNum, $placesReservees);
                                        $isMyPlace = $maPlace == $placeNum;
                                        $isAvailable = $placeNum <= $capacite && !$isReserved;
                                    ?>
                                    
                                    <?php if($placeNum <= $capacite): ?>
                                        <div class="relative">
                                            <?php if($isMyPlace): ?>
                                                <button class="w-full h-20 rounded-lg bg-green-600 text-white border-2 border-green-700 shadow-md cursor-default">
                                                    <div class="flex items-center justify-center space-x-1">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                    <p class="text-xs mt-1 font-semibold">Ma place</p>
                                                </button>
                                            <?php elseif($isReserved): ?>
                                                <button class="w-full h-20 rounded-lg bg-red-200 border-2 border-red-300 cursor-not-allowed opacity-75" disabled>
                                                    <div class="flex items-center justify-center space-x-1 text-red-600">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </div>
                                                    <p class="text-xs mt-1 text-red-600">Occupée</p>
                                                </button>
                                            <?php else: ?>
                                                <form action="<?php echo e(route('etudiant.reservations.store')); ?>" method="POST" class="inline">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="cours_id" value="<?php echo e($selectedCours->id); ?>">
                                                    <input type="hidden" name="emploi_du_temps_id" value="<?php echo e($selectedEmploi->id); ?>">
                                                    <input type="hidden" name="numero_place" value="<?php echo e($placeNum); ?>">
                                                    <button type="submit" 
                                                            onclick="return confirm('Voulez-vous réserver la place <?php echo e($placeNum); ?> ?')"
                                                            class="w-full h-20 rounded-lg bg-gray-100 border-2 border-gray-300 hover:border-blue-500 hover:bg-blue-50 transition-all">
                                                        <div class="flex items-center justify-center space-x-1 text-gray-600">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                            </svg>
                                                        </div>
                                                        <p class="text-xs mt-1 text-gray-600 font-semibold">Place <?php echo e($placeNum); ?></p>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div></div>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <!-- Legend -->
                <div class="mt-8 flex items-center justify-center space-x-6 flex-wrap">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-green-600 rounded-lg border-2 border-green-700"></div>
                        <span class="text-sm text-gray-700 font-semibold">Ma place</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gray-100 border-2 border-gray-300 rounded-lg"></div>
                        <span class="text-sm text-gray-700">Disponible</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-red-200 border-2 border-red-300 rounded-lg"></div>
                        <span class="text-sm text-gray-700">Occupée</span>
                    </div>
                </div>

                <?php if($maReservation): ?>
                    <!-- Annuler la réservation -->
                    <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-800 mb-3">
                            <strong>Vous avez réservé la place <?php echo e($maPlace); ?> pour ce cours.</strong>
                        </p>
                        <form action="<?php echo e(route('etudiant.reservations.cancel', $maReservation->id)); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler votre réservation ?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm font-semibold">
                                Annuler ma réservation
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="max-w-md mx-auto">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Sélectionnez un cours</h3>
                <p class="text-gray-600">Veuillez sélectionner un cours et une séance pour réserver une place.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function updateEmplois() {
        const coursId = document.getElementById('coursSelect').value;
        const emploiSelect = document.getElementById('emploiSelect');
        
        if (coursId) {
            // Recharger la page avec le cours sélectionné
            window.location.href = '<?php echo e(route("etudiant.reservations")); ?>?cours_id=' + coursId;
        } else {
            emploiSelect.innerHTML = '<option value="">Sélectionner une séance</option>';
            // Réinitialiser l'URL si aucun cours n'est sélectionné
            window.location.href = '<?php echo e(route("etudiant.reservations")); ?>';
        }
    }
    
    function toggleCourseSelection() {
        const form = document.getElementById('courseSelectionForm');
        const toggleIcon = document.getElementById('toggleIcon');
        const toggleText = document.getElementById('toggleText');
        
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
            toggleIcon.classList.add('rotate-180');
            toggleText.textContent = 'Masquer';
        } else {
            form.classList.add('hidden');
            toggleIcon.classList.remove('rotate-180');
            toggleText.textContent = 'Changer de cours';
        }
    }
    
    // Vérifier si le formulaire de réservation fonctionne
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form[action*="reservations.store"]');
        forms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                const button = form.querySelector('button[type="submit"]');
                if (button) {
                    button.disabled = true;
                    button.textContent = 'Réservation en cours...';
                }
            });
        });
        
        // Scroll automatique vers la grille si un cours est sélectionné
        <?php if($selectedCours && $selectedEmploi): ?>
            setTimeout(function() {
                const seatingLayout = document.getElementById('seatingLayout');
                if (seatingLayout) {
                    seatingLayout.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 300);
        <?php endif; ?>
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\dell\Downloads\plateformehestim\resources\views/etudiant/reservations.blade.php ENDPATH**/ ?>