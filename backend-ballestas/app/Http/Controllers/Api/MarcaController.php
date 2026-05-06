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
}
