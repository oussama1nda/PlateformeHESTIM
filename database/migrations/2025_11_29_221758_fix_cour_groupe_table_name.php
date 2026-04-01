<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Si l'ancienne table existe, la supprimer
        if (Schema::hasTable('cours_groupe')) {
            Schema::dropIfExists('cours_groupe');
        }
        
        // Créer la nouvelle table avec le bon nom
        if (!Schema::hasTable('cour_groupe')) {
            Schema::create('cour_groupe', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cour_id')->constrained('cours')->onDelete('cascade');
                $table->foreignId('groupe_id')->constrained('groupes')->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['cour_id', 'groupe_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Si la nouvelle table existe, la supprimer
        if (Schema::hasTable('cour_groupe')) {
            Schema::dropIfExists('cour_groupe');
        }
        
        // Recréer l'ancienne table si nécessaire
        if (!Schema::hasTable('cours_groupe')) {
            Schema::create('cours_groupe', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
                $table->foreignId('groupe_id')->constrained('groupes')->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['cours_id', 'groupe_id']);
            });
        }
    }
};
