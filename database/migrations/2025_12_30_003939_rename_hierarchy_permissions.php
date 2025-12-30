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
        $renames = [
            'ver sitio 1' => 'ver sucursal',
            'ver sitio 2' => 'ver unidad',
            'ver sitio 3' => 'ver area',
            'ver granjas' => 'ver sucursales',
            'crear granjas' => 'crear sucursales',
            'editar granjas' => 'editar sucursales',
            'eliminar granjas' => 'eliminar sucursales',
            'ver granjas naves' => 'ver naves',
        ];

        foreach ($renames as $old => $new) {
            \DB::table('permissions')->where('name', $old)->update(['name' => $new]);
        }

        // Add missing hierarchy permissions if needed
        $newPermissions = ['ver unidades', 'ver areas'];
        foreach ($newPermissions as $perm) {
            if (!\DB::table('permissions')->where('name', $perm)->exists()) {
                \DB::table('permissions')->insert([
                    'name' => $perm,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        $renames = [
            'ver sucursal' => 'ver sitio 1',
            'ver unidad' => 'ver sitio 2',
            'ver area' => 'ver sitio 3',
            'ver sucursales' => 'ver granjas',
            'crear sucursales' => 'crear granjas',
            'editar sucursales' => 'editar granjas',
            'eliminar sucursales' => 'eliminar granjas',
            'ver naves' => 'ver granjas naves',
        ];

        foreach ($renames as $old => $new) {
            \DB::table('permissions')->where('name', $old)->update(['name' => $new]);
        }
        
        \DB::table('permissions')->whereIn('name', ['ver unidades', 'ver areas'])->delete();
    }
};
