<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('IdReservation');
            $table->unsignedBigInteger('IdClient');
            $table->unsignedBigInteger('IdEspace');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->enum('statut', ['Confirmée', 'En attente', 'Annulée', 'Terminée'])->default('En attente');
            $table->decimal('montant', 8, 2)->default(0);
            $table->timestamps();

            $table->foreign('IdClient')
                  ->references('IdClient')
                  ->on('clients')
                  ->onDelete('cascade');

            $table->foreign('IdEspace')
                  ->references('IdEspace')
                  ->on('espaces')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
