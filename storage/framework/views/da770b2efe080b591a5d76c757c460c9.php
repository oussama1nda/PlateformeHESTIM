

<?php $__env->startSection('title', 'Mes Notifications'); ?>
<?php $__env->startSection('page-title', 'Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Mes Notifications</h2>
                    <p class="text-blue-100 mt-1">Toutes vos notifications</p>
                </div>
            </div>
            <?php if(auth()->user()->notifications()->where('lu', false)->count() > 0): ?>
                <form action="<?php echo e(route('notifications.read-all')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition font-semibold">
                        Tout marquer comme lu
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <!-- Liste des notifications -->
    <div class="space-y-3">
        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow <?php echo e(!$notification->lu ? 'border-l-4 border-blue-500' : ''); ?>">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-1">
                                <?php if($notification->type == 'success'): ?>
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                <?php elseif($notification->type == 'error'): ?>
                                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                <?php elseif($notification->type == 'warning'): ?>
                                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <h3 class="text-lg font-semibold text-gray-800"><?php echo e($notification->titre); ?></h3>
                                    <?php if(!$notification->lu): ?>
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Nouveau</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-gray-600 mb-2"><?php echo e($notification->message); ?></p>
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span><?php echo e($notification->created_at->diffForHumans()); ?></span>
                                    <?php if($notification->lien): ?>
                                        <a href="<?php echo e($notification->lien); ?>" class="text-blue-600 hover:text-blue-800 font-semibold">
                                            Voir plus →
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <?php if(!$notification->lu): ?>
                            <form action="<?php echo e(route('notifications.read', $notification)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-gray-400 hover:text-gray-600" title="Marquer comme lu">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <div class="max-w-md mx-auto">
                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Aucune notification</h3>
                    <p class="text-gray-600">Vous n'avez pas encore de notifications.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($notifications->hasPages()): ?>
        <div class="mt-6">
            <?php echo e($notifications->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\dell\Desktop\plateformehestim\resources\views/notifications/index.blade.php ENDPATH**/ ?>