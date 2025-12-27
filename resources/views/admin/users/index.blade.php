@extends('layouts.admin')

@section('title', 'Lista de Usuarios')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Lista de Usuarios</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addUserModal">
        <i class="fas fa-plus"></i> Agregar Usuario
      </button>
    </div>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Rol</th>
          <th>Fecha de Registro</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr>
          <td>{{ $user->id }}</td>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->getRoleNames()->first() }}</td>
          <td>{{ $user->created_at->format('d/m/Y') }}</td>
          <td>
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm">Ver</a>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">Editar</a>
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
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
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Agregar Nuevo Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
            @error('password')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            @error('password_confirmation')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label for="role">Rol</label>
            <select class="form-control" id="role" name="role" required>
              <option value="">Seleccionar rol</option>
              @foreach(\Spatie\Permission\Models\Role::all() as $role)
                <option value="{{ $role->name }}">{{ $role->name }}</option>
              @endforeach
            </select>
            @error('role')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection