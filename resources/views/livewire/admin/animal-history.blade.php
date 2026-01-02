<div class="card shadow-lg border-0" style="border-radius: 15px;">
    <div class="card-header p-0 overflow-hidden" style="background-color: #f8f9fa;">
        <!-- Info Bar (Top) - Matches the layout in the image -->
        @if($animal)
        <div class="px-4 py-2 text-white d-flex align-items-center flex-wrap" style="background-color: #6c757d; font-size: 0.9rem;">
            <span class="mr-4 font-weight-bold"><i class="fas fa-fingerprint mr-1"></i> Cerda: {{ $animal->id_animal }}</span>
            <span class="mr-4"><span class="text-white-50">Genética:</span> {{ $animal->raza->nombre ?? 'N/A' }}</span>
            <span class="mr-4"><span class="text-white-50">Lote:</span> {{ $animal->lote ?? 'N/A' }}</span>
            <span class="mr-4"><span class="text-white-50">Estado:</span> {{ ucfirst($animal->estado) }}</span>
        </div>
        @endif

        <!-- Tabs Navigation -->
        <div class="bg-white border-bottom px-4 pt-2">
            <nav class="nav nav-tabs border-0" style="margin-bottom: -1px;">
                <a class="nav-item nav-link {{ $activeTab === 'events' ? 'active font-weight-bold border-top-0 border-left-0 border-right-0 border-bottom-primary text-primary' : 'text-muted border-0 hover-text-dark' }}" 
                   href="#" wire:click.prevent="$set('activeTab', 'events')" 
                   style="{{ $activeTab === 'events' ? 'border-bottom: 2px solid #007bff !important;' : '' }}">
                   <i class="fas fa-notes-medical mr-1"></i> Historial Events
                </a>
                <a class="nav-item nav-link {{ $activeTab === 'audits' ? 'active font-weight-bold border-top-0 border-left-0 border-right-0 border-bottom-primary text-primary' : 'text-muted border-0 hover-text-dark' }}" 
                   href="#" wire:click.prevent="$set('activeTab', 'audits')"
                   style="{{ $activeTab === 'audits' ? 'border-bottom: 2px solid #007bff !important;' : '' }}">
                   <i class="fas fa-shield-alt mr-1"></i> Auditoría Sistema
                </a>
            </nav>
        </div>
    </div>
    <div class="card-body p-0">

        <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">
            @if ($activeTab === 'events')
            <table class="table table-hover mb-0 text-sm">
                <thead class="bg-light sticky-top text-uppercase text-muted">
                    <tr>
                        <th class="border-0 font-weight-bold p-3">Fecha</th>
                        <th class="border-0 font-weight-bold p-3">Suceso</th>
                        <th class="border-0 font-weight-bold p-3" style="width: 25%;">Detalle</th>
                        <th class="border-0 font-weight-bold p-3">Localización</th>
                        <th class="border-0 font-weight-bold p-3">Operario</th>
                        <th class="border-0 font-weight-bold p-3" style="width: 20%;">Último cambio</th>
                        <th class="border-0 font-weight-bold p-3 text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    @if($animal && $events->count() > 0)
                        @foreach($events as $evento)
                            <tr class="transition-all hover-bg-light border-bottom">
                                <!-- FECHA -->
                                <td class="p-3 align-middle text-dark">
                                    <span class="font-weight-bold d-block">{{ $evento->fecha->format('d/m/Y') }}</span>
                                    <span class="badge badge-light border text-xs text-muted">{{ \App\Helpers\PicDateHelper::format($evento->fecha) }}</span>
                                </td>

                                <!-- SUCESO -->
                                <td class="p-3 align-middle">
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
                                    @endphp
                                    <div>
                                        <span class="font-weight-bold d-block text-dark">{{ $evento->suceso }}</span>
                                        <!-- Optional: Hide badge if verified redundant, but keeping for clarity -->
                                        <!-- <span class="badge {{ $badgeClass }} badge-pill px-2 py-1 mt-1" style="font-size: 0.65rem;">{{ strtoupper($evento->tipo) }}</span> -->
                                    </div>
                                </td>

                                <!-- DETALLE -->
                                <td class="p-3 align-middle text-muted">
                                    {{ $evento->detalle ?? '' }}
                                </td>

                                <!-- LOCALIZACIÓN -->
                                <td class="p-3 align-middle">
                                    @if($evento->seccion)
                                        <span class="d-block text-dark font-weight-bold">{{ $evento->seccion->nave->nombre }}</span>
                                        @if($evento->corral)
                                            <small class="text-muted">{{ $evento->seccion->nombre }} - {{ $evento->corral }}</small>
                                        @else
                                            <small class="text-muted">{{ $evento->seccion->nombre }}</small>
                                        @endif
                                    @endif
                                </td>

                                <!-- OPERARIO (Futuro Módulo Montas) -->
                                <td class="p-3 align-middle">
                                    <!-- Espacio reservado para el operario de inseminación -->
                                </td>

                                <!-- ÚLTIMO CAMBIO -->
                                <td class="p-3 align-middle text-muted" style="font-size: 0.75rem;">
                                    Creado el {{ $evento->created_at->format('d/m/Y g:i a') }} por 
                                    <span class="font-weight-bold">{{ strtoupper($evento->user->name ?? 'SISTEMA') }}</span>
                                </td>

                                <!-- ACCIONES -->
                                <td class="p-3 align-middle text-right">
                                    <button wire:click="editEvent({{ $evento->id }})" class="btn btn-sm btn-link text-secondary p-0 mr-2" title="Editar">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button onclick="confirmDeleteEvent({{ $evento->id }})" type="button" class="btn btn-sm btn-link text-danger p-0" title="Eliminar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <span class="text-muted">No hay registros históricos.</span>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            @elseif ($activeTab === 'audits')
                <!-- Table for Audits (Simplified from previous step, adapted to style) -->
                <table class="table table-hover mb-0">
                    <thead class="bg-light sticky-top">
                        <tr>
                            <th class="border-0 text-uppercase text-xs font-weight-bold p-4">Fecha/Hora</th>
                            <th class="border-0 text-uppercase text-xs font-weight-bold p-4">Usuario</th>
                            <th class="border-0 text-uppercase text-xs font-weight-bold p-4">Acción</th>
                            <th class="border-0 text-uppercase text-xs font-weight-bold p-4">Cambios</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($audits as $audit)
                        <tr>
                            <td class="p-4 text-sm text-gray-500">
                                {{ $audit->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="p-4 text-sm text-gray-500">
                                {{ $audit->user->name ?? 'Sistema' }}
                                <div class="text-xs text-gray-400">{{ $audit->ip_address }}</div>
                            </td>
                            <td class="p-4 text-sm text-gray-500">
                                <span class="badge {{ $audit->event === 'updated' ? 'badge-warning' : ($audit->event === 'created' ? 'badge-success' : 'badge-danger') }}">
                                    {{ ucfirst($audit->event) }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-gray-500" style="max-width: 300px; white-space: normal;">
                                @if($audit->event === 'updated')
                                    <div class="small">
                                        @foreach(json_decode($audit->new_values, true) ?? [] as $key => $val)
                                            @php 
                                                $old = json_decode($audit->old_values, true)[$key] ?? 'N/A';
                                            @endphp
                                            <div>
                                                <strong>{{ $key }}:</strong> 
                                                <span class="text-danger flex-wrap" style="text-decoration: line-through">{{ is_array($old) ? json_encode($old) : $old }}</span> 
                                                &rarr; 
                                                <span class="text-success flex-wrap">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($audit->event === 'created')
                                    <span class="text-success">Registro Creado</span>
                                @else
                                    <span class="text-danger">Registro Eliminado</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">No hay registros de auditoría.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>


    <!-- Edit Modal -->
    <div wire:ignore.self class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                <div class="modal-header bg-gradient-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title font-weight-bold" id="editEventModalLabel">
                        <i class="fas fa-edit mr-2"></i> Editar Movimiento
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <!-- Fila de Fechas -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold text-muted text-xs text-uppercase">Vuelta</label>
                                <input type="text" 
                                    wire:model.live="editVuelta" 
                                    class="form-control shadow-sm border-light text-uppercase" 
                                    style="border-radius: 10px;" 
                                    placeholder="Ej: 19"
                                    maxlength="2">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold text-muted text-xs text-uppercase">Fecha PIC</label>
                                <input type="text" 
                                    wire:model.live="editPic" 
                                    class="form-control shadow-sm border-light text-uppercase" 
                                    style="border-radius: 10px;" 
                                    placeholder="Ej: 808"
                                    maxlength="3">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold text-muted text-xs text-uppercase">Fecha de Movimiento</label>
                                <input type="date" 
                                    wire:model.live="editFecha" 
                                    class="form-control shadow-sm border-light" 
                                    style="border-radius: 10px;">
                                @error('editFecha') <span class="text-danger text-xs font-weight-bold d-block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Destino del Movimiento -->
                    <div class="bg-light p-3 mb-3" style="border-radius: 12px; border: 1px dashed #ced4da;">
                        <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-3">
                            <i class="fas fa-map-marker-alt mr-1"></i> Destino del Movimiento (SITIO I)
                        </h6>
                        
                        <div class="row">
                            <!-- Nave / Galpón -->
                            <div class="col-md-4">
                                <div class="form-group mb-0 position-relative" x-data="{ open: false }" @click.outside="open = false">
                                    <label class="text-xs font-weight-bold">Nave / Galpón</label>
                                    <input type="text" 
                                        wire:model.live="editSearchNave" 
                                        @focus="open = true"
                                        @keydown.tab="open = false"
                                        @keydown.enter="open = false"
                                        class="form-control border-light shadow-sm text-uppercase" 
                                        placeholder="Buscar Nave...">
                                    @if($editSearchNave && !$editNaveId && count($editNaves) > 0)
                                        <div x-show="open" class="list-group position-absolute w-100 shadow-lg" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                            @foreach($editNaves as $n)
                                                @if(stripos($n->nombre, $editSearchNave) !== false)
                                                    <button type="button" wire:click="editSearchNave = '{{ $n->nombre }}'; editNaveId = {{ $n->id }}; loadEditSecciones(); open = false;" class="list-group-item list-group-item-action py-2 text-sm text-left">
                                                        <strong>{{ $n->nombre }}</strong>
                                                    </button>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($editNaveId)
                                        <small class="text-success font-weight-bold d-block mt-1"><i class="fas fa-check-circle mr-1"></i>Seleccionado</small>
                                    @endif
                                    @error('editNaveId') <span class="text-danger text-xs font-weight-bold d-block mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <!-- Sala / Fila -->
                            <div class="col-md-4">
                                <div class="form-group mb-0 position-relative" x-data="{ open: false }" @click.outside="open = false">
                                    <label class="text-xs font-weight-bold">Sala / Fila</label>
                                    <input type="text" 
                                        wire:model.live="editSearchSeccion" 
                                        @focus="open = true"
                                        @keydown.tab="open = false"
                                        @keydown.enter="open = false"
                                        class="form-control border-light shadow-sm text-uppercase" 
                                        placeholder="Buscar Sala..." 
                                        {{ !$editNaveId ? 'disabled' : '' }}>
                                    @if($editSearchSeccion && !$editSeccionId && $editNaveId && count($editSecciones) > 0)
                                        <div x-show="open" class="list-group position-absolute w-100 shadow-lg" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                            @foreach($editSecciones as $s)
                                                @if(stripos($s->nombre, $editSearchSeccion) !== false)
                                                    <button type="button" wire:click="editSearchSeccion = '{{ $s->nombre }}'; editSeccionId = {{ $s->id }}; open = false;" class="list-group-item list-group-item-action py-2 text-sm text-left">
                                                        {{ $s->nombre }}
                                                    </button>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($editSeccionId)
                                        <small class="text-success font-weight-bold d-block mt-1"><i class="fas fa-check-circle mr-1"></i>Seleccionado</small>
                                    @endif
                                    @error('editSeccionId') <span class="text-danger text-xs font-weight-bold d-block mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <!-- Corral -->
                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <label class="text-xs font-weight-bold">Número de Corral</label>
                                    <input type="text" 
                                        wire:model.live="editCorral" 
                                        class="form-control border-light shadow-sm text-uppercase" 
                                        placeholder="Ej: 15">
                                    @error('editCorral') <span class="text-danger text-xs font-weight-bold d-block mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tipo de Movimiento -->
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-muted text-xs text-uppercase">Tipo de Movimiento</label>
                        <select wire:model="editDetalle" class="form-control shadow-sm border-light" style="border-radius: 10px;">
                            <option value="">Seleccionar Tipo...</option>
                            <option value="Ingreso a Recria">Ingreso a Recria</option>
                            <option value="Ingreso a Maternidad">Ingreso a Maternidad</option>
                            <option value="Descarte">Descarte</option>
                            @if($editDetalle && !in_array($editDetalle, ['Ingreso a Recria', 'Ingreso a Maternidad', 'Descarte']))
                                <option value="{{ $editDetalle }}">{{ $editDetalle }}</option>
                            @endif
                        </select>
                        @error('editDetalle') <span class="text-danger text-xs font-weight-bold d-block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0 rounded-bottom">
                    <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal" style="border-radius: 10px;">Cancelar</button>
                    <button type="button" wire:click="updateEvent" class="btn btn-primary shadow-sm" style="border-radius: 10px;">
                        <i class="fas fa-save mr-1"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        // Modal handlers
        $wire.on('open-modal', (modalId) => {
            $('#' + modalId).modal('show');
        });
        
        $wire.on('close-modal', (modalId) => {
            $('#' + modalId).modal('hide');
        });

        $wire.on('swal', (data) => {
            Swal.fire({
                icon: data.icon,
                title: data.title,
                text: data.text,
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'card-premium',
                    title: 'text-dark font-weight-bold',
                    confirmButton: 'btn btn-primary shadow-sm'
                },
                buttonsStyling: false
            });
        });

        // Delete confirmation function
        window.confirmDeleteEvent = function(id) {
            Swal.fire({
                title: '¿Eliminar Evento?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                customClass: {
                    popup: 'card-premium',
                    title: 'text-dark font-weight-bold',
                    confirmButton: 'btn btn-danger shadow-sm',
                    cancelButton: 'btn btn-secondary shadow-sm mr-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.call('deleteEvent', id);
                }
            });
        }
    </script>
    @endscript

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
