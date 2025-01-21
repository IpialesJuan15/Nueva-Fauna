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
use Illuminate\Support\Facades\Redirect;

class EspecieController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

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

        $rutaImagen = $request->file('imagen')->store('imagenes', 'public');

        $reino = Reino::firstOrCreate(['reino_nombre' => $request->reino]);
        $familia = Familia::firstOrCreate([
            'fam_nombre' => $request->familia,
            'fam_reino_id' => $reino->reino_id,
        ]);
        $genero = Genero::firstOrCreate([
            'gene_nombre' => $request->genero,
            'gene_fam_id' => $familia->fam_id,
        ]);

        $especie = Especie::create([
            'esp_gene_id' => $genero->gene_id,
            'esp_nombre_cientifico' => $request->nombre_cientifico,
            'esp_nombre_comun' => $request->nombre_comun,
            'esp_descripcion' => $request->descripcion,
            'esp_estado_valid' => false,
        ]);

        Ubicacion::create([
            'ubi_esp_id' => $especie->esp_id,
            'ubi_longitud' => $request->longitud,
            'ubi_latitud' => $request->latitud,
            'ubi_region' => $request->region,
            'ubi_descripcion' => $request->descripcion_ubicacion,
        ]);

        $especie->imagenes()->create([
            'img_ruta' => $rutaImagen,
            'img_descripcion' => 'Imagen subida automáticamente.',
        ]);

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

    public function search(Request $request)
    {
        // Validar que se reciba un parámetro para buscar
        $request->validate([
            'nombre_comun' => 'required|string|max:50',
        ]);

        // Buscar la especie por el nombre común
        $especie = Especie::with(['genero.familia.reino', 'ubicaciones', 'imagenes'])
            ->where('esp_nombre_comun', $request->nombre_comun)
            ->first();

        // Verificar si la especie existe
        if ($especie) {
            // Preparar los datos de la respuesta
            $ubicacion = $especie->ubicaciones->first(); // Tomar la primera ubicación relacionada
            $imagen = $especie->imagenes->first(); // Tomar la primera imagen relacionada

            return response()->json([
                'esp_id' => $especie->esp_id,
                'esp_nombre_comun' => $especie->esp_nombre_comun,
                'esp_nombre_cientifico' => $especie->esp_nombre_cientifico,
                'esp_descripcion' => $especie->esp_descripcion,
                'reino_nombre' => $especie->genero->familia->reino->reino_nombre,
                'fam_nombre' => $especie->genero->familia->fam_nombre,
                'gene_nombre' => $especie->genero->gene_nombre,
                'latitud' => $ubicacion ? $ubicacion->ubi_latitud : null,
                'longitud' => $ubicacion ? $ubicacion->ubi_longitud : null,
                'region' => $ubicacion ? $ubicacion->ubi_region : null,
                'descripcion_ubicacion' => $ubicacion ? $ubicacion->ubi_descripcion : null,
                'imagen_url' => $imagen ? asset('storage/' . $imagen->img_ruta) : null,
            ]);
        }

        // Si no se encuentra la especie, devolver error
        return response()->json(['message' => 'Especie no encontrada'], 404);
    }
    public function index()
    {
        $especies = Especie::with(['genero.familia.reino', 'ubicaciones', 'imagenes'])->get();

        return response()->json($especies);
    }


    public function destroy($id)
    {
        $especie = Especie::findOrFail($id);

        // Eliminar ubicaciones relacionadas
        $especie->ubicaciones()->delete();

        // Eliminar imágenes relacionadas
        $especie->imagenes()->delete();

        // Eliminar la especie
        $especie->delete();

        return response()->json(['message' => 'Registro eliminado con éxito']);
    }
}
