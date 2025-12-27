<div>
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-paw mr-2"></i> Gestión de Especies</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-sm btn-info">
                    <i class="fas fa-plus"></i> Nueva Especie
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
            @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-3">
                <input wire:model.live="search" type="text" class="form-control w-25" placeholder="Buscar especie...">
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th style="width: 150px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($especies as $especie)
                        <tr>
                            <td>{{ $especie->id }}</td>
                            <td><strong>{{ $especie->nombre }}</strong></td>
                            <td>{{ $especie->descripcion }}</td>
                            <td>
                                <button wire:click="edit({{ $especie->id }})" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $especie->id }})" class="btn btn-xs btn-danger" onclick="confirm('¿Está seguro? Esta acción no se puede deshacer si no hay registros asociados.') || event.stopImmediatePropagation()"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay especies registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $especies->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($isOpen)
    <div class="modal show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title font-weight-bold">{{ $especie_id ? 'Editar Especie' : 'Nueva Especie' }}</h5>
                    <button type="button" wire:click="closeModal()" class="close text-white" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre de la Especie <span class="text-danger">*</span></label>
                            <input type="text" wire:model="nombre" class="form-control" placeholder="Ej: Porcino, Bovino, Caprino">
                            @error('nombre') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Descripción (Opcional)</label>
                            <textarea wire:model="descripcion" class="form-control" rows="3" placeholder="Breve descripción de la especie"></textarea>
                            @error('descripcion') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal()" class="btn btn-secondary">Cancelar</button>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-save mr-1"></i> Guardar Especie
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
