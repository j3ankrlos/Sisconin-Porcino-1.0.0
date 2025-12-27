<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Empresa;

class EmpresaConfig extends Component
{
    public $empresa;
    public $nombre, $direccion, $telefono, $email, $nit, $fecha_fundacion;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'direccion' => 'nullable|string|max:255',
        'telefono' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'nit' => 'required|string|max:20',
        'fecha_fundacion' => 'nullable|date',
    ];

    public function mount()
    {
        $this->empresa = Empresa::first() ?: new Empresa();
        $this->nombre = $this->empresa->nombre;
        $this->direccion = $this->empresa->direccion;
        $this->telefono = $this->empresa->telefono;
        $this->email = $this->empresa->email;
        $this->nit = $this->empresa->nit;
        $this->fecha_fundacion = $this->empresa->fecha_fundacion ? $this->empresa->fecha_fundacion : null;
    }

    public function save()
    {
        $this->validate();

        $this->empresa->fill([
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'nit' => $this->nit,
            'fecha_fundacion' => $this->fecha_fundacion,
        ]);

        $this->empresa->save();

        session()->flash('success', 'Información de la empresa actualizada con éxito.');
    }

    public function render()
    {
        return view('livewire.admin.empresa-config');
    }
}
