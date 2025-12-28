@extends('layouts.admin')

@section('title', 'Lista de Usuarios')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Lista de Usuarios</h3>
    <div class="card-tools">
      <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Registrar Usuario
      </a>
    </div>
  </div>
  <div class="card-body">
    <livewire:admin.user-table />
  </div>
</div>

</div>

@endsection

@section('scripts')
<script>
    // Scripts adicionales para la tabla si fuera necesario
</script>
@endsection