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
            $table->string('type_menagement');
            $table->string('type_vehicule');
            $table->decimal('distance', 8, 2);
            $table->string('from');
            $table->string('to');
            $table->time('heure_reservation');
            $table->integer('etage');
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
