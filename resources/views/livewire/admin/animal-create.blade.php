<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus-circle mr-2"></i>Registrar Nuevo Animal</h3>
    </div>
    <form wire:submit.prevent="save">
        <div class="card-body">
            <div class="row">
                <!-- SECCIÓN DE FECHA PIC Y CALENDARIO (Ahora al principio) -->
                <div class="col-12 mb-4">
                    <div class="callout callout-info py-3 shadow-sm bg-light mb-0 border-left-lg">
                        <h5 class="text-info font-weight-bold mb-3"><i class="fas fa-calendar-alt mr-2"></i>Fecha de Nacimiento / PIC</h5>
                        
                        <div class="row align-items-end">
                            <!-- Calendario (Prioritario) -->
                            <div class="col-md-5 form-group mb-0">
                                <label class="small font-weight-bold">Fecha Calendario <span class="text-danger">*</span></label>
                                <input type="date" wire:model.live="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror shadow-sm border-info" style="font-size: 1.1rem; border-width: 2px;">
                                @error('fecha_nacimiento') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-1 d-none d-md-flex align-items-center justify-content-center">
                                <i class="fas fa-arrow-right text-muted"></i>
                            </div>

                            <!-- Campos PIC -->
                            <div class="col-md-3 form-group mb-0">
                                <label class="small font-weight-bold text-muted">Vuelta</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-redo-alt text-info"></i></span>
                                    </div>
                                    <input type="number" wire:model.live="vuelta" class="form-control border-left-0 bg-white" readonly>
                                </div>
                            </div>

                            <div class="col-md-3 form-group mb-0">
                                <label class="small font-weight-bold text-muted">Fecha PIC</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-fingerprint text-success"></i></span>
                                    </div>
                                    <input type="number" wire:model.live="pic" class="form-control border-left-0 bg-white" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 text-center">
                            <div class="badge badge-pill badge-info px-4 py-2" style="font-size: 0.9rem;">
                                <i class="fas fa-clock mr-2"></i>
                                Formato PIC: <strong>{{ \App\Helpers\PicDateHelper::format($fecha_nacimiento) }}</strong> 
                                <span class="mx-2">|</span> 
                                <i class="fas fa-calendar-check mr-1"></i> 
                                {{ $fecha_nacimiento ? \Carbon\Carbon::parse($fecha_nacimiento)->translatedFormat('l, d de F Y') : '---' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DATOS DEL ANIMAL -->
                <div class="col-md-6 form-group">
                    <label>ID Animal (Arete/Tatuaje) <span class="text-danger">*</span></label>
                    <input type="text" wire:model="id_animal" 
                           class="form-control @error('id_animal') is-invalid @enderror text-uppercase" 
                           placeholder="Ej: PRO-2025-001"
                           style="text-transform: uppercase;">
                    @error('id_animal') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label>ID Adicional (Oreja)</label>
                    <input type="text" wire:model="id_oreja" 
                           class="form-control text-uppercase" 
                           placeholder="Opcional"
                           style="text-transform: uppercase;">
                </div>

                <div class="col-md-4 form-group">
                    <label>Raza <span class="text-danger">*</span></label>
                    <select wire:model="raza_id" class="form-control @error('raza_id') is-invalid @enderror" {{ empty($razas) ? 'disabled' : '' }}>
                        <option value="">Seleccione Raza</option>
                        @foreach($razas as $r)
                            <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                        @endforeach
                    </select>
                    @error('raza_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-4 form-group">
                    <label>Sexo <span class="text-danger">*</span></label>
                    <select wire:model="sexo" class="form-control @error('sexo') is-invalid @enderror">
                        <option value="">Seleccione Sexo</option>
                        <option value="M">Macho</option>
                        <option value="F">Hembra</option>
                    </select>
                    @error('sexo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-4 form-group">
                    <label>Estado</label>
                    <select wire:model="estado" class="form-control">
                        <option value="activo">Activo</option>
                        <option value="vendido">Vendido</option>
                        <option value="fallecido">Fallecido</option>
                        <option value="descarte">Descarte</option>
                    </select>
                </div>

                <!-- Lote y Peso (Nuevos campos) -->
                <div class="col-md-6 form-group">
                    <label>Lote <span class="text-xs text-muted">(3 números + Letra opcional)</span></label>
                    <input type="text" wire:model.live="lote" 
                           class="form-control @error('lote') is-invalid @enderror text-uppercase" 
                           placeholder="Ej: 123A"
                           style="text-transform: uppercase;"
                           maxlength="4">
                    @error('lote') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label>Peso al Nacer (Kg)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-weight"></i></span>
                        </div>
                        <input type="number" step="0.01" wire:model="peso_nacimiento" class="form-control" placeholder="Ej: 1.50">
                    </div>
                </div>

                <!-- UBICACIÓN JERÁRQUICA -->
                <div class="col-12 mt-3">
                    <div class="card card-outline card-secondary bg-light shadow-sm">
                        <div class="card-body">
                            <h6 class="font-weight-bold mb-3"><i class="fas fa-map-marker-alt mr-2 text-secondary"></i>Ubicación Física</h6>
                            <div class="row">
                                <!-- Nave / Galpón Searchable -->
                                <div class="col-md-4 form-group">
                                    <label class="small">Nave / Galpón</label>
                                    <div class="position-relative">
                                        <div class="input-group input-group-sm">
                                            <input type="text" wire:model.live="search_nave" class="form-control" placeholder="Buscar Nave...">
                                        </div>
                                        @if($search_nave && !$nave_id)
                                            <div class="list-group position-absolute w-100 shadow-lg" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                                @forelse($naves as $n)
                                                    <button type="button" wire:click="selectNave({{ $n->id }}, '{{ $n->nombre }}')" class="list-group-item list-group-item-action py-2 text-sm text-left font-weight-bold">
                                                        {{ $n->nombre }} <span class="text-xs text-muted">({{ $n->granja->nombre }})</span>
                                                    </button>
                                                @empty
                                                    <div class="list-group-item text-xs text-muted">No hay resultados</div>
                                                @endforelse
                                            </div>
                                        @endif
                                        @if($nave_id)
                                            <small class="text-success font-weight-bold d-block mt-1"><i class="fas fa-check-circle mr-1"></i>Nave seleccionada</small>
                                        @endif
                                    </div>
                                </div>

                                <!-- Sala / Fila Searchable -->
                                <div class="col-md-4 form-group">
                                    <label class="small">Sala / Fila</label>
                                    <div class="position-relative">
                                        <div class="input-group input-group-sm">
                                            <input type="text" wire:model.live="search_seccion" class="form-control" placeholder="Buscar Sala..." {{ !$nave_id ? 'disabled' : '' }}>
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
                                            <small class="text-success font-weight-bold d-block mt-1"><i class="fas fa-check-circle mr-1"></i>Sala seleccionada</small>
                                        @endif
                                    </div>
                                </div>

                                <!-- Corral -->
                                <div class="col-md-4 form-group">
                                    <label class="small">Número de Corral</label>
                                    <input type="text" wire:model="corral" class="form-control form-control-sm" placeholder="Ej: 15">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer bg-white border-top">
            <button type="submit" class="btn btn-primary float-right px-4 shadow">
                <i class="fas fa-save mr-2"></i> Guardar Animal
            </button>
            <a href="{{ route('admin.animals.index') }}" class="btn btn-default float-right mr-2 px-4 shadow-sm">
                Cancelar
            </a>
        </div>
    </form>
</div>
