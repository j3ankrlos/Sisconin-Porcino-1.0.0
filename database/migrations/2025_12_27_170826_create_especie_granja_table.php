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
        Schema::create('especie_granja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('especie_id')->constrained('especies')->onDelete('cascade');
            $table->foreignId('granja_id')->constrained('granjas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('especie_granja');
    }
};
