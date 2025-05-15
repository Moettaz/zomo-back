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
        Schema::create('trajets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('transporteur_id')->constrained();
            $table->foreignId('service_id')->constrained();
            $table->dateTime('date_heure_depart');
            $table->dateTime('date_heure_arrivee');
            $table->string('point_depart');
            $table->string('point_arrivee');
            $table->decimal('prix', 10, 2);
            $table->double('note')->nullable();
            $table->string('etat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trajets');
    }
};
