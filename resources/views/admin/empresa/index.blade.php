@extends('layouts.admin')

@section('title', 'Configuración de Empresa')

@section('content')
<div class="row">
    <div class="col-12">
        @livewire('admin.empresa-config')
    </div>
</div>
@endsection
