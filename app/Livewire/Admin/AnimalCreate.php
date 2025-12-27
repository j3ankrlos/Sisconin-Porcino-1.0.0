<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Animal;
use App\Models\Especie;
use App\Models\Raza;
use App\Models\Seccion;
use App\Helpers\PicDateHelper;
use Carbon\Carbon;

class AnimalCreate extends Component
{
    // Datos del Animal
    public $id_animal, $id_oreja, $especie_id, $raza_id, $sexo, $fecha_nacimiento, $seccion_id, $corral, $estado = 'activo';
    
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
        $this->dispatch('animalAdded'); // Para refrescar la lista si es necesario
        $this->reset(['id_animal', 'id_oreja', 'seccion_id', 'corral']);
    }

    public function render()
    {
        $especies = Especie::all();
        $razas = $this->especie_id ? Raza::where('especie_id', $this->especie_id)->get() : [];
        $secciones = Seccion::with('nave.granja')->get();

        return view('livewire.admin.animal-create', [
            'especies' => $especies,
            'razas' => $razas,
            'secciones' => $secciones
        ]);
    }
}
