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
                'Producción (Porcina)' => ['ver sucursal', 'ver unidad', 'ver area', 'ver maternidad', 'ver reproduccion', 'ver reemplazo', 'ver movimientos', 'ver reportes', 'ver crear activos', 'ver mortalidad'],
                'Configuración General' => ['ver empresa', 'editar empresa', 'ver sucursales', 'crear sucursales', 'editar sucursales', 'eliminar sucursales', 'ver unidades', 'ver areas', 'ver especies', 'crear especies', 'editar especies', 'eliminar especies', 'ver razas', 'crear razas', 'editar razas', 'eliminar razas', 'ver naves', 'ver secciones']
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

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="sucursal_id">Sucursal</label>
            <select class="form-control" id="sucursal_id" name="sucursal_id">
              <option value="">Seleccionar sucursal</option>
              @foreach($sucursales as $sucursal)
                <option value="{{ $sucursal->id }}" {{ $user->sucursal_id == $sucursal->id ? 'selected' : '' }}>{{ $sucursal->nombre }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group" id="unidad_container" style="{{ $user->sucursal_id ? '' : 'display: none;' }}">
            <label for="unidad_id">Unidad</label>
            <select class="form-control" id="unidad_id" name="unidad_id">
              <option value="">Seleccionar unidad</option>
              @foreach($unidades as $unidad)
                <option value="{{ $unidad->id }}" data-sucursal="{{ $unidad->sucursal_id }}" {{ $user->unidad_id == $unidad->id ? 'selected' : '' }}>{{ $unidad->nombre }}</option>
              @endforeach
            </select>
          </div>
        </div>
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

    const sucursalSelect = document.getElementById('sucursal_id');
    const unidadContainer = document.getElementById('unidad_container');
    const unidadSelect = document.getElementById('unidad_id');
    const unidadOptions = Array.from(unidadSelect.options);

    const initialUnidadValue = "{{ $user->unidad_id }}";

    function filterUnidades() {
        const sucursalId = sucursalSelect.value;
        unidadSelect.innerHTML = '<option value="">Seleccionar unidad</option>';

        if (sucursalId) {
            unidadContainer.style.display = 'block';
            unidadOptions.forEach(option => {
                if (option.getAttribute('data-sucursal') === sucursalId) {
                    const clone = option.cloneNode(true);
                    if (clone.value === initialUnidadValue) clone.selected = true;
                    unidadSelect.add(clone);
                }
            });
        } else {
            unidadContainer.style.display = 'none';
        }
    }

    sucursalSelect.addEventListener('change', filterUnidades);
    
    // Inicialización
    if (sucursalSelect.value) {
        filterUnidades();
    }
});
</script>
@endsection