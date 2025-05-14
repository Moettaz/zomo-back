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
        Schema::table('transporteurs', function (Blueprint $table) {
            $table->enum('vehicule_type', ['confort', 'luxe', 'moto', 'taxi'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transporteurs', function (Blueprint $table) {
            $table->dropColumn('vehicule_type');
        });
    }
};
