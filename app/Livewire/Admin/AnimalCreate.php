<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Animal;
use App\Models\Especie;
use App\Models\Raza;
use App\Models\Granja;
use App\Models\Nave;
use App\Models\Seccion;
use App\Helpers\PicDateHelper;
use Carbon\Carbon;

class AnimalCreate extends Component
{
    // Datos del Animal
    public $id_animal, $id_oreja, $especie_id, $raza_id, $sexo, $fecha_nacimiento, $seccion_id, $corral, $estado = 'activo';
    public $lote, $peso_nacimiento;
    
    // Jerarquía de Ubicación
    public $nave_id;

    // Datos PIC
    public $vuelta, $pic;

    // Búsqueda para combos (Searchable Selects)
    public $search_nave = '';
    public $search_seccion = '';

    protected $rules = [
        'id_animal' => 'required|unique:animals,id_animal',
        'especie_id' => 'required',
        'raza_id' => 'required',
        'sexo' => 'required',
        'fecha_nacimiento' => 'required|date',
        'lote' => 'nullable|regex:/^[0-9]{3}[A-Z]?$/',
        'peso_nacimiento' => 'nullable|numeric|min:0',
        'estado' => 'required',
    ];

    protected $messages = [
        'lote.regex' => 'El formato del Lote debe ser de 3 números y una letra opcional (ej: 123 o 123A).',
    ];

    public function mount()
    {
        $this->especie_id = 1; // Porcino por defecto
        $this->fecha_nacimiento = date('Y-m-d');
        $this->syncPicFromDate();
    }

    // Asegurar IDs en mayúsculas
    public function updatedIdAnimal($value)
    {
        $this->id_animal = strtoupper($value);
    }

    public function updatedIdOreja($value)
    {
        $this->id_oreja = strtoupper($value);
    }

    public function updatedLote($value)
    {
        $this->lote = strtoupper($value);
    }

    public function selectNave($id, $nombre)
    {
        $this->nave_id = $id;
        $this->search_nave = $nombre;
        $this->seccion_id = null;
        $this->search_seccion = '';
    }

    public function selectSeccion($id, $nombre)
    {
        $this->seccion_id = $id;
        $this->search_seccion = $nombre;
    }

    // Sincronización: Fecha -> PIC
    public function updatedFechaNacimiento()
    {
        $this->syncPicFromDate();
    }

    // Sincronización: PIC -> Fecha
    public function updatedVuelta()
    {
        $this->syncDateFromPic();
    }

    public function updatedPic()
    {
        $this->syncDateFromPic();
    }

    private function syncPicFromDate()
    {
        if ($this->fecha_nacimiento) {
            $this->vuelta = PicDateHelper::getVuelta($this->fecha_nacimiento);
            $this->pic = PicDateHelper::getPic($this->fecha_nacimiento);
        }
    }

    private function syncDateFromPic()
    {
        if ($this->vuelta !== '' && $this->pic !== '') {
            $date = PicDateHelper::picToDate($this->pic, $this->vuelta);
            $this->fecha_nacimiento = $date->format('Y-m-d');
        }
    }

    public function save()
    {
        $this->validate();

        $animal = Animal::create([
            'id_animal' => $this->id_animal,
            'id_oreja' => $this->id_oreja,
            'especie_id' => $this->especie_id,
            'raza_id' => $this->raza_id,
            'sexo' => $this->sexo,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'seccion_id' => $this->seccion_id,
            'corral' => $this->corral,
            'lote' => $this->lote,
            'peso_nacimiento' => $this->peso_nacimiento,
            'estado' => $this->estado,
        ]);

        // Registrar evento de inicialización en el historial
        \App\Models\AnimalEvento::create([
            'animal_id' => $animal->id,
            'user_id' => auth()->id(),
            'tipo' => 'registro',
            'suceso' => 'Ingreso al Sistema',
            'detalle' => 'Registro inicial del activo en la granja.',
            'seccion_id' => $this->seccion_id,
            'corral' => $this->corral,
            'fecha' => now(),
        ]);

        session()->flash('success', 'Animal registrado correctamente.');
        $this->dispatch('animalAdded'); 
        $this->reset(['id_animal', 'id_oreja', 'nave_id', 'seccion_id', 'corral', 'lote', 'peso_nacimiento', 'search_nave', 'search_seccion']);
        $this->especie_id = 1; // Mantener especie porcina
    }

    public function render()
    {
        $razas = Raza::where('especie_id', $this->especie_id)->get();
        
        // Obtener IDs de las granjas que pertenecen al Sitio I
        $granjaIds = Granja::where('etapa', 'Sitio I')->pluck('id');

        $naves = Nave::whereIn('granja_id', $granjaIds)
            ->when($this->search_nave, function($q) {
                $q->where('nombre', 'like', '%' . $this->search_nave . '%');
            })
            ->get();

        $secciones = $this->nave_id ? 
            Seccion::where('nave_id', $this->nave_id)
                ->when($this->search_seccion, function($q) {
                    $q->where('nombre', 'like', '%' . $this->search_seccion . '%');
                })
                ->get() 
            : [];

        return view('livewire.admin.animal-create', [
            'razas' => $razas,
            'naves' => $naves,
            'secciones' => $secciones
        ]);
    }
}
