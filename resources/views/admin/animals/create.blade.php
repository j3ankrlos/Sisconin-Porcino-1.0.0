@extends('layouts.admin')

@section('title', 'Registro de Animal')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <a href="{{ route('admin.animals.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Volver a la Lista
            </a>
        </div>
        
        @livewire('admin.animal-create')
    </div>
</div>
@endsection
