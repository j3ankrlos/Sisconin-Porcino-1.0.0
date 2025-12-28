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
            'ver mortalidad',
            'ver empresa', 'editar empresa',
            'ver granjas', 'crear granjas', 'editar granjas', 'eliminar granjas',
            'ver especies', 'crear especies', 'editar especies', 'eliminar especies',
            'ver razas', 'crear razas', 'editar razas', 'eliminar razas',
            'ver granjas naves', 'ver secciones',
            'ver roles',
            'ver sitio 1', 'ver sitio 2', 'ver sitio 3'
        ];

        // 2. Crear permisos
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 3. Crear roles
        $superAdmin = Role::firstOrCreate(['name' => 'SuperUsuario', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        
        $sitioIAdmin = Role::firstOrCreate(['name' => 'SitioIAdmin', 'guard_name' => 'web']);
        $sitioIUser = Role::firstOrCreate(['name' => 'SitioIUsuario', 'guard_name' => 'web']);
        
        $sitioIIAdmin = Role::firstOrCreate(['name' => 'SitioIIAdmin', 'guard_name' => 'web']);
        $sitioIIUser = Role::firstOrCreate(['name' => 'SitioIIUsuario', 'guard_name' => 'web']);
        
        $sitioIIIAdmin = Role::firstOrCreate(['name' => 'SitioIIIAdmin', 'guard_name' => 'web']);
        $sitioIIIUser = Role::firstOrCreate(['name' => 'SitioIIIUsuario', 'guard_name' => 'web']);

        // 4. Asignar todos los permisos al SuperUsuario y Admin
        $superAdmin->syncPermissions($permissions);
        $adminRole->syncPermissions($permissions);

        // 5. Asignar permisos por sitio
        $sitioIPerms = ['ver sitio 1', 'ver maternidad', 'ver reproduccion', 'ver reemplazo', 'ver movimientos', 'ver reportes', 'ver crear activos', 'ver mortalidad'];
        $sitioIAdmin->syncPermissions($sitioIPerms);
        $sitioIUser->syncPermissions(['ver sitio 1', 'ver movimientos', 'ver reportes']);

        $sitioIIPerms = ['ver sitio 2'];
        $sitioIIAdmin->syncPermissions($sitioIIPerms);
        $sitioIIUser->syncPermissions(['ver sitio 2']);

        $sitioIIIPerms = ['ver sitio 3'];
        $sitioIIIAdmin->syncPermissions($sitioIIIPerms);
        $sitioIIIUser->syncPermissions(['ver sitio 3']);
    }
}