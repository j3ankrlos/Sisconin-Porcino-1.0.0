<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sucursal;
use App\Models\Empresa;

class SucursalManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;
    public $sucursal_id, $nombre, $direccion;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'direccion' => 'nullable|string|max:255',
    ];

    public function render()
    {
        $sucursales = Sucursal::with('unidades')
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.sucursal-manager', compact('sucursales'));
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
        $this->sucursal_id = null;
        $this->nombre = '';
        $this->direccion = '';
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        $empresa = Empresa::first(); 

        Sucursal::updateOrCreate(['id' => $this->sucursal_id], [
            'empresa_id' => $empresa->id,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
        ]);

        session()->flash('success', $this->sucursal_id ? 'Sucursal actualizada con éxito.' : 'Sucursal creada con éxito.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        $this->sucursal_id = $id;
        $this->nombre = $sucursal->nombre;
        $this->direccion = $sucursal->direccion;

        $this->openModal();
    }

    public function delete($id)
    {
        Sucursal::find($id)->delete();
        session()->flash('success', 'Sucursal eliminada con éxito.');
    }
}
