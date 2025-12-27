<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear Especies
        $porcino = \App\Models\Especie::create([
            'nombre' => 'Porcino',
            'descripcion' => 'Producción de carne de cerdo'
        ]);

        $bovino = \App\Models\Especie::create([
            'nombre' => 'Bovino',
            'descripcion' => 'Producción de carne y leche vacuna'
        ]);

        // 2. Crear Razas para Porcinos
        $landrace = \App\Models\Raza::create([
            'especie_id' => $porcino->id,
            'nombre' => 'Landrace',
            'descripcion' => 'Raza prolífica y excelente madre'
        ]);

        $duroc = \App\Models\Raza::create([
            'especie_id' => $porcino->id,
            'nombre' => 'Duroc',
            'descripcion' => 'Raza rústica con excelente calidad de carne'
        ]);

        // 3. Crear Animales (Abuelos)
        $abuelo = \App\Models\Animal::create([
            'id_animal' => 'P-001',
            'especie_id' => $porcino->id,
            'raza_id' => $duroc->id,
            'sexo' => 'M',
            'fecha_nacimiento' => '2022-01-01',
            'estado' => 'activo'
        ]);

        $abuela = \App\Models\Animal::create([
            'id_animal' => 'P-002',
            'especie_id' => $porcino->id,
            'raza_id' => $landrace->id,
            'sexo' => 'F',
            'fecha_nacimiento' => '2022-02-15',
            'estado' => 'activo'
        ]);

        // 4. Crear Padre (Hijo de abuelos)
        $padre = \App\Models\Animal::create([
            'id_animal' => 'P-100',
            'especie_id' => $porcino->id,
            'raza_id' => $duroc->id,
            'sexo' => 'M',
            'fecha_nacimiento' => '2023-05-10',
            'padre_id' => $abuelo->id,
            'madre_id' => $abuela->id,
            'estado' => 'activo'
        ]);

        // 5. Crear Madre (Sin pedigree conocido)
        $madre = \App\Models\Animal::create([
            'id_animal' => 'P-200',
            'especie_id' => $porcino->id,
            'raza_id' => $landrace->id,
            'sexo' => 'F',
            'fecha_nacimiento' => '2023-06-20',
            'estado' => 'activo',
            'fase_reproductiva' => 'Gestación'
        ]);

        // 6. Crear Hijos (Tercera generación)
        for ($i = 1; $i <= 5; $i++) {
            \App\Models\Animal::create([
                'id_animal' => 'P-30'.$i,
                'especie_id' => $porcino->id,
                'raza_id' => $landrace->id,
                'sexo' => $i % 2 == 0 ? 'M' : 'F',
                'fecha_nacimiento' => now()->subMonths(2),
                'padre_id' => $padre->id,
                'madre_id' => $madre->id,
                'estado' => 'activo',
                'peso_nacimiento' => 1.5 + ($i * 0.1)
            ]);
        }

        // 7. Animales con estados de Venta y Muerte
        \App\Models\Animal::create([
            'id_animal' => 'P-V01',
            'especie_id' => $porcino->id,
            'raza_id' => $duroc->id,
            'sexo' => 'F',
            'fecha_nacimiento' => '2023-01-10',
            'estado' => 'vendido',
            'notas' => 'Vendido por lote a Granja El Rosal'
        ]);

        \App\Models\Animal::create([
            'id_animal' => 'P-M01',
            'especie_id' => $porcino->id,
            'raza_id' => $landrace->id,
            'sexo' => 'M',
            'fecha_nacimiento' => '2024-02-05',
            'estado' => 'fallecido',
            'notas' => 'Fallecimiento por causas naturales'
        ]);

        \App\Models\Animal::create([
            'id_animal' => 'P-D01',
            'especie_id' => $porcino->id,
            'raza_id' => $duroc->id,
            'sexo' => 'F',
            'fecha_nacimiento' => '2022-11-20',
            'estado' => 'descarte',
            'notas' => 'Baja por baja productividad'
        ]);

        // 8. Ejemplo de Bovino vendido
        $holstein = \App\Models\Raza::create([
            'especie_id' => $bovino->id,
            'nombre' => 'Holstein',
            'descripcion' => 'Raza lechera por excelencia'
        ]);

        \App\Models\Animal::create([
            'id_animal' => 'B-V01',
            'especie_id' => $bovino->id,
            'raza_id' => $holstein->id,
            'sexo' => 'F',
            'fecha_nacimiento' => '2021-05-15',
            'estado' => 'vendido',
            'notas' => 'Vaca lechera vendida a productor local'
        ]);
    }
}
