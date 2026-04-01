

<?php $__env->startSection('title', 'Gestion des Utilisateurs'); ?>
<?php $__env->startSection('page-title', 'Tableau De bord Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">Utilisateurs total</p>
            <p class="text-3xl font-bold text-green-600 mt-2"><?php echo e(\App\Models\User::count()); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">Enseignants</p>
            <p class="text-3xl font-bold text-blue-600 mt-2"><?php echo e(\App\Models\User::where('role', 'enseignant')->count()); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">Étudiants</p>
            <p class="text-3xl font-bold text-blue-600 mt-2"><?php echo e(\App\Models\User::where('role', 'etudiant')->count()); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">Actifs</p>
            <p class="text-3xl font-bold text-green-600 mt-2"><?php echo e(\App\Models\User::count()); ?></p>
        </div>
    </div>

    <!-- Section Gestion des Utilisateurs -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Gestion des Utilisateurs</h2>
                    <p class="text-sm text-gray-600 mt-1">Créer, modifier et gérer les comptes utilisateurs</p>
                </div>
                <div class="flex space-x-3">
                    <a href="<?php echo e(route('admin.users.export')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        <span>Exporter Excel</span>
                    </a>
                    <button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>+ Nouvel Utilisateur</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Département</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = \App\Models\User::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($user->name); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo e($user->email); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php if($user->role == 'admin'): ?> bg-red-100 text-red-800
                                        <?php elseif($user->role == 'enseignant'): ?> bg-blue-100 text-blue-800
                                        <?php else: ?> bg-green-100 text-green-800
                                        <?php endif; ?>">
                                        <?php echo e(ucfirst($user->role)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo e($user->departement ? $user->departement->nom : 'Administration'); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Actif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <button class="text-yellow-600 hover:text-yellow-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Aucun utilisateur</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Création Utilisateur -->
<div id="userModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Créer un nouvel utilisateur</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <p class="text-sm text-gray-600 mb-6">Créez un compte pour un étudiant ou un enseignant</p>
        
        <form action="<?php echo e(route('admin.users.store')); ?>" method="POST" class="space-y-4" id="userForm">
            <?php echo csrf_field(); ?>
            <?php if($errors->any()): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom complet*</label>
                <input type="text" name="name" value="<?php echo e(old('name')); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php echo e($errors->has('name') ? 'border-red-500' : ''); ?>" placeholder="Ex: Jean Dupont">
                <?php $__errorArgs = ['name'];
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
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email*</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php echo e($errors->has('email') ? 'border-red-500' : ''); ?>" placeholder="exemple@email.com">
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
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe*</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php echo e($errors->has('password') ? 'border-red-500' : ''); ?>" placeholder="Min 8 caractères">
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
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                <select name="role" id="roleSelect" onchange="toggleGroupeField()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                    <option value="etudiant" <?php echo e(old('role') == 'etudiant' ? 'selected' : ''); ?>>Étudiant</option>
                    <option value="enseignant" <?php echo e(old('role') == 'enseignant' ? 'selected' : ''); ?>>Enseignant</option>
                    <option value="admin" <?php echo e(old('role') == 'admin' ? 'selected' : ''); ?>>Administrateur</option>
                </select>
                <?php $__errorArgs = ['role'];
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
            
            <!-- Champ Groupe (visible uniquement pour les étudiants) -->
            <div id="groupeField" style="display: <?php echo e(old('role') == 'etudiant' ? 'block' : 'none'); ?>;">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Groupe* <span class="text-gray-500 text-xs">(pour les étudiants)</span></label>
                    <a href="<?php echo e(route('admin.groupes.index')); ?>" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Créer un groupe</span>
                    </a>
                </div>
                <select name="groupe_id" id="groupeSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white <?php echo e($errors->has('groupe_id') ? 'border-red-500' : ''); ?>">
                    <option value="">Sélectionner un groupe</option>
                    <?php $__currentLoopData = \App\Models\Groupe::with('departement')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($groupe->id); ?>" <?php echo e(old('groupe_id') == $groupe->id ? 'selected' : ''); ?>>
                            <?php echo e($groupe->nom); ?> 
                            <?php if($groupe->departement): ?>
                                - <?php echo e($groupe->departement->nom); ?>

                            <?php endif; ?>
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['groupe_id'];
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
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Département</label>
                <input type="text" name="departement" value="<?php echo e(old('departement')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: Informatique">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                <input type="text" name="telephone" value="<?php echo e(old('telephone')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: +33612345678">
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Créer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('userModal').classList.remove('hidden');
        // Réinitialiser le formulaire
        document.getElementById('userForm').reset();
        // Supprimer les messages d'erreur
        const errorDiv = document.querySelector('#userForm .bg-red-100');
        if (errorDiv) {
            errorDiv.remove();
        }
        // Réinitialiser le champ groupe
        setTimeout(toggleGroupeField, 100);
    }
    
    function closeModal() {
        document.getElementById('userModal').classList.add('hidden');
        // Réinitialiser le formulaire
        document.getElementById('userForm').reset();
    }
    
    // Fermer la modal en cliquant à l'extérieur
    window.onclick = function(event) {
        const modal = document.getElementById('userModal');
        if (event.target == modal) {
            closeModal();
        }
    }
    
    // Toggle le champ groupe selon le rôle
    function toggleGroupeField() {
        const roleSelect = document.getElementById('roleSelect');
        const groupeField = document.getElementById('groupeField');
        const groupeSelect = document.getElementById('groupeSelect');
        
        if (roleSelect && groupeField && groupeSelect) {
            if (roleSelect.value === 'etudiant') {
                groupeField.style.display = 'block';
                groupeSelect.setAttribute('required', 'required');
            } else {
                groupeField.style.display = 'none';
                groupeSelect.removeAttribute('required');
                groupeSelect.value = '';
            }
        }
    }
    
    // Initialiser au chargement
    document.addEventListener('DOMContentLoaded', function() {
        toggleGroupeField();
        
        // Afficher les erreurs de validation si présentes
        <?php if($errors->any()): ?>
            openModal();
        <?php endif; ?>
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\dell\Downloads\plateformehestim\resources\views/admin/users/index.blade.php ENDPATH**/ ?>