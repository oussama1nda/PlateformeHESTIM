<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservation_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('emploi_du_temps_id')->constrained('emploi_du_temps')->onDelete('cascade');
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->integer('numero_place'); // Numéro de la place/table dans la salle
            $table->enum('statut', ['reservee', 'annulee'])->default('reservee');
            $table->timestamps();
            
            // Un étudiant ne peut réserver qu'une place par cours/emploi du temps (seulement si réservée)
            // Une place ne peut être réservée qu'une fois par cours/emploi du temps (seulement si réservée)
            $table->index(['emploi_du_temps_id', 'numero_place', 'statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_places');
    }
};

