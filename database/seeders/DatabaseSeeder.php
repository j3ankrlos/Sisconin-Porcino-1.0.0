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
            AnimalSeeder::class,
        ]);

        // 2. Crear Usuario Administrador Principal (JEAN)
        $admin = User::firstOrCreate(
            ['email' => 'jeankrlos687@gmail.com'],
            [
                'name' => 'Jean',
                'password' => bcrypt('123'),
            ]
        );

        // Asegurar que tenga el rol Admin
        if (!$admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        }

        // Crear un usuario de prueba (Opcional)
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Usuario Prueba',
                'password' => bcrypt('password'),
            ]
        );

        if (!$user->hasRole('User')) {
            $user->assignRole('User');
        }
    }
}
