<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use OpenApi\Attributes as OA;

class MarcaController extends Controller
{
    #[OA\Get(path: "/api/marcas", summary: "Obtiene todas las marcas", tags: ["Marcas"])]
    #[OA\Response(response: 200, description: "Operación exitosa")]
    public function index()
    {
        return response()->json(Marca::all(), 200);
    }
}