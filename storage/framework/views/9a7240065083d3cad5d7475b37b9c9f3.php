<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', 'HESTIM - Plateforme de Gestion Scolaire'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <?php if(file_exists(public_path('build/manifest.json'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <!-- Fallback: Tailwind CSS via CDN si Vite n'est pas compilé -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                    },
                },
            }
        </script>
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    <?php endif; ?>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-2xl font-bold text-indigo-600">HESTIM</h1>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="<?php echo e(route('dashboard')); ?>" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Dashboard
                            </a>
                            <?php if(auth()->guard()->check()): ?>
                                <?php if(auth()->user()->isAdmin()): ?>
                                    <a href="<?php echo e(route('admin.users.index')); ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                        Utilisateurs
                                    </a>
                                    <a href="<?php echo e(route('admin.salles.index')); ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                        Salles
                                    </a>
                                    <a href="<?php echo e(route('admin.cours.index')); ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                        Cours
                                    </a>
                                    <a href="<?php echo e(route('admin.emploi-du-temps.index')); ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                        Emploi du temps
                                    </a>
                                    <a href="<?php echo e(route('admin.statistiques')); ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                        Statistiques
                                    </a>
                                <?php elseif(auth()->user()->isEnseignant()): ?>
                                    <a href="<?php echo e(route('enseignant.planning')); ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                        Planning
                                    </a>
                                    <a href="<?php echo e(route('enseignant.reservations.index')); ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                        Réservations
                                    </a>
                                <?php elseif(auth()->user()->isEtudiant()): ?>
                                    <a href="<?php echo e(route('etudiant.emploi-du-temps')); ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                        Emploi du temps
                                    </a>
                                    <a href="<?php echo e(route('etudiant.reservations')); ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                        Réservations
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('notifications.index')); ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium relative">
                                    Notifications
                                    <?php if(auth()->user()->notifications()->where('lu', false)->count() > 0): ?>
                                        <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                            <?php echo e(auth()->user()->notifications()->where('lu', false)->count()); ?>

                                        </span>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <?php if(auth()->guard()->check()): ?>
                            <div class="flex items-center space-x-4">
                                <span class="text-gray-700"><?php echo e(auth()->user()->name); ?></span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    <?php if(auth()->user()->isAdmin()): ?> bg-purple-100 text-purple-800
                                    <?php elseif(auth()->user()->isEnseignant()): ?> bg-blue-100 text-blue-800
                                    <?php else: ?> bg-green-100 text-green-800
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst(auth()->user()->role)); ?>

                                </span>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                                Connexion
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
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

            <?php if($errors->any()): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</body>
</html>

<?php /**PATH C:\Users\dell\Downloads\plateformehestim\resources\views/layouts/app.blade.php ENDPATH**/ ?>