@extends('layouts.admin')

@section('title', 'Gestión de Especies')

@section('content')
<div class="row">
    <div class="col-12">
        @livewire('admin.especie-manager')
    </div>
</div>
@endsection
