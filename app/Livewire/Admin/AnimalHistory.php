<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class AnimalHistory extends Component
{
    public $animalId;
    public $animal;
    public $activeTab = 'events'; // events, audits

    public function mount($animalId)
    {
        $this->animalId = $animalId;
        $this->loadAnimal();
    }

    protected $listeners = ['refreshHistory' => 'loadAnimal'];

    public function loadAnimal()
    {
        $this->animal = \App\Models\Animal::with([
            'eventos.user',
            'eventos.seccion.nave.area.unidad',
        ])->findOrFail($this->animalId);
    }

    public function getSystemAuditsProperty()
    {
        // Get audits for the animal and its related events (if feasible)
        // For now, simpler: Audits on the animal model itself
        return \App\Models\Audit::where('auditable_type', \App\Models\Animal::class)
            ->where('auditable_id', $this->animalId)
            ->with('user')
            ->latest()
            ->get();
    }

    public $editingEventId = null;
    public $editFecha;
    public $editVuelta;
    public $editPic;
    public $editNaveId;
    public $editSeccionId;
    public $editCorral;
    public $editDetalle;
    
    // For searchable dropdowns in edit modal
    public $editSearchNave = '';
    public $editSearchSeccion = '';
    public $editNaves = [];
    public $editSecciones = [];
    
    // Sync flags to prevent infinite loops
    public $editSyncingFromDate = false;
    public $editSyncingFromPic = false;

    public function editEvent($eventId)
    {
        $event = \App\Models\AnimalEvento::with('seccion.nave')->where('animal_id', $this->animalId)->findOrFail($eventId);
        $this->editingEventId = $event->id;
        $this->editFecha = $event->fecha ? $event->fecha->format('Y-m-d') : null;
        $this->editDetalle = $event->detalle;
        $this->editCorral = $event->corral;
        
        // Sincronizar PIC desde la fecha cargada
        if ($this->editFecha) {
            $this->editVuelta = \App\Helpers\PicDateHelper::getVuelta($this->editFecha);
            $this->editPic = \App\Helpers\PicDateHelper::getPic($this->editFecha);
        }
        
        // Load location data
        if ($event->seccion) {
            $this->editSeccionId = $event->seccion_id;
            $this->editSearchSeccion = $event->seccion->nombre;
            $this->editNaveId = $event->seccion->nave_id;
            $this->editSearchNave = $event->seccion->nave->nombre;
            
            // Load available secciones for this nave
            $this->loadEditSecciones();
        }
        
        // Load available naves
        $this->loadEditNaves();
        
        $this->dispatch('open-modal', 'editEventModal');
    }
    
    public function loadEditNaves()
    {
        $areaIds = \App\Models\Area::whereHas('unidad', function($q) {
            $q->where('nombre', 'Sitio I');
        })->pluck('id');

        $this->editNaves = \App\Models\Nave::whereIn('area_id', $areaIds)->get();
    }
    
    public function loadEditSecciones()
    {
        if ($this->editNaveId) {
            $this->editSecciones = \App\Models\Seccion::where('nave_id', $this->editNaveId)->get();
        } else {
            $this->editSecciones = [];
        }
    }
    
    public function updatedEditSearchNave()
    {
        $this->editSearchNave = strtoupper($this->editSearchNave);
        $this->editNaveId = null;
        $this->editSeccionId = null;
        $this->editSearchSeccion = '';
        
        $match = \App\Models\Nave::whereHas('area.unidad', function($q) {
            $q->where('nombre', 'Sitio I');
        })->where('nombre', $this->editSearchNave)->first();
        
        if ($match) {
            $this->editNaveId = $match->id;
            $this->loadEditSecciones();
        }
    }
    
    public function updatedEditSearchSeccion()
    {
        $this->editSearchSeccion = strtoupper($this->editSearchSeccion);
        $this->editSeccionId = null;
        
        if ($this->editNaveId) {
            $match = \App\Models\Seccion::where('nave_id', $this->editNaveId)
                ->where('nombre', $this->editSearchSeccion)
                ->first();
            
            if ($match) {
                $this->editSeccionId = $match->id;
            }
        }
    }
    
    public function updatedEditCorral()
    {
        $this->editCorral = strtoupper($this->editCorral);
    }
    
    public function updatedEditFecha()
    {
        if (!$this->editSyncingFromPic && $this->editFecha) {
            $this->editSyncingFromDate = true;
            $this->editVuelta = \App\Helpers\PicDateHelper::getVuelta($this->editFecha);
            $this->editPic = \App\Helpers\PicDateHelper::getPic($this->editFecha);
            $this->editSyncingFromDate = false;
        }
    }
    
    public function updatedEditPic()
    {
        if (!$this->editSyncingFromDate) {
            $this->editSyncingFromPic = true;
            $this->syncPicToDate();
            $this->editSyncingFromPic = false;
        }
    }
    
    public function updatedEditVuelta()
    {
        if (!$this->editSyncingFromDate) {
            $this->editSyncingFromPic = true;
            $this->syncPicToDate();
            $this->editSyncingFromPic = false;
        }
    }
    
    private function syncPicToDate()
    {
        if (!empty($this->editVuelta) && !empty($this->editPic)) {
            try {
                $fecha = \App\Helpers\PicDateHelper::picToDate($this->editPic, $this->editVuelta);
                $this->editFecha = $fecha->format('Y-m-d');
            } catch (\Exception $e) {
                // Si hay error, no hacemos nada
            }
        }
    }

    public function updateEvent()
    {
        $this->validate([
            'editFecha' => 'required|date',
            'editSeccionId' => 'required|exists:secciones,id',
            'editCorral' => 'nullable|string',
            'editDetalle' => 'nullable|string',
        ]);

        $event = \App\Models\AnimalEvento::where('animal_id', $this->animalId)->findOrFail($this->editingEventId);
        
        $event->update([
            'fecha' => $this->editFecha,
            'seccion_id' => $this->editSeccionId,
            'corral' => $this->editCorral,
            'detalle' => $this->editDetalle,
        ]);

        $this->editingEventId = null;
        $this->dispatch('close-modal', 'editEventModal');
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Actualizado',
            'text' => 'El evento ha sido actualizado correctamente.',
        ]);
        $this->loadAnimal();
    }

    public function deleteEvent($eventId)
    {
        try {
            $event = \App\Models\AnimalEvento::where('animal_id', $this->animalId)
                ->where('id', $eventId)
                ->firstOrFail();
            
            $event->delete();
            $this->loadAnimal();
            
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'Evento eliminado correctamente.',
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo eliminar el evento.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.animal-history', [
            'events' => $this->animal ? $this->animal->eventos : [],
            'audits' => $this->getSystemAuditsProperty()
        ]);
    }
}
