<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Animal;
use App\Models\Raza;
use App\Models\Area;
use App\Models\Nave;
use App\Models\Seccion;
use App\Models\AnimalEvento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnimalBatchCreate extends Component
{
    // Parámetros del Formulario
    public $lote;
    public $raza_id;
    public $origen; 
    public $sexo = 'F';
    public $nave_id;
    public $seccion_id;
    public $corral;
    public $peso;
    public $cantidad = 1;
    public $id_inicio; // ID de inicio manual/automático
    public $puedo_editar_id = false; // Control de bloqueo del campo ID
    
    // Datos de Fecha y PIC
    public $fecha_nacimiento;
    public $vuelta;
    public $pic;
    
    // Flags de sincronización
    public $syncingFromDate = false;
    public $syncingFromPic = false;
    
    // Información de Secuencia
    public $ultimo_id_sistema = 0;
    
    // Búsqueda para combos (Searchable Selects)
    public $search_nave = '';
    public $search_seccion = '';
    
    // Listado de animales generados (Pendientes de guardar)
    public $animales_lista = [];

    protected $rules = [
        'lote' => 'required',
        'raza_id' => 'required',
        'sexo' => 'required',
        'nave_id' => 'required',
        'seccion_id' => 'required',
        'id_inicio' => 'required|integer|min:1',
        'cantidad' => 'required|integer|min:1|max:500',
        'peso' => 'nullable|numeric',
    ];

    public function mount()
    {
        // No pre-cargar genética por defecto
        $this->actualizarInformacionSecuencia();
        
        // Inicializar fecha
        $this->fecha_nacimiento = date('Y-m-d');
        $this->syncPicFromDate();
    }

    public function updatedFechaNacimiento()
    {
        if (!$this->syncingFromPic) {
            $this->syncingFromDate = true;
            $this->syncPicFromDate();
            $this->syncingFromDate = false;
        }
    }

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
        if ($this->fecha_nacimiento) {
            $this->vuelta = \App\Helpers\PicDateHelper::getVuelta($this->fecha_nacimiento);
            $this->pic = \App\Helpers\PicDateHelper::getPic($this->fecha_nacimiento);
        }
    }

    private function syncDateFromPic()
    {
        if (!empty($this->vuelta) && !empty($this->pic)) {
            try {
                $date = \App\Helpers\PicDateHelper::picToDate($this->pic, $this->vuelta);
                $this->fecha_nacimiento = $date->format('Y-m-d');
            } catch (\Exception $e) {
                // Silencio en error
            }
        }
    }

    public function updatedRazaId()
    {
        $this->actualizarInformacionSecuencia();
    }

    /**
     * Busca el último ID numérico para la genética seleccionada.
     */
    public function actualizarInformacionSecuencia()
    {
        if (!$this->raza_id) return;

        $ultimo = Animal::where('raza_id', $this->raza_id)
            ->whereRaw('id_animal REGEXP "^[0-9]+$"')
            ->orderByRaw('CAST(id_animal AS UNSIGNED) DESC')
            ->first();

        $this->ultimo_id_sistema = $ultimo ? (int)$ultimo->id_animal : 0;
        
        // Si hay historia, sugerimos el siguiente. Si no, dejamos que el usuario lo ponga.
        if ($this->ultimo_id_sistema > 0) {
            $this->id_inicio = $this->ultimo_id_sistema + 1;
        }
    }

    /**
     * Genera la lista de correlativos en memoria (Botón Agregar).
     */
    public function generarLista()
    {
        $this->validate();
        
        $this->animales_lista = [];
        $inicio = (int)$this->id_inicio;
        $geneticaNombre = Raza::find($this->raza_id)->nombre ?? '---';

        for ($i = 0; $i < $this->cantidad; $i++) {
            $nuevoId = $inicio + $i;
            
            // Verificar si el ID ya existe para evitar errores en la lista
            $existe = Animal::where('id_animal', $nuevoId)->exists();

            $this->animales_lista[] = [
                'id_animal' => $nuevoId,
                'lote' => $this->lote,
                'genetica' => $geneticaNombre,
                'sexo' => $this->sexo == 'F' ? 'Hembra' : 'Macho',
                'peso' => $this->peso,
                'existe' => $existe
            ];
        }
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

    /**
     * Alterna el bloqueo del campo ID Inicial.
     */
    public function toggleEditarId()
    {
        $this->puedo_editar_id = !$this->puedo_editar_id;
    }

    /**
     * Elimina un item de la lista temporal.
     */
    public function eliminarDeLista($index)
    {
        unset($this->animales_lista[$index]);
        $this->animales_lista = array_values($this->animales_lista);
    }

    /**
     * Guarda definitivamente los animales en la DB (Botón Guardar).
     */
    public function guardarLoteDefinitivo()
    {
        if (empty($this->animales_lista)) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Lista Vacía',
                'text' => 'Debe generar la lista de animales antes de guardar.',
            ]);
            return;
        }

        // Verificar duplicados antes de insertar
        $ids = array_column($this->animales_lista, 'id_animal');
        $duplicados = Animal::whereIn('id_animal', $ids)->pluck('id_animal')->toArray();

        if (count($duplicados) > 0) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Conflictos Detectados',
                'text' => 'Los IDs ' . implode(', ', $duplicados) . ' ya fueron ocupados. Por favor genere la lista nuevamente.',
            ]);
            return;
        }

        DB::beginTransaction();
        try {
            foreach ($this->animales_lista as $item) {
                $animal = Animal::create([
                    'id_animal' => $item['id_animal'],
                    'especie_id' => 1, // Porcino
                    'raza_id' => $this->raza_id,
                    'sexo' => $this->sexo,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'lote' => $item['lote'],
                    'seccion_id' => $this->seccion_id,
                    'corral' => $this->corral,
                    'peso_nacimiento' => $item['peso'],
                    'notas' => "Registro masivo. Origen: " . $this->origen,
                    'estado' => 'activo'
                ]);

                // Registrar evento inicial
                AnimalEvento::create([
                    'animal_id' => $animal->id,
                    'user_id' => Auth::id(),
                    'tipo' => 'registro',
                    'suceso' => 'Ingreso por Lote',
                    'detalle' => "Genética " . $item['genetica'] . " - Lote " . $item['lote'],
                    'seccion_id' => $this->seccion_id,
                    'corral' => $this->corral,
                    'fecha' => $this->fecha_nacimiento,
                ]);
            }

            DB::commit();

            $total = count($this->animales_lista);
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Lote Guardado!',
                'text' => "Se han registrado con éxito {$total} activos.",
            ]);

            $this->reset(['animales_lista', 'cantidad', 'corral']);
            $this->actualizarInformacionSecuencia();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error Crítico',
                'text' => $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        $razas = Raza::whereIn('nombre', ['F1', 'F2'])->get();
        // Obtener IDs de las Areas (ej: EST, EXP) que pertenecen al Sitio I (Unidad)
        $areaIds = Area::whereHas('unidad', function($q) {
            $q->where('nombre', 'Sitio I');
        })->pluck('id');

        // Filtrado de Naves
        $naves = Nave::whereIn('area_id', $areaIds)
            ->when($this->search_nave, function($q) {
                $q->where('nombre', 'like', '%' . $this->search_nave . '%');
            })
            ->with('area')
            ->get();

        // Filtrado de Secciones
        $secciones = $this->nave_id ? 
            Seccion::where('nave_id', $this->nave_id)
                ->when($this->search_seccion, function($q) {
                    $q->where('nombre', 'like', '%' . $this->search_seccion . '%');
                })
                ->get() 
            : [];

        return view('livewire.admin.animal-batch-create', [
            'razas' => $razas,
            'naves' => $naves,
            'secciones' => $secciones
        ]);
    }
}
