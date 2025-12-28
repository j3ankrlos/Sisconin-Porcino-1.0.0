<div>
    <div class="row">
        <!-- Panel de Registro -->
        <div class="col-md-5">
            <div class="card card-premium shadow-lg border-0 overflow-hidden" style="border-radius: 15px;">
                <div class="card-header bg-gradient-primary p-4">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-exchange-alt mr-2"></i> Registrar Movimiento</h3>
                </div>
                <div class="card-body p-4">
                    <div class="form-group mb-4">
                        <label class="text-uppercase text-xs font-weight-bold text-muted">ID del Animal (Arete/Tatuaje)</label>
                        <div class="input-group input-group-lg shadow-sm">
                            <input type="text" wire:model.live.debounce.500ms="searchId" class="form-control border-0 bg-light" placeholder="Ej: 92762" style="border-radius: 10px 0 0 10px;">
                            <div class="input-group-append">
                                <span class="input-group-text border-0 bg-white" style="border-radius: 0 10px 10px 0;">
                                    <i class="fas fa-search text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($animal)
                        <div class="animal-info-badge mb-4 p-3 bg-light" style="border-radius: 12px; border-left: 5px solid #007bff;">
                            <div class="row">
                                <div class="col-6">
                                    <span class="d-block text-xs text-muted">ESPECIE / RAZA</span>
                                    <span class="font-weight-bold">{{ $animal->especie->nombre }} - {{ $animal->raza->nombre }}</span>
                                </div>
                                <div class="col-6">
                                    <span class="d-block text-xs text-muted">ESTADO</span>
                                    <span class="badge badge-success">{{ strtoupper($animal->estado) }}</span>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <form wire:submit.prevent="saveMovement">
                            <!-- Fila de Fechas -->
                            <div class="row align-items-end mb-4">
                                <div class="col-md-5">
                                    <div class="form-group mb-0">
                                        <label class="text-sm font-weight-bold">Fecha de Movimiento</label>
                                        <input type="date" wire:model.live="fecha" class="form-control border-light shadow-sm">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <label class="text-xs font-weight-bold">Vuelta</label>
                                        <input type="text" wire:model.live="vuelta" class="form-control border-light shadow-sm bg-light" placeholder="Ej: 19">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="text-xs font-weight-bold">Fecha PIC</label>
                                        <input type="text" wire:model.live="pic" class="form-control border-light shadow-sm bg-light" placeholder="Ej: 808">
                                    </div>
                                </div>
                            </div>

                            <div class="bg-light p-3 mb-4" style="border-radius: 12px; border: 1px dashed #ced4da;">
                                <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-3"><i class="fas fa-map-marker-alt mr-1"></i> Destino del Movimiento (SITIO I)</h6>
                                
                                <div class="row">
                                    <!-- Nave / Galpón Searchable -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-0 position-relative">
                                            <label class="text-xs font-weight-bold">Nave / Galpón</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" wire:model.live="search_nave" class="form-control border-light shadow-sm" placeholder="Buscar Nave...">
                                            </div>
                                            @if($search_nave && !$nave_id)
                                                <div class="list-group position-absolute w-100 shadow-lg" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                                    @forelse($naves as $n)
                                                        <button type="button" wire:click="selectNave({{ $n->id }}, '{{ $n->nombre }}')" class="list-group-item list-group-item-action py-2 text-sm text-left">
                                                            <strong>{{ $n->nombre }}</strong>
                                                        </button>
                                                    @empty
                                                        <div class="list-group-item text-xs text-muted">No hay resultados</div>
                                                    @endforelse
                                                </div>
                                            @endif
                                            @if($nave_id)
                                                <small class="text-success font-weight-bold d-block mt-1"><i class="fas fa-check-circle mr-1"></i>Seleccionado</small>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Sala / Fila Searchable -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-0 position-relative">
                                            <label class="text-xs font-weight-bold">Sala / Fila</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" wire:model.live="search_seccion" class="form-control border-light shadow-sm" placeholder="Buscar Sala..." {{ !$nave_id ? 'disabled' : '' }}>
                                            </div>
                                            @if($search_seccion && !$seccion_id && $nave_id)
                                                <div class="list-group position-absolute w-100 shadow-lg" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                                    @forelse($secciones as $s)
                                                        <button type="button" wire:click="selectSeccion({{ $s->id }}, '{{ $s->nombre }}')" class="list-group-item list-group-item-action py-2 text-sm text-left">
                                                            {{ $s->nombre }}
                                                        </button>
                                                    @empty
                                                        <div class="list-group-item text-xs text-muted">No hay resultados</div>
                                                    @endforelse
                                                </div>
                                            @endif
                                            @if($seccion_id)
                                                <small class="text-success font-weight-bold d-block mt-1"><i class="fas fa-check-circle mr-1"></i>Seleccionado</small>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Corral -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="text-xs font-weight-bold">Número de Corral</label>
                                            <input type="text" wire:model="corral" class="form-control border-light shadow-sm" placeholder="Ej: 15">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-sm font-weight-bold">Tipo de Movimiento</label>
                                <select wire:model="detalle" class="form-control border-light shadow-sm">
                                    <option value="">Seleccionar Tipo (Opcional)...</option>
                                    <option value="Ingreso a Recria">Ingreso a Recria</option>
                                    <option value="Ingreso a Maternidad">Ingreso a Maternidad</option>
                                    <option value="Descarte">Descarte</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg shadow mt-3" style="border-radius: 10px;">
                                <i class="fas fa-save mr-2"></i> Confirmar Movimiento
                            </button>
                        </form>

                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-piggy-bank fa-3x text-light mb-3"></i>
                            <p class="text-muted">Ingresa el ID de un animal para comenzar</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Panel de Historial -->
        <div class="col-md-7">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-white p-4 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold text-dark mb-0">
                        <i class="fas fa-history mr-2 text-info"></i> Hoja de Vida / Historial
                    </h3>
                    @if($animal)
                        <span class="badge badge-pill badge-outline-primary px-3 py-2 border">Animal ID: {{ $animal->id_animal }}</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th class="border-0 text-uppercase text-xs font-weight-bold p-4">Fecha</th>
                                    <th class="border-0 text-uppercase text-xs font-weight-bold p-4">Suceso</th>
                                    <th class="border-0 text-uppercase text-xs font-weight-bold p-4">Ubicación</th>
                                    <th class="border-0 text-uppercase text-xs font-weight-bold p-4">Detalle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($animal && $animal->eventos->count() > 0)
                                    @foreach($animal->eventos as $evento)
                                        <tr class="transition-all hover-bg-light">
                                            <td class="p-4 align-middle">
                                                <span class="d-block font-weight-bold">{{ $evento->fecha->format('d/m/Y') }}</span>
                                                <small class="text-muted">{{ $evento->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td class="p-4 align-middle">
                                                @php
                                                    $badgeClass = match($evento->tipo) {
                                                        'movimiento' => 'badge-primary',
                                                        'parto' => 'badge-success',
                                                        'reproduccion' => 'badge-info',
                                                        'salud' => 'badge-danger',
                                                        'registro' => 'badge-dark',
                                                        'venta' => 'badge-warning',
                                                        'muerte' => 'badge-danger',
                                                        'descarte' => 'badge-secondary',
                                                        default => 'badge-secondary'
                                                    };
                                                    $icon = match($evento->tipo) {
                                                        'movimiento' => 'fa-truck-loading',
                                                        'parto' => 'fa-baby',
                                                        'reproduccion' => 'fa-venus-mars',
                                                        'salud' => 'fa-heartbeat',
                                                        'registro' => 'fa-id-card',
                                                        'venta' => 'fa-shopping-cart',
                                                        'muerte' => 'fa-skull',
                                                        'descarte' => 'fa-trash-alt',
                                                        default => 'fa-info-circle'
                                                    };
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-circle shadow-sm bg-light mr-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; border-radius: 50%;">
                                                        <i class="fas {{ $icon }} text-muted"></i>
                                                    </div>
                                                    <div>
                                                        <span class="font-weight-bold d-block">{{ $evento->suceso }}</span>
                                                        <span class="badge {{ $badgeClass }} badge-pill px-2" style="font-size: 0.6rem;">{{ strtoupper($evento->tipo) }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-4 align-middle">
                                                @if($evento->seccion)
                                                    <span class="font-weight-bold d-block text-uppercase">{{ $evento->seccion->nave->nombre }} - {{ $evento->seccion->nombre }} -</span>
                                                    @if($evento->corral)
                                                        <span class="text-muted">{{ $evento->corral }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td class="p-4 align-middle">
                                                <p class="text-sm text-muted mb-0" style="max-width: 250px;">{{ $evento->detalle ?? 'Sin detalles adicionales' }}</p>
                                                <small class="text-xs text-info d-block mt-1">
                                                    <i class="fas fa-user-edit mr-1"></i> {{ $evento->user->name }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="fas fa-clipboard-list fa-3x text-light mb-3"></i>
                                                <p class="text-muted">No hay registros históricos para este animal.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card-premium {
            background: #fff;
            transition: transform 0.3s ease;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        .transition-all {
            transition: all 0.2s ease;
        }
        .hover-bg-light:hover {
            background-color: #f8f9fa;
        }
        .input-group-lg .form-control {
            font-size: 1.25rem;
        }
        .text-xs { font-size: 0.7rem; }
        .badge-pill { border-radius: 10rem; }
        
        /* Custom scrollbar */
        .table-responsive::-webkit-scrollbar {
            width: 6px;
        }
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }
        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #999;
        }
    </style>
</div>
