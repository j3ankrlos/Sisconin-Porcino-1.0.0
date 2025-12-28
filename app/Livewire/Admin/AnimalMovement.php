<?php

namespace App\Livewire\Admin;

use App\Models\Animal;
use App\Models\AnimalEvento;
use App\Models\Seccion;
use App\Models\Nave;
use App\Models\Granja;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AnimalMovement extends Component
{
    public $searchId = '';
    public $animal;
    
    // Jerarquía de Clasificación Simplificada (Sitio I)
    public $nave_id;
    public $seccion_id;
    public $corral;
    public $fecha;
    public $suceso = 'Movimiento';
    public $tipo = 'movimiento';
    public $detalle;

    // Datos PIC
    public $vuelta;
    public $pic;

    // Búsqueda para combos (Searchable Selects)
    public $search_nave = '';
    public $search_seccion = '';

    public function mount()
    {
        $this->fecha = date('Y-m-d');
        $this->syncPicFromDate();
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

    public function updatedSearchId()
    {
        $this->loadAnimal();
    }

    // Sincronización: Fecha -> PIC
    public function updatedFecha()
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
        if ($this->fecha) {
            $this->vuelta = \App\Helpers\PicDateHelper::getVuelta($this->fecha);
            $this->pic = \App\Helpers\PicDateHelper::getPic($this->fecha);
        }
    }

    private function syncDateFromPic()
    {
        if ($this->vuelta !== '' && $this->pic !== '') {
            $date = \App\Helpers\PicDateHelper::picToDate($this->pic, $this->vuelta);
            $this->fecha = $date->format('Y-m-d');
        }
    }

    public function loadAnimal()
    {
        if (empty($this->searchId)) {
            $this->animal = null;
            return;
        }

        $this->animal = Animal::with(['especie', 'raza', 'seccion.nave.granja', 'eventos.user', 'eventos.seccion.nave'])
            ->where('id_animal', $this->searchId)
            ->first();
    }

    public function saveMovement()
    {
        $this->validate([
            'animal' => 'required',
            'fecha' => 'required|date',
            'suceso' => 'required|string',
            'seccion_id' => 'required|exists:secciones,id',
            'corral' => 'nullable|string',
        ]);

        // Registrar el evento
        AnimalEvento::create([
            'animal_id' => $this->animal->id,
            'user_id' => Auth::id(),
            'tipo' => $this->tipo,
            'suceso' => $this->suceso,
            'detalle' => $this->detalle,
            'seccion_id' => $this->seccion_id,
            'corral' => $this->corral,
            'fecha' => $this->fecha,
        ]);

        // Actualizar la ubicación actual del animal
        $this->animal->update([
            'seccion_id' => $this->seccion_id,
            'corral' => $this->corral,
        ]);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text' => 'Movimiento registrado correctamente',
        ]);

        $this->reset(['detalle', 'corral', 'seccion_id', 'nave_id']);
        $this->loadAnimal(); // Recargar historial
    }

    public function render()
    {
        // Obtener todas las Granjas que son del Sitio I (EST, EXP)
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

        return view('livewire.admin.animal-movement', [
            'naves' => $naves,
            'secciones' => $secciones,
        ]);
    }
}
