<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Especie;
use App\Models\Raza;
use App\Models\Animal;
use Illuminate\Support\Facades\Schema;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear/Obtener Especie Porcina
        $porcino = Especie::firstOrCreate([
            'nombre' => 'Porcino'
        ], [
            'descripcion' => 'Producción de carne de cerdo'
        ]);

        // 2. Definir las únicas razas permitidas
        $razasPorcinas = [
            'F1' => 'Cruzamiento de primera generación',
            'F2' => 'Cruzamiento de segunda generación',
            'YORKSHIRE' => 'Raza Yorkshire (Large White)',
            'YORK-T' => 'Yorkshire - Línea Terminal',
            'DUROC' => 'Raza Duroc estándar',
            'DUROC-T' => 'Duroc - Línea Terminal',
            'LANDRACE' => 'Raza Landrace estándar',
            'LAND-T' => 'Landrace - Línea Terminal',
        ];

        // 3. Limpiar con seguridad
        Schema::disableForeignKeyConstraints();
        
        // Limpiamos animales existentes para evitar conflictos de integridad con razas que ya no existen
        Animal::truncate(); 
        
        // Limpiamos las razas de la especie porcina
        Raza::where('especie_id', $porcino->id)->delete();
        
        Schema::enableForeignKeyConstraints();

        // 4. Insertar las nuevas razas
        foreach ($razasPorcinas as $nombre => $descripcion) {
            Raza::create([
                'especie_id' => $porcino->id,
                'nombre' => $nombre,
                'descripcion' => $descripcion
            ]);
        }
    }
}
