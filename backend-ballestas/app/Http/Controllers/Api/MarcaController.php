<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MarcaController extends Controller
{
    #[OA\Get(path: "/api/marcas", summary: "Obtiene todas las marcas", tags: ["Marcas"])]
    #[OA\Response(response: 200, description: "Operación exitosa")]
    public function index()
    {
        return response()->json(Marca::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:100']);
        $marca = Marca::create($request->all());
        return response()->json($marca, 201);
    }

    #[OA\Put(path: "/api/marcas/{id}", summary: "Actualiza una marca existente", tags: ["Marcas"])]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "ID de la marca")]
    #[OA\Response(response: 200, description: "Marca actualizada")]
    public function update(Request $request, $id)
    {
        $marca = Marca::find($id);
        if (!$marca) return response()->json(['message' => 'Marca no encontrada'], 404);

        $request->validate(['nombre' => 'required|string|max:100']);
        $marca->update($request->all());
        return response()->json($marca, 200);
    }

    #[OA\Delete(path: "/api/marcas/{id}", summary: "Borra una marca", tags: ["Marcas"])]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "ID de la marca")]
    #[OA\Response(response: 200, description: "Marca eliminada")]
    public function destroy($id)
    {
        $marca = Marca::find($id);
        if (!$marca) return response()->json(['message' => 'Marca no encontrada'], 404);

        $marca->delete();
        return response()->json(['message' => 'Marca eliminada correctamente'], 200);
    }
}
