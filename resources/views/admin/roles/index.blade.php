@extends('layouts.admin')

@section('title', 'Lista de Roles')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Lista de Roles</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addRoleModal">
        <i class="fas fa-plus"></i> Agregar Rol
      </button>
    </div>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Permisos</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($roles as $role)
        <tr>
          <td>{{ $role->id }}</td>
          <td>{{ $role->name }}</td>
          <td>{{ $role->permissions->pluck('name')->join(', ') }}</td>
          <td>
            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning btn-sm">Editar</a>
            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addRoleModalLabel">Agregar Nuevo Rol</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{ route('admin.roles.store') }}">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="name">Nombre del Rol</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label>Permisos</label>
            <div class="row">
              @foreach(\Spatie\Permission\Models\Permission::all() as $permission)
                <div class="col-md-6">
                  <div class="icheck-primary">
                    <input type="checkbox" id="permission_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}">
                    <label for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                  </div>
                </div>
              @endforeach
            </div>
            @error('permissions')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Crear</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection