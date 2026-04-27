<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('espaces', function (Blueprint $table) {
            $table->id('IdEspace');
            $table->string('nom', 120);
            $table->text('description')->nullable();
            $table->decimal('prix_heure', 8, 2)->default(0);
            $table->integer('capacite')->default(1);
            $table->enum('statut', ['Disponible', 'Indisponible'])->default('Disponible');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('espaces');
    }
};
