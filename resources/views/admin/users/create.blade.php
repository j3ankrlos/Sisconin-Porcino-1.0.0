@extends('layouts.admin')

@section('title', 'Registro de Usuario')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Registro de Usuario</h3>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.users.store') }}">
      @csrf
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
          @foreach($roles as $role)
            <option value="{{ $role->name }}">{{ $role->name }}</option>
          @endforeach
        </select>
        @error('role')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
      <button type="submit" class="btn btn-primary">Registrar</button>
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</div>
@endsection