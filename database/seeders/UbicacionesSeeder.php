<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Unidad;
use App\Models\Nave;
use App\Models\Seccion;
use App\Models\Empresa;

class UbicacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresa = Empresa::first();
        if (!$empresa) {
            $this->command->error('No se encontró ninguna empresa. Por favor ejecuta EmpresaSeeder primero.');
            return;
        }

        // Estructura: [Unidad (Sitio), Area (Granja), Nave, Seccion]
        $data = [
            // SITIO I - EST (Breeding/Gestation)
            ['Sitio I', 'EST', 'G1', 'A'], ['Sitio I', 'EST', 'G1', 'B'], ['Sitio I', 'EST', 'G1', 'C'], ['Sitio I', 'EST', 'G1', 'D'], ['Sitio I', 'EST', 'G1', 'E'], ['Sitio I', 'EST', 'G1', 'F'],
            ['Sitio I', 'EST', 'G2', 'A'], ['Sitio I', 'EST', 'G2', 'B'], ['Sitio I', 'EST', 'G2', 'C'], ['Sitio I', 'EST', 'G2', 'D'], ['Sitio I', 'EST', 'G2', 'E'], ['Sitio I', 'EST', 'G2', 'F'],
            ['Sitio I', 'EST', 'G3', 'A'], ['Sitio I', 'EST', 'G3', 'B'], ['Sitio I', 'EST', 'G3', 'C'], ['Sitio I', 'EST', 'G3', 'D'], ['Sitio I', 'EST', 'G3', 'E'], ['Sitio I', 'EST', 'G3', 'F'],
            ['Sitio I', 'EST', 'G4', 'A'], ['Sitio I', 'EST', 'G4', 'B'], ['Sitio I', 'EST', 'G4', 'C'], ['Sitio I', 'EST', 'G4', 'D'], ['Sitio I', 'EST', 'G4', 'E'], ['Sitio I', 'EST', 'G4', 'F'],
            ['Sitio I', 'EST', 'CUARENTENA', 'A'], ['Sitio I', 'EST', 'CUARENTENA', 'B'], ['Sitio I', 'EST', 'CUARENTENA', 'C'], ['Sitio I', 'EST', 'CUARENTENA', 'D'],
            ['Sitio I', 'EST', 'LA', 'C'], ['Sitio I', 'EST', 'LB', 'C'], ['Sitio I', 'EST', 'MP1', 'C'], ['Sitio I', 'EST', 'PUB1', 'C'],
            ['Sitio I', 'EST', 'M1', 'S1'], ['Sitio I', 'EST', 'M1', 'S2'], ['Sitio I', 'EST', 'M2', 'S9'], ['Sitio I', 'EST', 'M3', 'S18'],

            // SITIO I - EXP
            ['Sitio I', 'EXP', 'G6', 'A'], ['Sitio I', 'EXP', 'G6', 'B'], ['Sitio I', 'EXP', 'G6', 'C'], ['Sitio I', 'EXP', 'G6', 'D'], 
            ['Sitio I', 'EXP', 'G7', 'A'], ['Sitio I', 'EXP', 'G7', 'B'], ['Sitio I', 'EXP', 'G7', 'C'], ['Sitio I', 'EXP', 'G7', 'D'],
            ['Sitio I', 'EXP', 'LE', 'C'], ['Sitio I', 'EXP', 'MP2', 'C'], ['Sitio I', 'EXP', 'PUB2', 'C'],
            ['Sitio I', 'EXP', 'M4', 'S25'], ['Sitio I', 'EXP', 'M5', 'S34'],

            // SITIO II - (Preceba/Nursery) - Sin división de granja (usamos 'General' o nombre del sitio)
            ['Sitio II', 'Preceba', 'RECRIA 1', 'A'], ['Sitio II', 'Preceba', 'RECRIA 1', 'B'],
            ['Sitio II', 'Preceba', 'RECRIA 2', 'A'], ['Sitio II', 'Preceba', 'RECRIA 2', 'B'],
            ['Sitio II', 'Preceba', 'RECRIA 3', 'A'], ['Sitio II', 'Preceba', 'RECRIA 3', 'B'],

            // SITIO III - (Ceba/Finishing)
            ['Sitio III', 'Ceba', 'CEBA 1', 'A'], ['Sitio III', 'Ceba', 'CEBA 2', 'B'],
            ['Sitio III', 'Ceba', 'CEBA 3', 'C'], ['Sitio III', 'Ceba', 'CEBA 4', 'D'],
        ];

        foreach ($data as $item) {
            // Check if Unidad exists
            $unidad = Unidad::firstOrCreate([
                'nombre' => $item[0]
            ]);

            // Create Area related to Unidad
            $area = Area::firstOrCreate([
                'nombre' => $item[1],
                'unidad_id' => $unidad->id
            ], [
                // 'etapa' was removed or is not in Area model? Assuming we can store it or it's implied by Area Name
                // But Area table has empresa_id constraint?
                'empresa_id' => $empresa->id
            ]);

            $nave = Nave::firstOrCreate([
                'area_id' => $area->id, // Changed from granja_id
                'nombre' => $item[2]
            ]);

            Seccion::firstOrCreate([
                'nave_id' => $nave->id,
                'nombre' => $item[3]
            ]);
        }
    }
}
