

<?php $__env->startSection('title', 'Historique des Actions'); ?>
<?php $__env->startSection('page-title', 'Tableau De bord Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Section Historique -->
    <div class="bg-blue-50 rounded-lg p-6 mb-6">
        <div class="flex items-center space-x-3 mb-2">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h2 class="text-xl font-bold text-gray-800">Historique des Actions</h2>
        </div>
        <p class="text-sm text-gray-600">Journalisation des opérations système</p>
    </div>

    <!-- Liste des Actions -->
    <div class="space-y-3">
        <?php
            $actions = [
                ['type' => 'Connexion', 'user' => 'Administrateur', 'email' => 'admin@example.com', 'action' => 'connecté', 'date' => '07/11/2025 13:40:33'],
                ['type' => 'Déconnexion', 'user' => 'Marie Martin', 'email' => null, 'action' => 'déconnecté', 'date' => '07/11/2025 13:40:26'],
                ['type' => 'Connexion', 'user' => 'Marie Martin', 'email' => 'student@example.com', 'action' => 'connecté', 'date' => '07/11/2025 13:36:17'],
                ['type' => 'Déconnexion', 'user' => 'Administrateur', 'email' => null, 'action' => 'déconnecté', 'date' => '07/11/2025 13:35:49'],
            ];
        ?>

        <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold 
                        <?php if($action['type'] == 'Connexion'): ?> bg-blue-100 text-blue-800
                        <?php else: ?> bg-gray-100 text-gray-800
                        <?php endif; ?>">
                        <?php echo e($action['type']); ?>

                    </span>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800">
                            <span class="font-semibold"><?php echo e($action['user']); ?></span>
                            <?php if($action['email']): ?>
                                <span class="text-gray-600">(<?php echo e($action['email']); ?>)</span>
                            <?php endif; ?>
                            <span class="text-gray-600"><?php echo e($action['action']); ?></span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1"><?php echo e($action['date']); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\dell\Desktop\plateformehestim\resources\views/admin/statistiques/index.blade.php ENDPATH**/ ?>