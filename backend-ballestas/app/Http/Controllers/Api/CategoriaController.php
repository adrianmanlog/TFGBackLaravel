<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/categorias",
     * tags={"Categorías"},
     * summary="Obtiene la lista de todas las categorías",
     * @OA\Response(
     * response=200,
     * description="Operación exitosa"
     * )
     * )
     */
    public function index()
    {
        return response()->json(Categoria::all(), 200);
    }
}