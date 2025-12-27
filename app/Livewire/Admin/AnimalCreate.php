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
    
    // Jerarquía de Ubicación
    public $granja_id, $nave_id;

    // Datos PIC
    public $vuelta, $pic;

    protected $rules = [
        'id_animal' => 'required|unique:animals,id_animal',
        'especie_id' => 'required',
        'raza_id' => 'required',
        'sexo' => 'required',
        'fecha_nacimiento' => 'required|date',
        'estado' => 'required',
    ];

    public function mount()
    {
        $this->fecha_nacimiento = date('Y-m-d');
        $this->syncPicFromDate();
    }

    // Sincronización de Combos Dependientes
    public function updatedGranjaId()
    {
        $this->nave_id = null;
        $this->seccion_id = null;
    }

    public function updatedNaveId()
    {
        $this->seccion_id = null;
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

        Animal::create([
            'id_animal' => $this->id_animal,
            'id_oreja' => $this->id_oreja,
            'especie_id' => $this->especie_id,
            'raza_id' => $this->raza_id,
            'sexo' => $this->sexo,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'seccion_id' => $this->seccion_id,
            'corral' => $this->corral,
            'estado' => $this->estado,
        ]);

        session()->flash('success', 'Animal registrado correctamente.');
        $this->dispatch('animalAdded'); 
        $this->reset(['id_animal', 'id_oreja', 'granja_id', 'nave_id', 'seccion_id', 'corral']);
    }

    public function render()
    {
        $especies = Especie::all();
        $razas = $this->especie_id ? Raza::where('especie_id', $this->especie_id)->get() : [];
        
        // Carga de combos dependientes de ubicación
        $granjas = Granja::all();
        $naves = $this->granja_id ? Nave::where('granja_id', $this->granja_id)->get() : [];
        $secciones = $this->nave_id ? Seccion::where('nave_id', $this->nave_id)->get() : [];

        return view('livewire.admin.animal-create', [
            'especies' => $especies,
            'razas' => $razas,
            'granjas' => $granjas,
            'naves' => $naves,
            'secciones' => $secciones
        ]);
    }
}
