@extends('layouts.admin')

@section('title', 'Movimientos e Historial')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Gestión de Movimientos</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.animals.index') }}">Animales</a></li>
                <li class="breadcrumb-item active">Movimientos</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <livewire:admin.animal-movement />
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('swal', event => {
        Swal.fire({
            title: event.detail[0].title,
            text: event.detail[0].text,
            icon: event.detail[0].icon,
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@endsection
