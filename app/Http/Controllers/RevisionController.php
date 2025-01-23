<?php

namespace App\Http\Controllers;

use App\Models\Revision;
use App\Models\Especie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RevisionController extends Controller
{
    public function indexTaxonomo()
    {
        try {
            $revisiones = Revision::with([
                'especie.imagenes',
                'especie.genero.familia.reino',
                'user'
            ])->orderBy('created_at', 'desc')->get();

            if ($revisiones->isEmpty()) {
                Log::info('No se encontraron revisiones');
            }

            return view('taxonomo', compact('revisiones'));
        } catch (\Exception $e) {
            Log::error('Error en indexTaxonomo: ' . $e->getMessage());
            return view('taxonomo', ['revisiones' => collect()])
                   ->with('error', 'Error al cargar las revisiones');
        }
    }

    public function store($especie_id)
    {
        try {
            $especie = Especie::findOrFail($especie_id);
            
            // Verificar si ya existe una revisión pendiente
            $revisionExistente = Revision::where('esp_id', $especie_id)
                                       ->where('estado', 'pendiente')
                                       ->first();
            
            if ($revisionExistente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una revisión pendiente para esta especie'
                ], 400);
            }

            $revision = new Revision();
            $revision->esp_id = $especie_id;
            $revision->user_id = Auth::id();
            $revision->estado = 'pendiente';
            $revision->save();

            return response()->json([
                'success' => true,
                'message' => 'Revisión creada exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error en store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la revisión: ' . $e->getMessage()
            ], 500);
        }
    }

    public function actualizarEstado(Request $request, $id)
{
    try {
        $request->validate([
            'estado' => 'required|in:pendiente,aprobado,rechazado',
            'comentario' => 'nullable|string'
        ]);

        $revision = Revision::findOrFail($id);
        $revision->estado = $request->estado;
        $revision->taxonomo_id = Auth::id();
        $revision->comentario = $request->comentario;
        $revision->save();

        if ($request->estado === 'aprobado') {
            $especie = Especie::find($revision->esp_id);
            if ($especie) {
                $especie->visible_observador = true; // Asegúrate de que esta columna exista en la tabla especies
                $especie->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente'
        ]);
    } catch (\Exception $e) {
        Log::error('Error en actualizarEstado: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el estado: ' . $e->getMessage()
        ], 500);
    }
}


    public function contarPendientes()
    {
        try {
            $count = Revision::where('estado', 'pendiente')->count();
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error('Error en contarPendientes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al contar revisiones pendientes'
            ], 500);
        }
    }

    public function filtrar(Request $request)
    {
        try {
            $query = Revision::with(['especie.imagenes', 'user']);

            if ($request->species) {
                $query->whereHas('especie', function($q) use ($request) {
                    $q->where('esp_nombre_comun', 'ILIKE', "%{$request->species}%");
                });
            }

            if ($request->status) {
                $query->where('estado', $request->status);
            }

            $revisiones = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $revisiones
            ]);
        } catch (\Exception $e) {
            Log::error('Error en filtrar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al filtrar revisiones'
            ], 500);
        }
    }
}