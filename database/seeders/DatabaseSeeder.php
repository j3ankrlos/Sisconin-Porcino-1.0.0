<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Ejecutar seeders de configuración básica primero
        $this->call([
            RolePermissionSeeder::class,
            EmpresaSeeder::class,
            UbicacionesSeeder::class,
            CompanyHierarchySeeder::class,
            AnimalSeeder::class,
        ]);

        // Obtener la empresa creada
        $empresa = \App\Models\Empresa::first();

        // Crear Sucursal y Unidad 'Todas' para el SuperUsuario
        $sucursalTodas = null;
        $unidadTodas = null;

        if ($empresa) {
            $sucursalTodas = \App\Models\Sucursal::firstOrCreate(
                ['nombre' => 'Todas'],
                ['empresa_id' => $empresa->id]
            );

            $unidadTodas = \App\Models\Unidad::firstOrCreate(
                ['nombre' => 'Todas'],
                ['sucursal_id' => $sucursalTodas->id]
            );
        }

        // 2. Crear Usuario Administrador Principal (JEAN)
        $admin = User::firstOrCreate(
            ['email' => 'jeankrlos687@gmail.com'],
            [
                'name' => 'Jean',
                'password' => bcrypt('123'),
                'sucursal_id' => $sucursalTodas?->id,
                'unidad_id' => $unidadTodas?->id,
            ]
        );

        // Actualizar datos si el usuario ya existía (para asegurar que tenga sucursal/unidad 'Todas')
        if ($sucursalTodas && $unidadTodas) {
            $admin->update([
                'sucursal_id' => $sucursalTodas->id,
                'unidad_id' => $unidadTodas->id
            ]);
        }

        // Asegurar que tenga el rol SuperUsuario
        if (!$admin->hasRole('SuperUsuario')) {
            $admin->assignRole('SuperUsuario');
        }

        // Crear un usuario de prueba (Opcional)
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Usuario Prueba',
                'password' => bcrypt('password'),
            ]
        );

        if (!$user->hasRole('SitioIUsuario')) { // Asignando un rol válido del seeder
            $user->assignRole('SitioIUsuario');
        }
    }
}
