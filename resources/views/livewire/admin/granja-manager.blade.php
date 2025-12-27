<div>
    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-map-marked-alt mr-2"></i> Listado de Sucursales (Granjas)</h3>
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

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Ubicación</th>
                        <th>Hectáreas</th>
                        <th>Gerente</th>
                        <th>Especies en Producción</th>
                        <th style="width: 150px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($granjas as $granja)
                        <tr>
                            <td>{{ $granja->nombre }}</td>
                            <td>
                                {{ $granja->direccion }} <br>
                                @if($granja->latitud && $granja->longitud)
                                    <small class="text-info"><i class="fas fa-location-arrow"></i> {{ $granja->latitud }}, {{ $granja->longitud }}</small>
                                @endif
                            </td>
                            <td>{{ $granja->tamano_hectareas }} ha</td>
                            <td>{{ $granja->gerente }}</td>
                            <td>
                                @foreach($granja->especies as $esp)
                                    <span class="badge badge-info">{{ $esp->nombre }}</span>
                                @endforeach
                            </td>
                            <td>
                                <button wire:click="edit({{ $granja->id }})" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $granja->id }})" class="btn btn-xs btn-danger" onclick="confirm('¿Está seguro?') || event.stopImmediatePropagation()"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay sucursales registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $granjas->links() }}
            </div>
        </div>
    </div>

    <!-- Modal para Crear/Editar -->
    @if($isOpen)
    <div class="modal show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title font-weight-bold">{{ $granja_id ? 'Editar Sucursal' : 'Nueva Sucursal' }}</h5>
                    <button type="button" wire:click="closeModal()" class="close text-white" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Nombre de la Granja <span class="text-danger">*</span></label>
                                <input type="text" wire:model="nombre" class="form-control" placeholder="Ej: Granja El Rosal">
                                @error('nombre') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Gerente Responsable</label>
                                <input type="text" wire:model="gerente" class="form-control" placeholder="Nombre completo">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Dirección</label>
                                <input type="text" wire:model="direccion" class="form-control" placeholder="Dirección completa">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Hectáreas</label>
                                <input type="number" step="0.01" wire:model="tamano_hectareas" class="form-control">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Latitud (GPS)</label>
                                <input type="text" wire:model="latitud" class="form-control" placeholder="Ej: 4.6097">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Longitud (GPS)</label>
                                <input type="text" wire:model="longitud" class="form-control" placeholder="Ej: -74.0817">
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label class="font-weight-bold">Especies en esta Sucursal:</label>
                            <div class="row">
                                @forelse($especies as $especialidad)
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="esp_{{ $especialidad->id }}" value="{{ $especialidad->id }}" wire:model="selected_species">
                                            <label for="esp_{{ $especialidad->id }}" class="custom-control-label font-weight-normal">{{ $especialidad->nombre }}</label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-muted"><i class="fas fa-exclamation-triangle"></i> No hay especies registradas. Por favor, agregue especies en el módulo de Configuración > Especies.</p>
                                    </div>
                                @endforelse
                            </div>
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
