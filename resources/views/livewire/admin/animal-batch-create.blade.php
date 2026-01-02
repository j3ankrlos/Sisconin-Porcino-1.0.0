<div class="batch-container px-2">
    <div class="row">
        <!-- SECCIÓN DE PARÁMETROS -->
        <div class="col-lg-4">
            <div class="card card-outline card-primary shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-sliders-h mr-2 text-primary"></i>Configuración de Lote
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Fecha de Nacimiento / PIC Sincronizada -->
                        <div class="col-md-12 mb-3">
                            <div class="p-2 border rounded bg-light shadow-sm">
                                <label class="text-xs text-primary font-weight-bold uppercase mb-2 d-block">
                                    <i class="fas fa-calendar-day mr-1"></i>Fecha de Nacimiento del Lote
                                </label>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-6 pr-1">
                                        <input type="date" wire:model.live="fecha_nacimiento" class="form-control form-control-sm border-primary">
                                    </div>
                                    <div class="col-3 px-1">
                                        <input type="text" wire:model.live="vuelta" class="form-control form-control-sm text-center" placeholder="Vta">
                                    </div>
                                    <div class="col-3 pl-1">
                                        <input type="text" wire:model.live="pic" class="form-control form-control-sm text-center" placeholder="PIC">
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <span class="badge badge-info text-xs">PIC: {{ \App\Helpers\PicDateHelper::format($fecha_nacimiento) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Lote y Genética -->
                        <div class="col-md-6 form-group">
                            <label class="text-xs text-muted font-weight-bold uppercase mb-1">Cód. Lote</label>
                            <input type="text" wire:model="lote" class="form-control form-control-sm text-center font-weight-bold" placeholder="Ej: 548">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="text-xs text-muted font-weight-bold uppercase mb-1">Genética</label>
                            <select wire:model.live="raza_id" class="form-control form-control-sm">
                                <option value="">Seleccione...</option>
                                @foreach($razas as $raza)
                                    <option value="{{ $raza->id }}">{{ $raza->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- ID Inicial (Nuevo campo solicitado) -->
                        <div class="col-md-12 form-group">
                            <label class="text-xs text-primary font-weight-bold uppercase mb-1">
                                <i class="fas fa-fingerprint mr-1"></i>ID Inicial del Correlativo
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="number" wire:model="id_inicio" 
                                       class="form-control font-weight-bold border-primary shadow-sm {{ !$puedo_editar_id ? 'bg-light' : '' }}" 
                                       {{ !$puedo_editar_id ? 'readonly' : '' }}
                                       placeholder="{{ $ultimo_id_sistema > 0 ? 'Siguiente sugerido: '.($ultimo_id_sistema+1) : 'Ej: 98764' }}">
                                <div class="input-group-append">
                                    <button class="btn {{ $puedo_editar_id ? 'btn-primary' : 'btn-outline-primary' }} shadow-sm" 
                                            type="button" 
                                            wire:click="toggleEditarId"
                                            title="{{ $puedo_editar_id ? 'Bloquear campo' : 'Habilitar edición manual' }}">
                                        <i class="fas {{ $puedo_editar_id ? 'fa-lock-open' : 'fa-pencil-alt' }}"></i>
                                    </button>
                                </div>
                            </div>
                            @if($ultimo_id_sistema == 0 && !$puedo_editar_id)
                                <small class="text-info mt-1 d-block"><i class="fas fa-info-circle mr-1"></i>Sistema sin historial. Pulse el lápiz para ingresar el número inicial.</small>
                            @endif
                        </div>

                        <!-- Origen y Sexo -->
                        <div class="col-md-6 form-group">
                            <label class="text-xs text-muted font-weight-bold uppercase mb-1">Origen</label>
                            <input type="text" wire:model="origen" class="form-control form-control-sm uppercase" placeholder="Ej: LA">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="text-xs text-muted font-weight-bold uppercase mb-1">Sexo</label>
                            <select wire:model="sexo" class="form-control form-control-sm">
                                <option value="F">Hembra</option>
                                <option value="M">Macho</option>
                            </select>
                        </div>

                        <hr class="col-12 my-2">

                        <!-- Destino Searchable -->
                        <div class="col-md-12">
                            <label class="text-xs text-primary font-weight-bold uppercase mb-2">Ubicación de Destino</label>
                        </div>
                        
                        <!-- Nave Searchable -->
                        <div class="col-md-12 form-group position-relative">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                </div>
                                <input type="text" wire:model.live="search_nave" class="form-control" placeholder="Buscar Nave...">
                            </div>
                            @if($search_nave && !$nave_id)
                                <div class="list-group position-absolute w-100 shadow-lg" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                    @forelse($naves as $n)
                                        <button type="button" wire:click="selectNave({{ $n->id }}, '{{ $n->nombre }}')" class="list-group-item list-group-item-action py-2 text-sm text-left">
                                            <i class="fas fa-warehouse mr-2 text-primary"></i><strong>{{ $n->nombre }}</strong> <span class="text-xs text-muted ml-1">({{ $n->granja->nombre }})</span>
                                        </button>
                                    @empty
                                        <div class="list-group-item text-xs text-muted italic">No se encontraron naves...</div>
                                    @endforelse
                                </div>
                            @endif
                            @if($nave_id)
                                <div class="mt-1 d-flex justify-content-between align-items-center bg-light p-1 rounded border">
                                    <small class="text-success font-weight-bold ml-2"><i class="fas fa-check-circle mr-1"></i>Seleccionado</small>
                                    <button wire:click="$set('nave_id', null); $set('search_nave', '')" class="btn btn-xs btn-link text-danger p-0 mr-2"><i class="fas fa-times"></i></button>
                                </div>
                            @endif
                        </div>

                        <!-- Sección Searchable -->
                        <div class="col-md-6 form-group position-relative">
                            <div class="input-group input-group-sm">
                                <input type="text" wire:model.live="search_seccion" class="form-control" placeholder="Sala / Fila..." {{ !$nave_id ? 'disabled' : '' }}>
                            </div>
                            @if($search_seccion && !$seccion_id && $nave_id)
                                <div class="list-group position-absolute w-100 shadow-lg" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                    @forelse($secciones as $s)
                                        <button type="button" wire:click="selectSeccion({{ $s->id }}, '{{ $s->nombre }}')" class="list-group-item list-group-item-action py-2 text-sm text-left">
                                            <i class="fas fa-door-open mr-2 text-secondary"></i>{{ $s->nombre }}
                                        </button>
                                    @empty
                                        <div class="list-group-item text-xs text-muted italic">Sin coincidencias...</div>
                                    @endforelse
                                </div>
                            @endif
                        </div>

                        <!-- Corral -->
                        <div class="col-md-6 form-group">
                            <input type="text" wire:model="corral" class="form-control form-control-sm" placeholder="Corral">
                        </div>

                        <hr class="col-12 my-2">

                        <!-- Peso y Cantidad -->
                        <div class="col-md-6 form-group">
                            <label class="text-xs text-muted font-weight-bold uppercase mb-1">Peso Prom. (Kg)</label>
                            <input type="number" step="0.01" wire:model="peso" class="form-control form-control-sm text-center font-weight-bold">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="text-xs text-muted font-weight-bold uppercase mb-1">Cantidad</label>
                            <input type="number" wire:model="cantidad" class="form-control form-control-sm text-center font-weight-bold text-primary">
                        </div>
                    </div>

                    <button wire:click="generarLista" class="btn btn-primary btn-block shadow-sm py-2 font-weight-bold mt-2">
                        <i class="fas fa-plus mr-2"></i>GENERAR LISTADO
                    </button>
                </div>
            </div>
        </div>

        <!-- SECCIÓN DE LISTADO (TABLA) -->
        <div class="col-lg-8">
            <!-- (Misma tabla del paso anterior con ligeros ajustes si es necesario) -->
            <div class="card shadow-sm border-0" style="border-radius: 12px; height: 100%; min-height: 600px;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-list-ol mr-2 text-primary"></i>Lista de Correlativos Generados
                    </h5>
                    @if(!empty($animales_lista))
                        <button wire:click="guardarLoteDefinitivo" class="btn btn-success px-4 font-weight-bold shadow-sm animate__animated animate__pulse animate__infinite">
                            <i class="fas fa-save mr-2"></i>GUARDAR REGISTROS DEFINITIVOS
                        </button>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 580px; overflow-y: auto;">
                        <table class="table table-hover table-striped mb-0 text-center">
                            <thead class="bg-light sticky-top">
                                <tr class="text-muted text-xs uppercase font-weight-bold">
                                    <th>#</th>
                                    <th>ID Animal</th>
                                    <th>Lote</th>
                                    <th>Genética</th>
                                    <th>Sexo</th>
                                    <th>Peso Est.</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm border-top-0">
                                @forelse($animales_lista as $index => $item)
                                    <tr class="{{ $item['existe'] ? 'table-danger' : '' }}">
                                        <td class="text-muted py-3">{{ $index + 1 }}</td>
                                        <td class="font-weight-bold text-lg {{ $item['existe'] ? '' : 'text-primary' }}">
                                            #{{ $item['id_animal'] }}
                                            @if($item['existe'])
                                                <small class="d-block text-danger font-weight-bold" style="font-size: 0.65rem;">¡YA EXISTE EN SISTEMA!</small>
                                            @endif
                                        </td>
                                        <td><span class="badge badge-light border font-weight-bold px-2">{{ $item['lote'] }}</span></td>
                                        <td>{{ $item['genetica'] }}</td>
                                        <td>{{ $item['sexo'] }}</td>
                                        <td>{{ $item['peso'] ?: '---' }} Kg</td>
                                        <td>
                                            <button wire:click="eliminarDeLista({{ $index }})" class="btn btn-link btn-sm text-danger shadow-none">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-5">
                                            <div class="empty-state py-5 text-center">
                                                <div class="mb-4">
                                                    <i class="fas fa-clipboard-list fa-5x text-light" style="opacity: 0.5;"></i>
                                                </div>
                                                <h6 class="text-muted font-italic">Configure los datos y haga clic en "Generar Listado" para visualizar los animales antes de guardar.</h6>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(!empty($animales_lista))
                <div class="card-footer bg-white text-right border-0 py-3">
                    <span class="mr-3 text-muted">Total a registrar: <strong class="text-primary h5">{{ count($animales_lista) }}</strong> activos</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .uppercase { text-transform: uppercase; }
        .batch-container { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .table thead th { border-top: none; }
        .table td { vertical-align: middle; }
        .card-outline.card-primary { border-top: 3px solid #007bff; }
        .list-group-item-action:hover { background-color: #f8f9fa; cursor: pointer; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #999; }
    </style>
</div>
