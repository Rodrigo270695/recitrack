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
        Schema::create('horaries', function (Blueprint $table) {
            $table->id();
            $table->string('day');
            $table->string('type');
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('maintenance_id')->constrained('maintenances')->onDelete('restrict');
            $table->foreignId('vehicleoccupant_id')->constrained('vehicleoccupants')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horaries');
    }
};
