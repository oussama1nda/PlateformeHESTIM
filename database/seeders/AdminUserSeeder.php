<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer l'admin s'il n'existe pas
        if (!User::where('email', 'admin@hestim.ma')->exists()) {
            User::create([
                'name' => 'Administrateur',
                'email' => 'admin@hestim.ma',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
            echo "✓ Utilisateur Admin créé\n";
        } else {
            echo "⚠ Utilisateur Admin existe déjà\n";
        }

        // Créer l'enseignant s'il n'existe pas
        if (!User::where('email', 'enseignant@hestim.ma')->exists()) {
            User::create([
                'name' => 'Enseignant Test',
                'email' => 'enseignant@hestim.ma',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
            ]);
            echo "✓ Utilisateur Enseignant créé\n";
        } else {
            echo "⚠ Utilisateur Enseignant existe déjà\n";
        }

        // Créer l'étudiant s'il n'existe pas
        if (!User::where('email', 'etudiant@hestim.ma')->exists()) {
            User::create([
                'name' => 'Étudiant Test',
                'email' => 'etudiant@hestim.ma',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
            ]);
            echo "✓ Utilisateur Étudiant créé\n";
        } else {
            echo "⚠ Utilisateur Étudiant existe déjà\n";
        }
    }
}

