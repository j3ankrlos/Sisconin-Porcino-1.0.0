<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Granja;
use App\Models\Sitio;
use App\Models\Empresa;

class SitioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresa = Empresa::first();
        
        // Crear la granja Porcina si no existe
        $granjaPorcina = Granja::firstOrCreate(
            ['nombre' => 'Porcina'],
            ['empresa_id' => $empresa->id]
        );

        // Crear los Sitios para la granja Porcina
        $sitios = ['Sitio I', 'Sitio II', 'Sitio III'];

        foreach ($sitios as $nombre) {
            Sitio::firstOrCreate([
                'granja_id' => $granjaPorcina->id,
                'nombre' => $nombre
            ]);
        }
    }
}
