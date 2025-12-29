<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Animal;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AnimalList extends Component
{
    use WithPagination;

    public $search = '';
    public $especie_id = 1; // Default to Porcino
    public $raza_id = '';
    public $sexo = '';
    public $estado = '';

    protected $paginationTheme = 'bootstrap';

    // Resetear página cuando cambian los filtros
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'especie_id', 'raza_id', 'sexo', 'estado'])) {
            $this->resetPage();
        }
    }

    private function getFilteredAnimalsQuery()
    {
        return Animal::with(['especie', 'raza', 'padre', 'madre'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('id_animal', 'like', '%' . $this->search . '%')
                      ->orWhere('id_oreja', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->especie_id, function($query) {
                $query->where('especie_id', $this->especie_id);
            })
            ->when($this->raza_id, function($query) {
                $query->where('raza_id', $this->raza_id);
            })
            ->when($this->sexo, function($query) {
                $query->where('sexo', $this->sexo);
            })
            ->when($this->estado, function($query) {
                $query->where('estado', $this->estado);
            })
            ->latest();
    }

    public function render()
    {
        $razas = \App\Models\Raza::where('especie_id', $this->especie_id)->orderBy('nombre')->get();
        $animals = $this->getFilteredAnimalsQuery()->paginate(10);

        return view('livewire.admin.animal-list', compact('animals', 'razas'));
    }

    public function exportExcel()
    {
        $data = $this->getFilteredAnimalsQuery()->get();
        
        // Exportación simple a CSV (para evitar problemas de librerías faltantes si los hay)
        $filename = "animales_" . now()->format('Ymd_His') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID Animal', 'ID Oreja', 'Especie', 'Raza', 'Sexo', 'Nacimiento', 'Estado', 'Fase'];

        $callback = function() use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($data as $item) {
                fputcsv($file, [
                    $item->id_animal,
                    $item->id_oreja,
                    $item->especie->nombre,
                    $item->raza->nombre,
                    $item->sexo,
                    $item->fecha_nacimiento->format('d/m/Y'),
                    $item->estado,
                    $item->fase_reproductiva,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $animals = $this->getFilteredAnimalsQuery()->get();
        
        $pdf = Pdf::loadView('admin.animals.reports.pdf', compact('animals'));
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'reporte_animales.pdf');
    }
}
