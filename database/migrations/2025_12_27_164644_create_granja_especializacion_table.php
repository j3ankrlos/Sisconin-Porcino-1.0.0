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
        Schema::create('granja_especializacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('granja_id')->constrained('granjas')->onDelete('cascade');
            $table->foreignId('especializacion_id')->constrained('especializacions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('granja_especializacion');
    }
};
