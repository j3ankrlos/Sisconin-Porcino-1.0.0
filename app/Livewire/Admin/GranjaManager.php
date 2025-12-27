<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Granja;
use App\Models\Empresa;
use App\Models\Especializacion;

class GranjaManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;
    public $granja_id, $nombre, $direccion, $latitud, $longitud, $tamano_hectareas, $fecha_establecimiento, $gerente;
    public $selected_specializations = [];
    public $otra_especializacion = '';

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
        $granjas = Granja::with('especializaciones')
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->paginate(5);
        $especialidades = Especializacion::all();

        return view('livewire.admin.granja-manager', compact('granjas', 'especialidades'));
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
        $this->selected_specializations = [];
        $this->otra_especializacion = '';
    }

    public function store()
    {
        $this->validate();

        $empresa = Empresa::first(); // Asignar a la primera empresa por defecto

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

        // Manejar especializaciones
        if ($this->otra_especializacion) {
            $nueva = Especializacion::firstOrCreate(['nombre' => $this->otra_especializacion]);
            $this->selected_specializations[] = $nueva->id;
        }
        $granja->especializaciones()->sync($this->selected_specializations);

        session()->flash('success', $this->granja_id ? 'Sucursal actualizada con éxito.' : 'Sucursal creada con éxito.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $granja = Granja::with('especializaciones')->findOrFail($id);
        $this->granja_id = $id;
        $this->nombre = $granja->nombre;
        $this->direccion = $granja->direccion;
        $this->latitud = $granja->latitud;
        $this->longitud = $granja->longitud;
        $this->tamano_hectareas = $granja->tamano_hectareas;
        $this->fecha_establecimiento = $granja->fecha_establecimiento;
        $this->gerente = $granja->gerente;
        $this->selected_specializations = $granja->especializaciones->pluck('id')->toArray();

        $this->openModal();
    }

    public function delete($id)
    {
        Granja::find($id)->delete();
        session()->flash('success', 'Sucursal eliminada con éxito.');
    }
}
