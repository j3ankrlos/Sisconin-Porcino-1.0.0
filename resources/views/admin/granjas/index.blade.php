@extends('layouts.admin')

@section('title', 'Gestión de Sucursales (Granjas)')

@section('content')
<div class="row">
    <div class="col-12">
        @livewire('admin.granja-manager')
    </div>
</div>
@endsection
