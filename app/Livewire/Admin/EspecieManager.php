<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Especie;

class EspecieManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;
    public $especie_id, $nombre, $descripcion;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'nombre' => 'required|string|max:255|unique:especies,nombre',
        'descripcion' => 'nullable|string|max:255',
    ];

    public function render()
    {
        $especies = Especie::where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy('nombre')
            ->paginate(10);

        return view('livewire.admin.especie-manager', compact('especies'));
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
        $this->especie_id = null;
        $this->nombre = '';
        $this->descripcion = '';
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->rules['nombre'] = 'required|string|max:255|unique:especies,nombre,' . $this->especie_id;
        $this->validate();

        Especie::updateOrCreate(['id' => $this->especie_id], [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
        ]);

        session()->flash('success', $this->especie_id ? 'Especie actualizada con éxito.' : 'Especie creada con éxito.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $especie = Especie::findOrFail($id);
        $this->especie_id = $id;
        $this->nombre = $especie->nombre;
        $this->descripcion = $especie->descripcion;

        $this->openModal();
    }

    public function delete($id)
    {
        // Verificar si tiene animales o razas asociadas antes de borrar
        $especie = Especie::withCount(['animals', 'razas'])->findOrFail($id);
        
        if($especie->animals_count > 0 || $especie->razas_count > 0) {
            session()->flash('error', 'No se puede eliminar la especie porque tiene animales o razas asociadas.');
            return;
        }

        $especie->delete();
        session()->flash('success', 'Especie eliminada con éxito.');
    }
}
