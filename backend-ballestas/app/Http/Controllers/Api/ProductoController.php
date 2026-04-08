<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/productos",
     * tags={"Productos"},
     * summary="Obtiene el catálogo completo de productos",
     * @OA\Response(
     * response=200,
     * description="Operación exitosa"
     * )
     * )
     */
    public function index()
    {
        $productos = Producto::with(['categoria', 'marca'])->get();
        return response()->json($productos, 200);
    }

    /**
     * @OA\Get(
     * path="/api/productos/destacados",
     * tags={"Productos"},
     * summary="Obtiene solo los productos marcados como destacados",
     * @OA\Response(
     * response=200,
     * description="Operación exitosa"
     * )
     * )
     */
    public function destacados()
    {
        $destacados = Producto::with(['categoria', 'marca'])
                              ->where('destacado', true)
                              ->get();
        return response()->json($destacados, 200);
    }
}