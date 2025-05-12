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
        Schema::create('transporteurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('email')->unique();
            $table->string('username');
            $table->string('password');
            $table->string('phone', 8);
            $table->integer('points')->default(0);
            $table->string('image_url')->nullable();
            $table->foreignId('service_id')->constrained();
            $table->boolean('disponibilite')->default(true);
            $table->decimal('note_moyenne', 3, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transporteurs');
    }
};
