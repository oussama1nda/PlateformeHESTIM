<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Groupe;
use App\Models\Departement;

class GroupeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un département par défaut si nécessaire
        $departement = Departement::firstOrCreate([
            'nom' => 'Cycle d\'Ingénieur'
        ], [
            'description' => 'Département Cycle d\'Ingénieur'
        ]);

        // Créer les groupes
        $groupes = [
            ['nom' => '1ère Année Cycle d\'Ingénieur', 'departement_id' => $departement->id],
            ['nom' => '2ème Année Cycle d\'Ingénieur', 'departement_id' => $departement->id],
            ['nom' => '3ème Année Cycle d\'Ingénieur', 'departement_id' => $departement->id],
            ['nom' => '1ère Année Informatique', 'departement_id' => $departement->id],
            ['nom' => '2ème Année Informatique', 'departement_id' => $departement->id],
            ['nom' => '3ème Année Informatique', 'departement_id' => $departement->id],
        ];

        foreach ($groupes as $groupe) {
            Groupe::firstOrCreate(
                ['nom' => $groupe['nom']],
                $groupe
            );
        }

        echo "✓ Groupes créés avec succès\n";
    }
}

