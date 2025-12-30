<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyHierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresa = \DB::table('empresas')->where('nombre', 'Brunal')->first();
        if (!$empresa) {
            $empresaId = \DB::table('empresas')->insertGetId([
                'nombre' => 'Brunal',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $empresaId = $empresa->id;
        }

        // Create Sucursal Torcer
        $sucursalId = \DB::table('sucursales')->where('nombre', 'Torcer')->value('id');
        if (!$sucursalId) {
            $sucursalId = \DB::table('sucursales')->insertGetId([
                'empresa_id' => $empresaId,
                'nombre' => 'Torcer',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Link Unidades to Sucursal
        \DB::table('unidades')->where('nombre', 'like', 'Sitio %')->update(['sucursal_id' => $sucursalId]);

        // Get Unidades IDs
        $sitio1 = \DB::table('unidades')->where('nombre', 'Sitio I')->first();
        $sitio2 = \DB::table('unidades')->where('nombre', 'Sitio II')->first();
        $sitio3 = \DB::table('unidades')->where('nombre', 'Sitio III')->first();

        // Link Areas to Unidades
        if ($sitio1) {
            \DB::table('areas')->whereIn('nombre', ['EST', 'EXP'])->update(['unidad_id' => $sitio1->id]);
        }
        if ($sitio2) {
            \DB::table('areas')->where('nombre', 'Preceba')->update(['unidad_id' => $sitio2->id]);
        }
        if ($sitio3) {
            \DB::table('areas')->where('nombre', 'Ceba')->update(['unidad_id' => $sitio3->id]);
        }
    }
}
