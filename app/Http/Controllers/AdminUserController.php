<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function create()
    {
        $roles = Role::with('permissions')->get();
        $permissions = \Spatie\Permission\Models\Permission::all();
        $sucursales = \App\Models\Sucursal::all();
        $unidades = \App\Models\Unidad::all();
        return view('admin.users.create', compact('roles', 'permissions', 'sucursales', 'unidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
            'sucursal_id' => 'nullable|exists:sucursales,id',
            'unidad_id' => 'nullable|exists:unidades,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'sucursal_id' => $request->sucursal_id,
            'unidad_id' => $request->unidad_id,
        ]);

        $user->assignRole($request->role);
        
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        // Log de Auditoría
        \App\Models\PermissionLog::create([
            'causer_id' => auth()->id() ?? $user->id, // Fallback si es autoregistro
            'user_id' => $user->id,
            'action' => 'create',
            'permissions_after' => $user->getPermissionNames()
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::with('permissions')->get();
        $permissions = \Spatie\Permission\Models\Permission::all();
        $sucursales = \App\Models\Sucursal::all();
        $unidades = \App\Models\Unidad::all();
        $userPermissions = $user->getPermissionNames()->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'permissions', 'sucursales', 'unidades', 'userPermissions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|exists:roles,name',
            'sucursal_id' => 'nullable|exists:sucursales,id',
            'unidad_id' => 'nullable|exists:unidades,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $user->update($request->only(['name', 'email', 'sucursal_id', 'unidad_id']));

        $user->syncRoles($request->role);
        
        // Sincronizar permisos directos
        $user->syncPermissions($request->permissions ?? []);

        // Log de Auditoría
        \App\Models\PermissionLog::create([
            'causer_id' => auth()->id(),
            'user_id' => $user->id,
            'action' => 'update',
            'permissions_after' => $user->getPermissionNames()
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}