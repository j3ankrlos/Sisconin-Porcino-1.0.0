<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Granja;
use App\Models\Empresa;
use App\Models\Especie;

class GranjaManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;
    public $granja_id, $nombre, $direccion, $latitud, $longitud, $tamano_hectareas, $fecha_establecimiento, $gerente;
    public $selected_species = [];

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
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
        $granjas = Granja::with('especies')
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->paginate(10);
        $especies = Especie::orderBy('nombre')->get();

        return view('livewire.admin.granja-manager', compact('granjas', 'especies'));
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
        $this->granja_id = null;
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

        $empresa = Empresa::first(); 

        $granja = Granja::updateOrCreate(['id' => $this->granja_id], [
            'empresa_id' => $empresa->id,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'latitud' => $this->latitud ?: null,
            'longitud' => $this->longitud ?: null,
            'tamano_hectareas' => $this->tamano_hectareas ?: null,
            'fecha_establecimiento' => $this->fecha_establecimiento ?: null,
            'gerente' => $this->gerente,
        ]);

        $granja->especies()->sync($this->selected_species);

        session()->flash('success', $this->granja_id ? 'Sucursal actualizada con éxito.' : 'Sucursal creada con éxito.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $granja = Granja::with('especies')->findOrFail($id);
        $this->granja_id = $id;
        $this->nombre = $granja->nombre;
        $this->direccion = $granja->direccion;
        $this->latitud = $granja->latitud;
        $this->longitud = $granja->longitud;
        $this->tamano_hectareas = $granja->tamano_hectareas;
        $this->fecha_establecimiento = $granja->fecha_establecimiento;
        $this->gerente = $granja->gerente;
        $this->selected_species = $granja->especies->pluck('id')->toArray();

        $this->openModal();
    }

    public function delete($id)
    {
        Granja::find($id)->delete();
        session()->flash('success', 'Sucursal eliminada con éxito.');
    }
}
