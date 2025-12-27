@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
.small-box {
  transition: all 0.3s ease;
  cursor: pointer;
}
.small-box:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}
.inner h3 {
    font-size: 2.2rem;
    font-weight: 700;
}
</style>

<div class="row">
  <!-- Animales Totales -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-purple" style="background-color: #6f42c1 !important; color: #fff !important;">
      <div class="inner">
        <h3>{{ $animalCount }}</h3>
        <p>Total de Animales</p>
      </div>
      <div class="icon">
        <i class="fas fa-paw"></i>
      </div>
      <a href="{{ route('admin.animals.index') }}" class="small-box-footer">Ver Listado <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <!-- Sucursales / Granjas -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{ $granjaCount }}</h3>
        <p>Sucursales (Granjas)</p>
      </div>
      <div class="icon">
        <i class="fas fa-map-marked-alt"></i>
      </div>
      <a href="{{ route('admin.granjas.index') }}" class="small-box-footer">Ver Granjas <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <!-- Usuarios Registrados -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $userCount }}</h3>
        <p>Usuarios del Sistema</p>
      </div>
      <div class="icon">
        <i class="fas fa-users-cog"></i>
      </div>
      <a href="{{ route('admin.users.index') }}" class="small-box-footer">Gestionar <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <!-- Especies -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $especieCount }}</h3>
        <p>Especies Activas</p>
      </div>
      <div class="icon">
        <i class="fas fa-leaf"></i>
      </div>
      <a href="{{ route('admin.especies.index') }}" class="small-box-footer">Configurar <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
</div>

<div class="row">
    <!-- Razas -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-maroon" style="background-color: #d81b60 !important; color: #fff !important;">
          <div class="inner">
            <h3>{{ $razaCount }}</h3>
            <p>Variedad de Razas</p>
          </div>
          <div class="icon">
            <i class="fas fa-dna"></i>
          </div>
          <a href="#" class="small-box-footer">Próximamente <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title text-bold"><i class="fas fa-chart-line mr-2"></i> Estado General</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Bienvenido al panel central de <strong>SISCONINT</strong>. Aquí tienes un resumen del inventario ganadero y la estructura corporativa de tu empresa agropecuaria.</p>
            </div>
        </div>
    </div>
</div>
@endsection