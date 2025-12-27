<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus-circle mr-2"></i>Registrar Nuevo Animal</h3>
    </div>
    <form wire:submit.prevent="save">
        <div class="card-body">
            <div class="row">
                <!-- ID Principal -->
                <div class="col-md-6 form-group">
                    <label>ID Animal (Arete/Tatuaje) <span class="text-danger">*</span></label>
                    <input type="text" wire:model="id_animal" class="form-control @error('id_animal') is-invalid @enderror" placeholder="Ej: PRO-2025-001">
                    @error('id_animal') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <!-- ID Oreja -->
                <div class="col-md-6 form-group">
                    <label>ID Adicional (Oreja)</label>
                    <input type="text" wire:model="id_oreja" class="form-control" placeholder="Opcional">
                </div>

                <!-- Especie y Raza -->
                <div class="col-md-6 form-group">
                    <label>Especie <span class="text-danger">*</span></label>
                    <select wire:model.live="especie_id" class="form-control @error('especie_id') is-invalid @enderror">
                        <option value="">Seleccione Especie</option>
                        @foreach($especies as $e)
                            <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                        @endforeach
                    </select>
                    @error('especie_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label>Raza <span class="text-danger">*</span></label>
                    <select wire:model="raza_id" class="form-control @error('raza_id') is-invalid @enderror" {{ empty($razas) ? 'disabled' : '' }}>
                        <option value="">Seleccione Raza</option>
                        @foreach($razas as $r)
                            <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                        @endforeach
                    </select>
                    @error('raza_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <!-- Sexo y Estado -->
                <div class="col-md-6 form-group">
                    <label>Sexo <span class="text-danger">*</span></label>
                    <select wire:model="sexo" class="form-control @error('sexo') is-invalid @enderror">
                        <option value="">Seleccione Sexo</option>
                        <option value="M">Macho</option>
                        <option value="F">Hembra</option>
                    </select>
                    @error('sexo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label>Estado</label>
                    <select wire:model="estado" class="form-control">
                        <option value="activo">Activo</option>
                        <option value="vendido">Vendido</option>
                        <option value="fallecido">Fallecido</option>
                        <option value="descarte">Descarte</option>
                    </select>
                </div>

                <!-- SECCIÓN DE FECHA PIC Y CALENDARIO -->
                <div class="col-12 mt-3">
                    <div class="callout callout-info py-3 shadow-sm bg-light">
                        <h5><i class="fas fa-calendar-alt mr-2 text-info"></i>Configuración de Fecha PIC</h5>
                        
                        <div class="row items-center">
                            <!-- Campos PIC -->
                            <div class="col-md-3 form-group mb-0">
                                <label class="small font-weight-bold">Vuelta</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-redo-alt text-info"></i></span>
                                    </div>
                                    <input type="number" wire:model.live="vuelta" class="form-control border-left-0" placeholder="00">
                                </div>
                            </div>

                            <div class="col-md-3 form-group mb-0">
                                <label class="small font-weight-bold">Fecha PIC</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-fingerprint text-success"></i></span>
                                    </div>
                                    <input type="number" wire:model.live="pic" class="form-control border-left-0" placeholder="000" min="0" max="999">
                                </div>
                            </div>

                            <div class="col-md-1 d-none d-md-flex align-items-center justify-content-center pt-4">
                                <i class="fas fa-arrows-alt-h fa-2x text-muted"></i>
                            </div>

                            <!-- Calendario -->
                            <div class="col-md-5 form-group mb-0">
                                <label class="small font-weight-bold">Fecha Calendario <span class="text-danger">*</span></label>
                                <input type="date" wire:model.live="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror shadow-sm border-info" style="font-size: 1.1rem; border-width: 2px;">
                                @error('fecha_nacimiento') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-3 text-center">
                            <div class="badge badge-pill badge-info px-4 py-2" style="font-size: 1rem;">
                                <i class="fas fa-clock mr-2"></i>
                                Resultado: <strong>{{ \App\Helpers\PicDateHelper::format($fecha_nacimiento) }}</strong> 
                                <span class="mx-2">|</span> 
                                <i class="fas fa-calendar-check mr-1"></i> 
                                {{ $fecha_nacimiento ? \Carbon\Carbon::parse($fecha_nacimiento)->format('l, d de F Y') : '---' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UBICACIÓN JERÁRQUICA -->
                <div class="col-12 mt-3">
                    <div class="card card-outline card-secondary bg-light shadow-sm">
                        <div class="card-body">
                            <h6 class="font-weight-bold mb-3"><i class="fas fa-map-marker-alt mr-2 text-secondary"></i>Ubicación Física</h6>
                            <div class="row">
                                <!-- Granja -->
                                <div class="col-md-3 form-group">
                                    <label class="small">Granja</label>
                                    <select wire:model.live="granja_id" class="form-control">
                                        <option value="">Seleccione Granja</option>
                                        @foreach($granjas as $g)
                                            <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Nave -->
                                <div class="col-md-3 form-group">
                                    <label class="small">Nave / Galpón</label>
                                    <select wire:model.live="nave_id" class="form-control" {{ empty($naves) ? 'disabled' : '' }}>
                                        <option value="">Seleccione Nave</option>
                                        @foreach($naves as $n)
                                            <option value="{{ $n->id }}">{{ $n->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sección -->
                                <div class="col-md-3 form-group">
                                    <label class="small">Sección</label>
                                    <select wire:model="seccion_id" class="form-control" {{ empty($secciones) ? 'disabled' : '' }}>
                                        <option value="">Seleccione Sección</option>
                                        @foreach($secciones as $s)
                                            <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Corral -->
                                <div class="col-md-3 form-group">
                                    <label class="small">Número de Corral</label>
                                    <input type="number" wire:model="corral" class="form-control" placeholder="Ej: 15">
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
