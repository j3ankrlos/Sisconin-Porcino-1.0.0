<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Granja;
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

        $data = [
            ['EST', 'G1', 'A'], ['EST', 'G1', 'B'], ['EST', 'G1', 'C'], ['EST', 'G1', 'D'], ['EST', 'G1', 'E'], ['EST', 'G1', 'F'],
            ['EST', 'G2', 'A'], ['EST', 'G2', 'B'], ['EST', 'G2', 'C'], ['EST', 'G2', 'D'], ['EST', 'G2', 'E'], ['EST', 'G2', 'F'],
            ['EST', 'G3', 'A'], ['EST', 'G3', 'B'], ['EST', 'G3', 'C'], ['EST', 'G3', 'D'], ['EST', 'G3', 'E'], ['EST', 'G3', 'F'],
            ['EST', 'G4', 'A'], ['EST', 'G4', 'B'], ['EST', 'G4', 'C'], ['EST', 'G4', 'D'], ['EST', 'G4', 'E'], ['EST', 'G4', 'F'],
            ['EST', 'G5', 'A'], ['EST', 'G5', 'B'], ['EST', 'G5', 'C'], ['EST', 'G5', 'D'], ['EST', 'G5', 'E'], ['EST', 'G5', 'F'],
            ['EXP', 'G6', 'A'], ['EXP', 'G6', 'B'], ['EXP', 'G6', 'C'], ['EXP', 'G6', 'D'], ['EXP', 'G6', 'E'], ['EXP', 'G6', 'F'],
            ['EXP', 'G7', 'A'], ['EXP', 'G7', 'B'], ['EXP', 'G7', 'C'], ['EXP', 'G7', 'D'], ['EXP', 'G7', 'E'], ['EXP', 'G7', 'F'],
            ['EXP', 'G8', 'A'], ['EXP', 'G8', 'B'], ['EXP', 'G8', 'C'], ['EXP', 'G8', 'D'], ['EXP', 'G8', 'E'], ['EXP', 'G8', 'F'],
            ['EXP', 'G9', 'A'], ['EXP', 'G9', 'B'], ['EXP', 'G9', 'C'], ['EXP', 'G9', 'D'], ['EXP', 'G9', 'E'], ['EXP', 'G9', 'F'],
            ['EST', 'LA', 'C'], ['EST', 'LB', 'C'], ['EXP', 'LE', 'C'],
            ['EST', 'MP1', 'A'], ['EST', 'MP1', 'B'], ['EST', 'MP1', 'C'], ['EST', 'MP1', 'D'],
            ['EXP', 'MP2', 'A'], ['EXP', 'MP2', 'B'], ['EXP', 'MP2', 'C'], ['EXP', 'MP2', 'D'],
            ['EST', 'PUB1', 'C'], ['EXP', 'PUB2', 'C'], ['EXP', 'PUB2-D', 'C'], ['EXP', 'PUB3', 'C'],
            ['EXP', 'RECRIA', '1-A'], ['EXP', 'RECRIA', '1-B'], ['EXP', 'RECRIA', '2-A'], ['EXP', 'RECRIA', '2-B'],
            ['EXP', 'RECRIA', '3-A'], ['EXP', 'RECRIA', '3-B'], ['EXP', 'RECRIA', '4-A'], ['EXP', 'RECRIA', '4-B'],
            ['EST', 'M1', '1'], ['EST', 'M1', '2'], ['EST', 'M1', '3'], ['EST', 'M1', '4'], ['EST', 'M1', '5'], ['EST', 'M1', '6'], ['EST', 'M1', '7'], ['EST', 'M1', '8'],
            ['EST', 'M2', '9'], ['EST', 'M2', '10'], ['EST', 'M2', '11'], ['EST', 'M2', '12'], ['EST', 'M2', '13'], ['EST', 'M2', '14'], ['EST', 'M2', '15'], ['EST', 'M2', '16'], ['EST', 'M2', '17'],
            ['EST', 'M3', '18'], ['EST', 'M3', '19'], ['EST', 'M3', '20'], ['EST', 'M3', '21'], ['EST', 'M3', '22'], ['EST', 'M3', '23'], ['EST', 'M3', '24'],
            ['EXP', 'M4', '25'], ['EXP', 'M4', '26'], ['EXP', 'M4', '27'], ['EXP', 'M4', '28'], ['EXP', 'M4', '29'], ['EXP', 'M4', '30'], ['EXP', 'M4', '31'], ['EXP', 'M4', '32'], ['EXP', 'M4', '33'],
            ['EXP', 'M5', '34'], ['EXP', 'M5', '35'], ['EXP', 'M5', '36'], ['EXP', 'M5', '37'], ['EXP', 'M5', '38'], ['EXP', 'M5', '39'], ['EXP', 'M5', '40'], ['EXP', 'M5', '41'],
        ];

        foreach ($data as $item) {
            $granja = Granja::firstOrCreate([
                'nombre' => $item[0],
                'empresa_id' => $empresa->id
            ]);

            $nave = Nave::firstOrCreate([
                'granja_id' => $granja->id,
                'nombre' => $item[1]
            ]);

            Seccion::firstOrCreate([
                'nave_id' => $nave->id,
                'nombre' => $item[2]
            ]);
        }
    }
}
