<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Area;
use App\Models\Unidad;
use App\Models\Especie;

class AreaManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;
    public $area_id, $unidad_id, $nombre, $direccion, $latitud, $longitud, $tamano_hectareas, $fecha_establecimiento, $gerente;
    public $selected_species = [];

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'unidad_id' => 'required|exists:unidades,id',
        'nombre' => 'required|string|max:255',
        'direccion' => 'nullable|string|max:255',
        'latitud' => 'nullable|numeric',
        'longitud' => 'nullable|numeric',
        'tamano_hectareas' => 'nullable|numeric',
        'fecha_establecimiento' => 'nullable|date',
        'gerente' => 'nullable|string|max:255',
    ];

    public function render()
    {
        $areas = Area::with(['especies', 'unidad.sucursal'])
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->paginate(10);
        $unidades = Unidad::with('sucursal')->get();
        $especies = Especie::orderBy('nombre')->get();

        return view('livewire.admin.area-manager', compact('areas', 'unidades', 'especies'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->area_id = null;
        $this->unidad_id = null;
        $this->nombre = '';
        $this->direccion = '';
        $this->latitud = '';
        $this->longitud = '';
        $this->tamano_hectareas = '';
        $this->fecha_establecimiento = '';
        $this->gerente = '';
        $this->selected_species = [];
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        $area = Area::updateOrCreate(['id' => $this->area_id], [
            'unidad_id' => $this->unidad_id,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'latitud' => $this->latitud ?: null,
            'longitud' => $this->longitud ?: null,
            'tamano_hectareas' => $this->tamano_hectareas ?: null,
            'fecha_establecimiento' => $this->fecha_establecimiento ?: null,
            'gerente' => $this->gerente,
        ]);

        $area->especies()->sync($this->selected_species);

        session()->flash('success', $this->area_id ? 'Área actualizada con éxito.' : 'Área creada con éxito.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $area = Area::with('especies')->findOrFail($id);
        $this->area_id = $id;
        $this->unidad_id = $area->unidad_id;
        $this->nombre = $area->nombre;
        $this->direccion = $area->direccion;
        $this->latitud = $area->latitud;
        $this->longitud = $area->longitud;
        $this->tamano_hectareas = $area->tamano_hectareas;
        $this->fecha_establecimiento = $area->fecha_establecimiento;
        $this->gerente = $area->gerente;
        $this->selected_species = $area->especies->pluck('id')->toArray();

        $this->openModal();
    }

    public function delete($id)
    {
        Area::find($id)->delete();
        session()->flash('success', 'Área eliminada con éxito.');
    }
}
