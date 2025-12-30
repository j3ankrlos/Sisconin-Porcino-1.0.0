<div>
    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-map-marked-alt mr-2"></i> Listado de Sucursales</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i> Nueva Sucursal
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-3">
                <input type="text" wire:model.live="search" class="form-control" placeholder="Buscar sucursal...">
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Unidades Relacionadas</th>
                        <th style="width: 150px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sucursales as $sucursal)
                        <tr>
                            <td>{{ $sucursal->id }}</td>
                            <td>{{ $sucursal->nombre }}</td>
                            <td>{{ $sucursal->direccion }}</td>
                            <td>
                                @foreach($sucursal->unidades as $unidad)
                                    <span class="badge badge-info">{{ $unidad->nombre }}</span>
                                @endforeach
                                @if($sucursal->unidades->isEmpty())
                                    <span class="text-muted small">Sin unidades</span>
                                @endif
                            </td>
                            <td>
                                <button wire:click="edit({{ $sucursal->id }})" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $sucursal->id }})" class="btn btn-xs btn-danger" onclick="confirm('¿Está seguro? Se eliminarán también las unidades y áreas asociadas.') || event.stopImmediatePropagation()"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No hay sucursales registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $sucursales->links() }}
            </div>
        </div>
    </div>

    <!-- Modal para Crear/Editar -->
    @if($isOpen)
    <div class="modal show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title font-weight-bold">{{ $sucursal_id ? 'Editar Sucursal' : 'Nueva Sucursal' }}</h5>
                    <button type="button" wire:click="closeModal()" class="close text-white" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre de la Sucursal <span class="text-danger">*</span></label>
                            <input type="text" wire:model="nombre" class="form-control" placeholder="Ej: Sucursal Torcer">
                            @error('nombre') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Dirección</label>
                            <input type="text" wire:model="direccion" class="form-control" placeholder="Ubicación física">
                            @error('direccion') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal()" class="btn btn-secondary">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i> Guardar Sucursal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
