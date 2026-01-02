<div>
    <div class="row">
        <!-- Panel de Registro -->
        <div class="col-md-12 mb-4">
            <div class="card card-premium shadow-lg border-0 overflow-hidden" style="border-radius: 15px;">
                <div class="card-header bg-gradient-primary p-4">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-exchange-alt mr-2"></i> Registrar Movimiento</h3>
                </div>
                <div class="card-body p-4">
                    <div class="form-group mb-4">
                        <label class="text-uppercase text-xs font-weight-bold text-muted">ID del Animal (Arete/Tatuaje)</label>
                        <div class="input-group input-group-lg shadow-sm">
                            <input type="text" wire:model.live.debounce.500ms="searchId" class="form-control border-0 bg-light text-uppercase" placeholder="Ej: 92762" style="border-radius: 10px 0 0 10px;">
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
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <label class="text-sm font-weight-bold">Vuelta</label>
                                        <input type="text" 
                                            wire:model.live="vuelta" 
                                            class="form-control border-light shadow-sm text-uppercase" 
                                            placeholder="Ej: 19"
                                            maxlength="2">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <label class="text-sm font-weight-bold">Fecha PIC</label>
                                        <input type="text" 
                                            wire:model.live="pic" 
                                            class="form-control border-light shadow-sm text-uppercase" 
                                            placeholder="Ej: 808"
                                            maxlength="3">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="text-sm font-weight-bold">Fecha de Movimiento</label>
                                        <input type="date" 
                                            wire:model.live="fecha" 
                                            class="form-control border-light shadow-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="bg-light p-3 mb-4" style="border-radius: 12px; border: 1px dashed #ced4da;">
                                <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-3"><i class="fas fa-map-marker-alt mr-1"></i> Destino del Movimiento (SITIO I)</h6>
                                
                                <div class="row">
                                    <!-- Nave / Galpón Searchable -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-0 position-relative" x-data="{ open: false }" @click.outside="open = false">
                                            <label class="text-xs font-weight-bold">Nave / Galpón</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" 
                                                    id="input-nave"
                                                    wire:model.live="search_nave" 
                                                    @focus="open = true"
                                                    @keydown.tab="open = false"
                                                    @keydown.enter="open = false"
                                                    class="form-control border-light shadow-sm text-uppercase" 
                                                    placeholder="Buscar Nave...">
                                            </div>
                                            @if($search_nave && !$nave_id)
                                                <div x-show="open" class="list-group position-absolute w-100 shadow-lg" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                                    @forelse($naves as $n)
                                                        <button type="button" wire:click="selectNave({{ $n->id }}, '{{ $n->nombre }}'); open = false; document.getElementById('input-sala').focus();" class="list-group-item list-group-item-action py-2 text-sm text-left">
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
                                        <div class="form-group mb-0 position-relative" x-data="{ open: false }" @click.outside="open = false">
                                            <label class="text-xs font-weight-bold">Sala / Fila</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" 
                                                    id="input-sala"
                                                    wire:model.live="search_seccion" 
                                                    @focus="open = true"
                                                    @keydown.tab="open = false"
                                                    @keydown.enter="open = false"
                                                    class="form-control border-light shadow-sm text-uppercase" 
                                                    placeholder="Buscar Sala..." 
                                                    {{ !$nave_id ? 'disabled' : '' }}>
                                            </div>
                                            @if($search_seccion && !$seccion_id && $nave_id)
                                                <div x-show="open" class="list-group position-absolute w-100 shadow-lg" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                                    @forelse($secciones as $s)
                                                        <button type="button" wire:click="selectSeccion({{ $s->id }}, '{{ $s->nombre }}'); open = false; document.getElementById('input-corral').focus();" class="list-group-item list-group-item-action py-2 text-sm text-left">
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
                                            <input type="text" 
                                                id="input-corral"
                                                wire:model.live="corral" 
                                                class="form-control border-light shadow-sm text-uppercase" 
                                                placeholder="Ej: 15">
                                        </div>
                                    </div>
                                </div>
                                @error('seccion_id') 
                                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)" x-transition.duration.1000ms class="alert alert-danger mt-2 text-sm font-weight-bold fade show">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> {{ $message }}
                                    </div> 
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-sm font-weight-bold">Tipo de Movimiento</label>
                                <select 
                                    id="input-detalle"
                                    wire:model="detalle" 
                                    class="form-control border-light shadow-sm">
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

        <!-- Panel de Historial (Ahora debajo y full width) -->
        <div class="col-md-12">
            @if($animal)
                <livewire:admin.animal-history :animalId="$animal->id" wire:key="history-{{ $animal->id }}" />
            @else
                <div class="card shadow-lg border-0 h-100" style="border-radius: 15px;">
                    <div class="card-body d-flex align-items-center justify-content-center flex-column text-muted py-5">
                        <i class="fas fa-history fa-4x mb-3 text-light"></i>
                        <p class="mb-0">Selecciona un animal para ver su historial completo.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('keydown', function (event) {
            // Verificamos si la tecla presionada es Enter (código 13)
            if (event.key === 'Enter') {
                const element = event.target;

                // Evitamos que el Enter afecte a los botones de envío o textareas
                if (element.tagName === 'INPUT' || element.tagName === 'SELECT') {
                    
                    // Si el input es de tipo 'submit', 'button' o 'reset', dejamos que funcione normal
                    if (['submit', 'button', 'reset'].includes(element.type)) {
                        return;
                    }

                    // Ignorar inputs de tipo date si se quiere abrir el calendario, pero aqui queremos navegar
                    // Si se desea comportamiento estandar de date picker con enter, se podria excluir, 
                    // pero para navegacion rapida es mejor saltar.

                    event.preventDefault(); // Evita que se envíe el formulario

                    // Buscamos TODOS los elementos input/select/button visibles en la pagina
                    // Esto permite saltar del buscador (fuera del form) al formulario de registro y llegar al boton
                    const selector = 'input:not([disabled]):not([type="hidden"]), select:not([disabled]), button:not([disabled])';
                    const allInputs = Array.from(document.querySelectorAll(selector));

                    // Filtrar solo los visiblemente activos (por si livewire deja basura en DOM o elementos ocultos)
                    const focusableElements = allInputs.filter(el => {
                        return el.offsetWidth > 0 && el.offsetHeight > 0 && el.style.display !== 'none';
                    });

                    const index = focusableElements.indexOf(element);

                    // Si existe un siguiente elemento, le damos el foco
                    if (index > -1 && index + 1 < focusableElements.length) {
                        const nextElement = focusableElements[index + 1];
                        nextElement.focus();
                        // Opcional: Seleccionar el texto del siguiente input para reemplazar rapido
                        if (nextElement.select && nextElement.type !== 'date') {
                            nextElement.select();
                        }
                    }
                }
            }
        });
    </script>

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
