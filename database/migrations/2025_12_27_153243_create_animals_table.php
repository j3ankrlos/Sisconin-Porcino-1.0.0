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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('id_animal')->unique(); // ID Principal (ej: Arete, Tatuaje)
            $table->string('id_oreja')->nullable()->unique(); // ID Adicional
            
            // Relaciones de tipo
            $table->foreignId('especie_id')->constrained('especies');
            $table->foreignId('raza_id')->constrained('razas');
            
            // Atributos básicos
            $table->enum('sexo', ['M', 'F']);
            $table->date('fecha_nacimiento');
            
            // Pedigree (Auto-referencial)
            $table->foreignId('padre_id')->nullable()->constrained('animals')->onDelete('set null');
            $table->foreignId('madre_id')->nullable()->constrained('animals')->onDelete('set null');
            
            // Atributos de ciclo de vida
            $table->string('estado')->default('activo'); // activo, vendido, fallecido, descarte
            $table->string('fase_reproductiva')->nullable(); // Ej: Gestación, Lactancia, Engorde
            $table->decimal('peso_nacimiento', 8, 2)->nullable();
            
            $table->text('notas')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para rendimiento
            $table->index('id_animal');
            $table->index('id_oreja');
            $table->index('raza_id');
            $table->index('especie_id');
            $table->index(['especie_id', 'estado']);
            $table->index('fecha_nacimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
