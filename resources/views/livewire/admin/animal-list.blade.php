<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Listado de Animales (Activos)</h3>
        <div class="card-tools w-100 mt-2">
            <div class="row">
                <div class="col-md-3">
                    <input wire:model.live="search" type="text" class="form-control form-control-sm" placeholder="ID o Oreja...">
                </div>
                <div class="col-md-2">
                    <select wire:model.live="especie_id" class="form-control form-control-sm">
                        <option value="">Todas las Especies</option>
                        @foreach($especies as $e)
                            <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="raza_id" class="form-control form-control-sm" {{ empty($razas) ? 'disabled' : '' }}>
                        <option value="">Todas las Razas</option>
                        @foreach($razas as $r)
                            <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="sexo" class="form-control form-control-sm">
                        <option value="">Todos (Sexo)</option>
                        <option value="M">Macho</option>
                        <option value="F">Hembra</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="estado" class="form-control form-control-sm">
                        <option value="">Todos (Estado)</option>
                        <option value="activo">Activo</option>
                        <option value="vendido">Vendido</option>
                        <option value="fallecido">Fallecido</option>
                        <option value="descarte">Descarte</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button wire:click="$set('search', ''); $set('especie_id', ''); $set('raza_id', ''); $set('sexo', ''); $set('estado', '');" class="btn btn-sm btn-default btn-block" title="Limpiar filtros">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <div class="float-right">
                        <a href="{{ route('admin.animals.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus mr-1"></i> Registrar Nuevo
                        </a>
                        <button wire:click="exportExcel" class="btn btn-sm btn-success ml-1">
                            <i class="fas fa-file-excel mr-1"></i> Excel
                        </button>
                        <button wire:click="exportPdf" class="btn btn-sm btn-danger">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID Animal</th>
                    <th>Especie</th>
                    <th>Raza</th>
                    <th>Sexo</th>
                    <th>Nacimiento</th>
                    <th>Estado</th>
                    <th>Fase</th>
                    <th>Padre / Madre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($animals as $animal)
                <tr>
                    <td>
                        <span class="badge badge-info">{{ $animal->id_animal }}</span>
                        @if($animal->id_oreja)
                        <br><small class="text-muted">ID Oreja: {{ $animal->id_oreja }}</small>
                        @endif
                    </td>
                    <td>{{ $animal->especie->nombre }}</td>
                    <td>{{ $animal->raza->nombre }}</td>
                    <td>
                        @if($animal->sexo == 'M')
                        <i class="fas fa-mars text-blue"></i> Macho
                        @else
                        <i class="fas fa-venus text-pink" style="color: #e83e8c;"></i> Hembra
                        @endif
                    </td>
                    <td>{{ $animal->fecha_nacimiento->format('d/m/Y') }}</td>
                    <td>
                        @php
                            $badgeColor = match($animal->estado) {
                                'activo' => 'success',
                                'vendido' => 'primary',
                                'fallecido' => 'danger',
                                'descarte' => 'warning',
                                default => 'secondary',
                            };
                        @endphp
                        <span class="badge badge-{{ $badgeColor }}">
                            {{ ucfirst($animal->estado) }}
                        </span>
                    </td>
                    <td>
                        @if($animal->fase_reproductiva)
                        <span class="badge badge-warning">{{ $animal->fase_reproductiva }}</span>
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        <small>
                            P: {{ $animal->padre ? $animal->padre->id_animal : 'S/I' }} <br>
                            M: {{ $animal->madre ? $animal->madre->id_animal : 'S/I' }}
                        </small>
                    </td>
                    <td>
                        <button class="btn btn-xs btn-primary"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">No se encontraron animales.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        <div class="float-right">
            {{ $animals->links() }}
        </div>
    </div>
</div>
