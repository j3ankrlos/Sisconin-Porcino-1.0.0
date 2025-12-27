<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Especializacion;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear Empresa Principal si no existe
        Empresa::firstOrCreate(
            ['nit' => '900123456-1'],
            [
                'nombre' => 'SISCONINT AGROPECUARIA S.A.S',
                'direccion' => 'Sede Central - Calle 123 #45-67',
                'telefono' => '+57 300 000 0000',
                'email' => 'admin@sisconint.com',
                'fecha_fundacion' => '2010-01-01'
            ]
        );

        // 2. Crear Especializaciones predefinidas
        $especialidades = [
            'Ganadería lechera (producción de leche)',
            'Ganadería de carne (producción de carne)',
            'Porcinos (cerdos)',
            'Caprinos (cabras)',
            'Ovinos (ovejas)',
            'Avicultura (aves)',
            'Acuicultura (peces)'
        ];

        foreach ($especialidades as $nombre) {
            Especializacion::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
