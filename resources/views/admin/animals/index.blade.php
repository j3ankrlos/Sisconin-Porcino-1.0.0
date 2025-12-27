@extends('layouts.admin')

@section('title', 'Lista de Activos')

@section('content')
<div class="row">
    <div class="col-12">
        @livewire('admin.animal-list')
    </div>
</div>
@endsection
