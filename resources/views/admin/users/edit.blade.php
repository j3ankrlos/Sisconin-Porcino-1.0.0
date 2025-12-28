@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Editar Usuario</h3>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        @error('name')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group">
        <label for="email">Correo Electrónico</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        @error('email')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group">
        <label for="role">Rol principal (Actuará como plantilla)</label>
        <select class="form-control" id="role" name="role" required>
          <option value="">Seleccionar rol</option>
          @foreach($roles as $role)
            <option value="{{ $role->name }}" data-permissions="{{ $role->permissions->pluck('name') }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
          @endforeach
        </select>
        @error('role')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group mt-4">
        <label>Asignación de Permisos Individuales (Desacoplados)</label>
        <div class="row">
          @php
            $groups = [
                'Administración System' => ['ver usuarios', 'crear usuarios', 'editar usuarios', 'eliminar usuarios', 'ver roles'],
                'Producción (Porcina)' => ['ver sitio 1', 'ver sitio 2', 'ver sitio 3', 'ver maternidad', 'ver reproduccion', 'ver reemplazo', 'ver movimientos', 'ver reportes', 'ver crear activos', 'ver mortalidad'],
                'Configuración General' => ['ver empresa', 'editar empresa', 'ver granjas', 'crear granjas', 'editar granjas', 'eliminar granjas', 'ver especies', 'crear especies', 'editar especies', 'eliminar especies', 'ver razas', 'crear razas', 'editar razas', 'eliminar razas', 'ver granjas naves', 'ver secciones']
            ];
          @endphp

          @foreach($groups as $groupName => $groupPermissions)
            <div class="col-md-4 mb-3">
              <div class="card card-outline card-info">
                <div class="card-header p-2">
                  <h3 class="card-title text-sm">{{ $groupName }}</h3>
                </div>
                <div class="card-body p-2" style="max-height: 250px; overflow-y: auto;">
                  @foreach($groupPermissions as $pName)
                    @if($permissions->contains('name', $pName))
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input permission-checkbox" type="checkbox" name="permissions[]" 
                               id="p_{{ Str::slug($pName) }}" value="{{ $pName }}"
                               {{ in_array($pName, $userPermissions) ? 'checked' : '' }}>
                        <label for="p_{{ Str::slug($pName) }}" class="custom-control-label font-weight-normal text-sm">
                          {{ ucfirst($pName) }}
                        </label>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="form-group">
        <label for="granja_id">Sucursal de Origen</label>
        <select class="form-control" id="granja_id" name="granja_id">
          <option value="">Seleccionar sucursal</option>
          @foreach($granjas as $granja)
            <option value="{{ $granja->id }}" data-nombre="{{ $granja->nombre }}" {{ $user->granja_id == $granja->id ? 'selected' : '' }}>{{ $granja->nombre }}</option>
          @endforeach
        </select>
        @error('granja_id')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div id="sitio_container" class="form-group" style="{{ optional($user->granja)->nombre == 'Porcina' ? '' : 'display: none;' }}">
        <label for="sitio_id">Sitio / Área Específica</label>
        <select class="form-control" id="sitio_id" name="sitio_id">
          <option value="">Seleccionar sitio</option>
          @foreach($sitios as $sitio)
            <option value="{{ $sitio->id }}" data-granja="{{ $sitio->granja_id }}" {{ $user->sitio_id == $sitio->id ? 'selected' : '' }}>{{ $sitio->nombre }}</option>
          @endforeach
        </select>
        @error('sitio_id')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>


      <button type="submit" class="btn btn-primary">Actualizar</button>
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

    // Manejo de Plantillas por Rol
    roleSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const permissionsData = selectedOption.getAttribute('data-permissions');
        
        if (permissionsData) {
            const defaultPermissions = JSON.parse(permissionsData);
            
            // Limpiar todos primero
            permissionCheckboxes.forEach(cb => cb.checked = false);
            
            // Marcar los del rol
            permissionCheckboxes.forEach(cb => {
                if (defaultPermissions.includes(cb.value)) {
                    cb.checked = true;
                }
            });
        }
    });

    const granjaSelect = document.getElementById('granja_id');
    const sitioContainer = document.getElementById('sitio_container');
    const sitioSelect = document.getElementById('sitio_id');
    const sitioOptions = Array.from(sitioSelect.options);
    const initialSitioValue = "{{ $user->sitio_id }}";

    function filterSitios() {
        const selectedOption = granjaSelect.options[granjaSelect.selectedIndex];
        const granjaNombre = selectedOption.getAttribute('data-nombre');
        const granjaId = granjaSelect.value;

        // Limpiar opciones actuales
        sitioSelect.innerHTML = '<option value="">Seleccionar sitio</option>';

        if (granjaNombre === 'Porcina') {
            sitioContainer.style.display = 'block';
            sitioOptions.forEach(option => {
                if (option.getAttribute('data-granja') === granjaId) {
                    if (option.value === initialSitioValue && granjaId == "{{ $user->granja_id }}") {
                        option.selected = true;
                    }
                    sitioSelect.add(option);
                }
            });
        } else {
            sitioContainer.style.display = 'none';
            sitioSelect.value = '';
        }
    }

    granjaSelect.addEventListener('change', filterSitios);
    
    // Ejecutar al cargar para asegurar consistencia
    if (granjaSelect.value) {
        filterSitios();
        if (initialSitioValue) {
            sitioSelect.value = initialSitioValue;
        }
    }
});
</script>
@endsection