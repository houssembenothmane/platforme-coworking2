<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avis', function (Blueprint $table) {
            $table->id('IdAvis');
            $table->unsignedBigInteger('IdClient');
            $table->unsignedBigInteger('IdEspace');
            $table->integer('note')->unsigned()->between(1, 5);
            $table->string('commentaire', 500)->nullable();
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
        Schema::dropIfExists('avis');
    }
};
