<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Especie;
use App\Models\Familia;
use App\Models\Genero;
use App\Models\Ubicacion;
use App\Models\Reino;
use App\Models\Registro;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Mapa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class EspecieController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validar los datos
        $request->validate([
            'nombre_comun' => 'required|string|max:50',
            'reino' => 'required|string|max:50',
            'familia' => 'required|string|max:50',
            'genero' => 'required|string|max:50',
            'nombre_cientifico' => 'required|string|max:50',
            'imagen' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'region' => 'required|string|max:100',
            'descripcion_ubicacion' => 'required|string|max:255',
        ]);

        // Manejo de la imagen
        $rutaImagen = $request->file('imagen')->store('imagenes', 'public');

        // Buscar o crear el Reino
        $reino = Reino::firstOrCreate(['reino_nombre' => $request->reino]);

        // Buscar o crear la Familia
        $familia = Familia::firstOrCreate([
            'fam_nombre' => $request->familia,
            'fam_reino_id' => $reino->reino_id,
        ]);

        // Buscar o crear el Género
        $genero = Genero::firstOrCreate([
            'gene_nombre' => $request->genero,
            'gene_fam_id' => $familia->fam_id,
        ]);

        // Crear la Especie
        $especie = Especie::create([
            'esp_gene_id' => $genero->gene_id,
            'esp_nombre_cientifico' => $request->nombre_cientifico,
            'esp_nombre_comun' => $request->nombre_comun,
            'esp_descripcion' => $request->descripcion,
            'esp_estado_valid' => false, // Inicialmente no está validada
        ]);

        // Seleccionar el mapa ya existente
        $mapaExistente = Mapa::first(); // Toma el primer mapa encontrado en la base de datos

        if (!$mapaExistente) {
            return redirect()->back()->withErrors(['error' => 'No hay mapas disponibles en la base de datos.']);
        }

        // Crear la Ubicación usando el mapa existente
        Ubicacion::create([
            'ubi_mapa_id' => $mapaExistente->mapa_id,
            'ubi_esp_id' => $especie->esp_id,
            'ubi_longitud' => $request->longitud,
            'ubi_latitud' => $request->latitud,
            'ubi_region' => $request->region,
            'ubi_descripcion' => $request->descripcion_ubicacion,
        ]);

        // Guardar la imagen asociada a la especie
        $especie->imagenes()->create([
            'img_ruta' => $rutaImagen,
            'img_descripcion' => 'Imagen subida automáticamente.',
        ]);

        // Registrar la especie en la tabla de registros
        Registro::create([
            'esp_id' => $especie->esp_id,
            'user_id' => $user->user_id,
            'regis_estado' => 'pendiente',
        ]);

        return redirect()->back()->with('success', 'Especie registrada con éxito.');
    }

    public function update(Request $request)
    {
        // Validar los datos enviados
        $request->validate([
            'esp_id' => 'required|exists:tax_especies,esp_id',
            'nombre_comun' => 'required|string|max:50',
            'reino' => 'required|string|max:50',
            'familia' => 'required|string|max:50',
            'genero' => 'required|string|max:50',
            'nombre_cientifico' => 'required|string|max:50',
            'descripcion' => 'required|string|max:255',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'region' => 'required|string|max:100',
            'descripcion_ubicacion' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Buscar la especie por ID
        $especie = Especie::findOrFail($request->esp_id);

        // Actualizar Reino
        $reino = Reino::firstOrCreate(['reino_nombre' => $request->reino]);

        // Actualizar Familia
        $familia = Familia::firstOrCreate([
            'fam_nombre' => $request->familia,
            'fam_reino_id' => $reino->reino_id,
        ]);

        // Actualizar Género
        $genero = Genero::firstOrCreate([
            'gene_nombre' => $request->genero,
            'gene_fam_id' => $familia->fam_id,
        ]);

        // Actualizar los datos de la especie
        $especie->update([
            'esp_gene_id' => $genero->gene_id,
            'esp_nombre_cientifico' => $request->nombre_cientifico,
            'esp_nombre_comun' => $request->nombre_comun,
            'esp_descripcion' => $request->descripcion,
        ]);

        // Actualizar o crear la ubicación
        $ubicacion = $especie->ubicaciones()->first();
        if ($ubicacion) {
            $ubicacion->update([
                'ubi_latitud' => $request->latitud,
                'ubi_longitud' => $request->longitud,
                'ubi_region' => $request->region,
                'ubi_descripcion' => $request->descripcion_ubicacion,
            ]);
        } else {
            $especie->ubicaciones()->create([
                'ubi_latitud' => $request->latitud,
                'ubi_longitud' => $request->longitud,
                'ubi_region' => $request->region,
                'ubi_descripcion' => $request->descripcion_ubicacion,
            ]);
        }

        // Actualizar la imagen si se proporciona
        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('imagenes', 'public');
            $imagen = $especie->imagenes()->first();
            if ($imagen) {
                // Eliminar la imagen anterior
                Storage::disk('public')->delete($imagen->img_ruta);
                $imagen->update([
                    'img_ruta' => $rutaImagen,
                    'img_descripcion' => 'Imagen actualizada.',
                ]);
            } else {
                $especie->imagenes()->create([
                    'img_ruta' => $rutaImagen,
                    'img_descripcion' => 'Nueva imagen cargada.',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Especie actualizada con éxito.');
    }

    /**
     * Obtener las especies visibles para el observador.
     */
    public function getVisibleEspecies()
    {
        $especies = Especie::where('esp_estado_valid', true)
            ->with(['imagenes', 'ubicaciones', 'genero.familia.reino'])
            ->get();

        return response()->json([
            'success' => true,
            'especies' => $especies,
        ]);
    }
}