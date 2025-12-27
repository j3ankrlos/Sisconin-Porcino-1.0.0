<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Raza;
use App\Models\Especie;

class RazaManager extends Component
{
    use WithPagination;

    public $search = '';
    public $searchEspecie = '';
    public $isOpen = false;
    public $raza_id, $especie_id, $nombre, $descripcion;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'especie_id' => 'required|exists:especies,id',
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string|max:255',
    ];

    public function render()
    {
        $razas = Raza::with('especie')
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->when($this->searchEspecie, function($query) {
                $query->whereHas('especie', function($q) {
                    $q->where('nombre', 'like', '%' . $this->searchEspecie . '%');
                });
            })
            ->orderBy('nombre')
            ->paginate(10);

        $especies = Especie::orderBy('nombre')->get();

        return view('livewire.admin.raza-manager', compact('razas', 'especies'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
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
        $this->raza_id = null;
        $this->especie_id = '';
        $this->nombre = '';
        $this->descripcion = '';
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        // Validar unicidad manual para el par especie_id y nombre
        $exists = Raza::where('especie_id', $this->especie_id)
            ->where('nombre', $this->nombre)
            ->where('id', '!=', $this->raza_id)
            ->exists();

        if ($exists) {
            $this->addError('nombre', 'Esta raza ya existe para la especie seleccionada.');
            return;
        }

        Raza::updateOrCreate(['id' => $this->raza_id], [
            'especie_id' => $this->especie_id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
        ]);

        session()->flash('success', $this->raza_id ? 'Raza actualizada correctamente.' : 'Raza creada correctamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $raza = Raza::findOrFail($id);
        $this->raza_id = $id;
        $this->especie_id = $raza->especie_id;
        $this->nombre = $raza->nombre;
        $this->descripcion = $raza->descripcion;

        $this->openModal();
    }

    public function delete($id)
    {
        $raza = Raza::withCount('animals')->findOrFail($id);
        
        if($raza->animals_count > 0) {
            session()->flash('error', 'No se puede eliminar la raza porque tiene animales asociados.');
            return;
        }

        $raza->delete();
        session()->flash('success', 'Raza eliminada correctamente.');
    }
}
