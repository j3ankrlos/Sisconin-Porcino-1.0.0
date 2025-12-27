<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-building mr-2"></i> Información de la Empresa Principal</h3>
    </div>
    <form wire:submit.prevent="save">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="nombre">Nombre de la Empresa <span class="text-danger">*</span></label>
                    <input type="text" wire:model="nombre" class="form-control" id="nombre" placeholder="Nombre completo">
                    @error('nombre') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label for="nit">NIT / Identificación Fiscal <span class="text-danger">*</span></label>
                    <input type="text" wire:model="nit" class="form-control" id="nit" placeholder="NIT o identificación">
                    @error('nit') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="direccion">Dirección Fiscal</label>
                    <input type="text" wire:model="direccion" class="form-control" id="direccion" placeholder="Dirección completa">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="telefono">Teléfono de Contacto</label>
                    <input type="text" wire:model="telefono" class="form-control" id="telefono" placeholder="+00 000 000 0000">
                </div>
                <div class="col-md-4 form-group">
                    <label for="email">E-mail corporativo</label>
                    <input type="email" wire:model="email" class="form-control" id="email" placeholder="contacto@empresa.com">
                </div>
                <div class="col-md-4 form-group">
                    <label for="fecha_fundacion">Fecha de Fundación</label>
                    <input type="date" wire:model="fecha_fundacion" class="form-control" id="fecha_fundacion">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary float-right">
                <i class="fas fa-save mr-1"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>
