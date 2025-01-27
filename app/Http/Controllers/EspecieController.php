<?php

namespace App\Http\Controllers;

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
use App\Models\Validacion;
use Illuminate\Support\Facades\Log; // Agregar esta línea

class EspecieController extends Controller
{
    public function create()
    {
        $reinos = DB::table('tax_reinos')->pluck('reino_nombre'); // Nombres de los reinos
        $familias = DB::table('tax_familias')->pluck('fam_nombre'); // Nombres de las familias
        $generos = DB::table('tax_generos')->pluck('gene_nombre'); // Nombres de los géneros

        return view('FormInvest', compact('reinos', 'familias', 'generos')); // Pasar datos a la vista
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validar los datos
        $request->validate([
            'nombre_comun' => 'required|string|max:50',
            'reino' => 'required|string|exists:tax_reinos,reino_nombre',
            'familia' => 'required|string|max:50',
            'genero' => 'required|string|max:50',
            'nombre_cientifico' => 'required|string|max:50',
            'imagen' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'region' => 'required|string|max:100',
            'descripcion_ubicacion' => 'required|string|max:255',
        ]);

        // Buscar el ID del reino seleccionado
        $reino = DB::table('tax_reinos')->where('reino_nombre', $request->reino)->first();

        // Manejo de la imagen
        $rutaImagen = $request->file('imagen')->store('imagenes', 'public');

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
            'esp_estado_valid' => false,
        ]);

        // Crear la Ubicación
        Ubicacion::create([
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

    public function getFamiliasGeneros(Request $request)
{
    // Obtener el nombre del Reino desde la solicitud
    $reino = $request->query('reino');

    // Filtrar las Familias relacionadas con el Reino seleccionado
    $familias = DB::table('tax_familias')
        ->join('tax_reinos', 'tax_familias.fam_reino_id', '=', 'tax_reinos.reino_id')
        ->where('tax_reinos.reino_nombre', $reino)
        ->pluck('fam_nombre');

    // Filtrar los Géneros relacionados con las Familias del Reino seleccionado
    $generos = DB::table('tax_generos')
        ->join('tax_familias', 'tax_generos.gene_fam_id', '=', 'tax_familias.fam_id')
        ->join('tax_reinos', 'tax_familias.fam_reino_id', '=', 'tax_reinos.reino_id')
        ->where('tax_reinos.reino_nombre', $reino)
        ->pluck('gene_nombre');

    // Devolver los resultados en formato JSON
    return response()->json([
        'familias' => $familias,
        'generos' => $generos,
    ]);
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

        DB::statement("SET app.current_user_id = " . auth()->id());
        // Buscar la especie por ID
        $especie = Especie::findOrFail($request->esp_id);

        if (!$especie || !$this->checkPermission($especie->esp_id, 'edit')) {
            return redirect()->route('especies.index')
                ->with('error', 'No tienes permiso para actualizar esta especie.');
        }

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
        try {
            DB::beginTransaction();

            // Verificar si la especie existe y validar permisos
            $especie = Especie::with(['ubicaciones', 'imagenes', 'registros'])->findOrFail($id);

            if (!$this->checkPermission($especie->esp_id, 'delete')) {
                return response()->json([
                    'message' => 'No tienes permiso para eliminar esta especie.'
                ], 403);
            }

            // Eliminar ubicaciones relacionadas
            foreach ($especie->ubicaciones as $ubicacion) {
                $ubicacion->delete();
            }

            // Eliminar imágenes relacionadas y sus archivos
            foreach ($especie->imagenes as $imagen) {
                Storage::disk('public')->delete($imagen->img_ruta); // Borra el archivo físicamente
                $imagen->delete();
            }

            // Eliminar registros relacionados
            foreach ($especie->registros as $registro) {
                $registro->delete();
            }

            // Eliminar la especie
            $especie->delete();

            DB::commit();
            return response()->json(['message' => 'Registro eliminado con éxito.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar el registro: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al eliminar el registro.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function validarEspecie(Request $request, $id)
    {
        try {
            // Validar la especie existe
            $especie = Especie::findOrFail($id);
            
            // Actualizar el estado
            $especie->update([
                'esp_estado_valid' => ($request->estado === 'Aprobado') ? 2 : 3
            ]);
    
            // Buscar el registro asociado
            $registro = Registro::where('esp_id', $id)->first();
            if ($registro) {
                $registro->update([
                    'regis_estado' => $request->estado
                ]);
            }
    
            return response()->json([
                'success' => true,
                'message' => "Especie {$request->estado} correctamente"
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error en validarEspecie: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la validación'
            ], 500);
        }
    }

private function convertirEstadoANumero($estado)
{
    switch ($estado) {
        case 'Aprobado':
            return 2;
        case 'Rechazado':
            return 3;
        default:
            return 1; // Pendiente
    }
}

// Agregar este método para obtener el nombre del estado
public function getEstadoNombre($estadoNumerico)
{
    switch ($estadoNumerico) {
        case 1:
            return 'Pendiente';
        case 2:
            return 'Aprobado';
        case 3:
            return 'Rechazado';
        default:
            return 'Desconocido';
    }
}

private function checkPermission($especie_id, $permission)
{
    return DB::selectOne(
        'SELECT check_especie_permissions(?, ?, ?) as result',
        [Auth::id(), $especie_id, $permission]
    )->result;
}

    public function obtenerEspeciesAprobadas()
    {
        try {
            // Obtener solo especies con estado_valid = 2 (Aprobado)
            $especies = Especie::where('esp_estado_valid', 2)
                ->with(['imagenes', 'ubicaciones', 'genero.familia.reino'])
                ->get();
    
            return response()->json([
                'success' => true,
                'especies' => $especies
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las especies: ' . $e->getMessage()
            ], 500);
        }
    }
}
