<?php

namespace App\Livewire\Admin;

use App\Models\Animal;
use App\Models\AnimalEvento;
use App\Models\Seccion;
use App\Models\Nave;
use App\Models\Area;
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
    public $syncingFromDate = false;
    public $syncingFromPic = false;

    public function updatedFecha()
    {
        if (!$this->syncingFromPic) {
            $this->syncingFromDate = true;
            $this->syncPicFromDate();
            $this->syncingFromDate = false;
        }
    }

    // Sincronización: PIC -> Fecha
    public function updatedVuelta()
    {
        if (!$this->syncingFromDate) {
            $this->syncingFromPic = true;
            $this->syncDateFromPic();
            $this->syncingFromPic = false;
        }
    }

    public function updatedPic()
    {
        if (!$this->syncingFromDate) {
            $this->syncingFromPic = true;
            $this->syncDateFromPic();
            $this->syncingFromPic = false;
        }
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
        if (!empty($this->vuelta) && !empty($this->pic)) {
            try {
                $date = \App\Helpers\PicDateHelper::picToDate($this->pic, $this->vuelta);
                $this->fecha = $date->format('Y-m-d');
            } catch (\Exception $e) {
                // Si hay error en la conversión, no hacemos nada
            }
        }
    }

    public function loadAnimal()
    {
        if (empty($this->searchId)) {
            $this->animal = null;
            return;
        }

        $this->animal = Animal::with(['especie', 'raza', 'seccion.nave.area', 'eventos.user', 'eventos.seccion.nave'])
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

        // Check for redundant movement (Animal already here)
        if ($this->animal->seccion_id == $this->seccion_id && 
            (strval($this->animal->corral) === strval($this->corral) || (empty($this->animal->corral) && empty($this->corral)))) {
            $this->addError('seccion_id', 'El animal ya se encuentra registrado en esta ubicación exacta.');
            return;
        }

        // Validación personalizada de Ubicación (Logica de Negocio)
        if ($this->seccion_id) {
            $seccion = Seccion::with('nave')->find($this->seccion_id);
            if ($seccion && $seccion->nave) {
                $naveNombre = strtoupper($seccion->nave->nombre);
                
                // Definir Patrones de Naves Restringidas (Individuales) y Públicas (Grupales)
                // Restringidas: M... (Maternidad), G... (Gestación), MP... (Maternidad Prod), DM... (Destete/Recria individual?)
                // Publicas: PUB..., LA, LB, LE
                
                $isRestricted = preg_match('/^(M|G|MP|DM)/', $naveNombre);
                // Exepción: si es Publica explícita. (Aunque MP empieza con M, es restringida segun usuario)
                // El usuario dijo: "maternidad de produccion (MP...)... no se pueden ingresar ubicaciones repetidas".
                // "ubicaciones públicas (PUB1... LA, LB, LE)... aceptan varios".
                
                // Chequear si es publica para saltar validacion (por seguridad)
                // Note: Modified regex to strictly match start
                $isPublic = preg_match('/^(PUB|LA|LB|LE)/', $naveNombre);

                if ($isRestricted && !$isPublic) {
                    $query = Animal::where('seccion_id', $this->seccion_id)
                        ->whereNotIn('estado', ['muerto', 'vendido', 'descarte', 'baja'])
                        ->where('id', '!=', $this->animal->id); // Excluir el animal actual si ya está ahí (movimiento interno?)

                    if (!empty($this->corral)) {
                        $query->where('corral', $this->corral);
                    } else {
                        // Si no especifica corral, asumimos que la seccion completa es la ubicación (ej. Celda)
                        $query->where(function($q) {
                            $q->whereNull('corral')->orWhere('corral', '');
                        });
                    }

                    if ($query->exists()) {
                         // Obtener información del animal que ocupa la plaza para el mensaje
                         $ocupante = $query->first();
                         $this->addError('seccion_id', "La ubicación {$naveNombre} - {$seccion->nombre} " . ($this->corral ? "Corral {$this->corral}" : "") . " ya está ocupada por el animal {$ocupante->id_animal}.");
                         return;
                    }
                }
            }
        }

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

        $this->reset(['detalle', 'corral', 'seccion_id', 'nave_id', 'search_nave', 'search_seccion']);
        
        // Refresh local animal data to update badge status if needed, but history is handled by child component
        $this->loadAnimal(); 
        
        // Notify the history component to refresh
        $this->dispatch('refreshHistory');
    }

    // Auto-Select Logic
    public function updatedSearchNave()
    {
        $this->search_nave = strtoupper($this->search_nave);
        
        // Limpiar selección previa
        $this->nave_id = null;
        $this->seccion_id = null;
        $this->search_seccion = '';

        // Buscar coincidencia exacta en Sitio I
        $areaIds = Area::whereHas('unidad', function($q) {
            $q->where('nombre', 'Sitio I');
        })->pluck('id');

        $match = Nave::whereIn('area_id', $areaIds)
            ->where('nombre', $this->search_nave)
            ->first();

        if ($match) {
            $this->nave_id = $match->id;
        }
    }

    public function updatedSearchSeccion()
    {
        $this->search_seccion = strtoupper($this->search_seccion);
        $this->seccion_id = null;

        if ($this->nave_id) {
            $match = Seccion::where('nave_id', $this->nave_id)
                ->where('nombre', $this->search_seccion)
                ->first();

            if ($match) {
                $this->seccion_id = $match->id;
                $this->checkRedundantMove();
            }
        }
    }
    
    public function updatedCorral()
    {
        $this->corral = strtoupper($this->corral);
        $this->checkRedundantMove();
    }
    
    public function checkRedundantMove()
    {
        $this->resetErrorBag('seccion_id');
        
        if ($this->animal && $this->seccion_id) {
            // Check if same section
            if ($this->animal->seccion_id == $this->seccion_id) {
                // Check if same corral (handling nulls and empty strings)
                $currentCorral = (string)$this->animal->corral;
                $newCorral = (string)$this->corral;
                
                if ($currentCorral === $newCorral) {
                    $this->addError('seccion_id', 'ALERTA: El animal ya se encuentra en esta ubicación.');
                }
            }
        }
    }

    public function render()
    {
        // Obtener todas las Areas que son del Sitio I (EST, EXP)
        $areaIds = Area::whereHas('unidad', function($q) {
            $q->where('nombre', 'Sitio I');
        })->pluck('id');

        $naves = Nave::whereIn('area_id', $areaIds)
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
