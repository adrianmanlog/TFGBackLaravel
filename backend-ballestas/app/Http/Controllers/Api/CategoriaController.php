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

    public function store(Request $request) {
    $request->validate(['nombre' => 'required|string|max:100']);
    $categoria = Categoria::create($request->all());
    return response()->json($categoria, 201);
}
}