<div>
    <div class="card card-outline card-maroon">
        <div class="card-header">
            <h3 class="card-title text-maroon"><i class="fas fa-hand-holding-heart mr-2"></i> Gestión de Razas</h3>
            <div class="card-tools">
                <button wire:click="create()" class="btn btn-sm bg-maroon">
                    <i class="fas fa-plus"></i> Nueva Raza
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

            <div class="row mb-3">
                <div class="col-md-3">
                    <input wire:model.live="search" type="text" class="form-control" placeholder="Buscar por raza...">
                </div>
                <div class="col-md-3">
                    <input wire:model.live="searchEspecie" type="text" class="form-control" placeholder="Filtrar por especie...">
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Especie</th>
                        <th>Raza</th>
                        <th>Descripción</th>
                        <th style="width: 150px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($razas as $raza)
                        <tr>
                            <td>{{ $raza->id }}</td>
                            <td><span class="badge badge-info">{{ $raza->especie->nombre }}</span></td>
                            <td><strong>{{ $raza->nombre }}</strong></td>
                            <td>{{ $raza->descripcion }}</td>
                            <td>
                                <button wire:click="edit({{ $raza->id }})" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $raza->id }})" class="btn btn-xs btn-danger" onclick="confirm('¿Está seguro? Esta acción no se puede deshacer si hay animales vinculados.') || event.stopImmediatePropagation()"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No se encontraron razas con los criterios de búsqueda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3 float-right">
                {{ $razas->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($isOpen)
    <div class="modal show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-maroon">
                <div class="modal-header bg-maroon">
                    <h5 class="modal-title font-weight-bold">{{ $raza_id ? 'Editar Raza' : 'Nueva Raza' }}</h5>
                    <button type="button" wire:click="closeModal()" class="close text-white" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Especie <span class="text-danger">*</span></label>
                            <select wire:model="especie_id" class="form-control">
                                <option value="">Seleccione una especie</option>
                                @foreach($especies as $especie)
                                    <option value="{{ $especie->id }}">{{ $especie->nombre }}</option>
                                @endforeach
                            </select>
                            @error('especie_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Nombre de la Raza <span class="text-danger">*</span></label>
                            <input type="text" wire:model="nombre" class="form-control" placeholder="Ej: Duroc, Holstein, Angus">
                            @error('nombre') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Descripción (Opcional)</label>
                            <textarea wire:model="descripcion" class="form-control" rows="3" placeholder="Características de la raza"></textarea>
                            @error('descripcion') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal()" class="btn btn-secondary">Cancelar</button>
                        <button type="submit" class="btn bg-maroon">
                            <i class="fas fa-save mr-1"></i> Guardar Raza
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
