<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Animales</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .badge { padding: 3px 6px; border-radius: 4px; font-size: 10px; color: white; }
        .bg-success { background-color: #28a745; }
        .bg-primary { background-color: #007bff; }
        .bg-danger { background-color: #dc3545; }
        .bg-warning { background-color: #ffc107; color: black; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SISCONINT - Inventario de Animales</h1>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Animal</th>
                <th>Especie</th>
                <th>Raza</th>
                <th>Sexo</th>
                <th>Nacimiento</th>
                <th>Estado</th>
                <th>Fase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($animals as $animal)
            <tr>
                <td>{{ $animal->id_animal }}</td>
                <td>{{ $animal->especie->nombre }}</td>
                <td>{{ $animal->raza->nombre }}</td>
                <td>{{ $animal->sexo == 'M' ? 'Macho' : 'Hembra' }}</td>
                <td>{{ $animal->fecha_nacimiento->format('d/m/Y') }}</td>
                <td>{{ ucfirst($animal->estado) }}</td>
                <td>{{ $animal->fase_reproductiva ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
