<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>Connexion - HESTIM</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <?php if(file_exists(public_path('build/manifest.json'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                        colors: {
                            'hestim-blue': '#00175f',
                            'hestim-dark': '#1e293b',
                        },
                    },
                },
            }
        </script>
        <style>
            body { font-family: 'Inter', sans-serif; }
            .bg-hestim-blue { background-color: #00175f !important; }
            .text-hestim-blue { color: #00175f !important; }
            .border-hestim-blue { border-color: #00175f !important; }
            .bg-blue-600 { background-color: #00175f !important; }
            .bg-blue-700 { background-color: #00175f !important; }
            .bg-blue-800 { background-color: #00175f !important; }
            .text-blue-600 { color: #00175f !important; }
            .text-blue-700 { color: #00175f !important; }
            .text-blue-800 { color: #00175f !important; }
            .border-blue-500 { border-color: #00175f !important; }
            .border-blue-600 { border-color: #00175f !important; }
            .focus\:ring-hestim-blue:focus { --tw-ring-color: #00175f !important; }
            .focus\:border-hestim-blue:focus { border-color: #00175f !important; }
            .hover\:bg-blue-800:hover { background-color: #00175f !important; }
            .hover\:text-hestim-blue:hover { color: #00175f !important; }
            .from-blue-600 { --tw-gradient-from: #00175f !important; }
            .to-blue-700 { --tw-gradient-to: #00175f !important; }
        </style>
    <?php endif; ?>
</head>
<body class="font-sans antialiased">
    <div class="flex min-h-screen">
        <!-- Left Panel - Logo -->
        <div class="hidden lg:flex lg:w-2/5 bg-hestim-blue text-white flex-col justify-between p-12">
            <div class="flex flex-col items-center justify-center h-full">
                <img src="<?php echo e(asset('hestim2.png')); ?>" alt="HESTIM" class="h-72 w-auto mb-12">
                
            </div>
            <p class="text-sm text-gray-400">Copyright© 2025 HESTIM</p>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="flex-1 flex flex-col justify-center px-8 py-12 bg-white lg:w-1/2">
            <div class="mx-auto w-full max-w-md">
                <!-- User Icon -->
                <div class="flex justify-end items-center mb-8">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>

                <!-- Logo Small -->
                <div class="flex items-center space-x-3 mb-8">
                    <img src="<?php echo e(asset('hestim.png')); ?>" alt="HESTIM" class="h-32 w-auto">
                    <div>
                        
                    </div>
                </div>

                <!-- Login Form -->
                <form action="<?php echo e(route('login')); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="<?php echo e(old('email')); ?>"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-hestim-blue focus:border-hestim-blue outline-none transition"
                               placeholder="Email">
                        <?php $__errorArgs = ['email'];
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

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                        <div class="relative">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-hestim-blue focus:border-hestim-blue outline-none transition pr-20">
                            <button type="button" 
                                    onclick="togglePassword()"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600 hover:text-gray-800 text-sm">
                                <span id="toggleText">Afficher</span>
                            </button>
                        </div>
                        <?php $__errorArgs = ['password'];
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

                    <!-- Forgot Password -->
                    <div class="text-right">
                        <a href="#" class="text-sm text-gray-600 hover:text-hestim-blue">Mot de passe oublié ?</a>
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                        <select id="role" 
                                name="role" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-hestim-blue focus:border-hestim-blue outline-none transition appearance-none bg-white">
                            <option value="admin" <?php echo e(old('role') == 'admin' ? 'selected' : ''); ?>>Administrateur</option>
                            <option value="enseignant" <?php echo e(old('role') == 'enseignant' ? 'selected' : ''); ?>>Enseignant</option>
                            <option value="etudiant" <?php echo e(old('role') == 'etudiant' ? 'selected' : ''); ?>>Étudiant</option>
                        </select>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" 
                            class="w-full bg-hestim-blue text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-800 transition uppercase tracking-wide">
                        Connexion
                    </button>
                </form>
            </div>
        </div>
    </div>

    <footer class="fixed inset-x-0 bottom-0 bg-hestim-blue text-white text-center py-3">
        <p class="text-sm">Copyright© 2025 HESTIM</p>
    </footer>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleText = document.getElementById('toggleText');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleText.textContent = 'Masquer';
            } else {
                passwordInput.type = 'password';
                toggleText.textContent = 'Afficher';
            }
        }
    </script>
    
</body>
</html>
<?php /**PATH C:\Users\dell\Desktop\plateformehestim\resources\views/auth/login.blade.php ENDPATH**/ ?>