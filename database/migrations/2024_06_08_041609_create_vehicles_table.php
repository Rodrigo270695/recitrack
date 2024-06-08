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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('code', 10)->unique();
            $table->string('plate', 10)->unique();
            $table->year('year');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('capacity');
            $table->enum('status', ['Disponible', 'No Disponible', 'En Mantenimiento'])->default('Disponible');
            $table->foreignId('brandmodel_id')->constrained('brandmodels')->onDelete('restrict');
            $table->foreignId('vehicletype_id')->constrained('vehicletypes')->onDelete('restrict');
            $table->foreignId('vehiclecolor_id')->constrained('vehiclecolors')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
