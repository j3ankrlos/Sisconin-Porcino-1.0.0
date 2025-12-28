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
        Schema::create('animal_eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animals')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('tipo'); // movimiento, parto, gestacion, maternidad, salud, otro
            $table->string('suceso'); // Ej: Destete total, Parto, Movimiento
            $table->text('detalle')->nullable();
            
            // Ubicación del evento o destino
            $table->foreignId('seccion_id')->nullable()->constrained('secciones')->onDelete('set null');
            $table->string('corral')->nullable();
            
            $table->date('fecha');
            $table->json('metadata')->nullable(); // Para datos extras como cantidad de lechones, pesos, etc.

            $table->timestamps();
            
            $table->index(['animal_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_eventos');
    }
};
