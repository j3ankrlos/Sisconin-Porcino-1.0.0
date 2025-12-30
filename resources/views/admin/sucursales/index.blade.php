@extends('layouts.admin')

@section('title', 'Gestión de Sucursales (Granjas)')

@section('content')
<div class="row">
    <div class="col-12">
        @livewire('admin.sucursal-manager')
    </div>
</div>
@endsection
