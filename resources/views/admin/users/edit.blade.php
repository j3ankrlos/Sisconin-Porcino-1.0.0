@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Editar Usuario</h3>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        @error('name')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group">
        <label for="email">Correo Electrónico</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        @error('email')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group">
        <label for="role">Rol</label>
        <select class="form-control" id="role" name="role" required>
          <option value="">Seleccionar rol</option>
          @foreach($roles as $role)
            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
          @endforeach
        </select>
        @error('role')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
      <button type="submit" class="btn btn-primary">Actualizar</button>
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</div>
@endsection