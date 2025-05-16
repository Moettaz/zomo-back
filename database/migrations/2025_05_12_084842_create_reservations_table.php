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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('transporteur_id')->constrained();
            $table->foreignId('service_id')->constrained();
            $table->dateTime('date_reservation');
            $table->string('status');
            $table->text('commentaire')->nullable();
            $table->string('colis_size')->nullable();
            $table->string('type_menagement')->nullable();
            $table->string('type_vehicule')->nullable();
            $table->string('distance')->nullable();
            $table->string('from');
            $table->string('to');
            $table->string('heure_reservation')->nullable();
            $table->integer('etage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
