<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Definir todos los permisos
        $permissions = [
            'ver usuarios', 'crear usuarios', 'editar usuarios', 'eliminar usuarios',
            'ver maternidad', 'ver reproduccion', 'ver reemplazo',
            'ver movimientos', 'ver reportes', 'ver crear activos',
            'ver granjas', 'ver naves', 'ver secciones' // Permisos extra por si acaso
        ];

        // 2. Crear permisos
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 3. Crear roles (Admin y User)
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);

        // 4. Asignar todos los permisos al Admin
        $adminRole->syncPermissions($permissions);

        // 5. Asignar permisos básicos al User
        $userRole->syncPermissions([
            'ver movimientos',
            'ver reportes',
            'ver crear activos'
        ]);
    }
}