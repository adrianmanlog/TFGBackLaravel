<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CategoriaController extends Controller
{
    #[OA\Get(path: "/api/categorias", summary: "Obtiene la lista de todas las categorías", tags: ["Categorías"])]
    #[OA\Response(response: 200, description: "Operación exitosa")]
    public function index()
    {
        return response()->json(Categoria::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:100']);
        $categoria = Categoria::create($request->all());
        return response()->json($categoria, 201);
    }

    #[OA\Put(path: "/api/categorias/{id}", summary: "Actualiza una categoría existente", tags: ["Categorías"])]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "ID de la categoría")]
    #[OA\Response(response: 200, description: "Categoría actualizada")]
    public function update(Request $request, $id)
    {
        $categoria = Categoria::find($id);
        if (!$categoria) return response()->json(['message' => 'Categoría no encontrada'], 404);

        $request->validate(['nombre' => 'required|string|max:100']);
        $categoria->update($request->all());
        return response()->json($categoria, 200);
    }

    #[OA\Delete(path: "/api/categorias/{id}", summary: "Borra una categoría", tags: ["Categorías"])]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "ID de la categoría")]
    #[OA\Response(response: 200, description: "Categoría eliminada")]
    public function destroy($id)
    {
        $categoria = Categoria::find($id);
        if (!$categoria) return response()->json(['message' => 'Categoría no encontrada'], 404);

        $categoria->delete();
        return response()->json(['message' => 'Categoría eliminada correctamente'], 200);
    }
}
