@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Revisiones Pendientes</h2>
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre Común</th>
                    <th>Reino</th>
                    <th>Familia</th>
                    <th>Género</th>
                    <th>Nombre Científico</th>
                    <th>Investigador</th>
                    <th>Fecha de Envío</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($revisiones as $revision)
                    <tr>
                        <td>{{ $revision->especie->esp_nombre_comun }}</td>
                        <td>{{ $revision->especie->genero->familia->reino->reino_nombre }}</td>
                        <td>{{ $revision->especie->genero->familia->fam_nombre }}</td>
                        <td>{{ $revision->especie->genero->gene_nombre }}</td>
                        <td>{{ $revision->especie->esp_nombre_cientifico }}</td>
                        <td>{{ $revision->user->name }}</td>
                        <td>{{ $revision->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <button class="btn btn-success btn-sm">Aprobar</button>
                            <button class="btn btn-danger btn-sm">Rechazar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay revisiones pendientes</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection