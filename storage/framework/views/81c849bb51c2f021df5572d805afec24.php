

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard ' . ucfirst(auth()->user()->role)); ?>

<?php $__env->startSection('content'); ?>
<?php if(auth()->user()->isAdmin()): ?>
    <?php echo $__env->make('admin.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(auth()->user()->isEnseignant()): ?>
    <?php echo $__env->make('enseignant.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(auth()->user()->isEtudiant()): ?>
    <?php echo $__env->make('etudiant.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\dell\Downloads\plateformehestim\resources\views/dashboard.blade.php ENDPATH**/ ?>