@extends('layouts.admin')

@section('title', 'Editar Rol')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Editar Rol</h3>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label for="name">Nombre del Rol</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $role->name) }}" required>
        @error('name')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group">
        <label>Permisos</label>
        <div class="row">
          @foreach($permissions as $permission)
            <div class="col-md-4">
              <div class="icheck-primary">
                <input type="checkbox" id="permission_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                <label for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
              </div>
            </div>
          @endforeach
        </div>
        @error('permissions')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
      <button type="submit" class="btn btn-primary">Actualizar</button>
      <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</div>
@endsection