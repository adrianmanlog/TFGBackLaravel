<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProductoController extends Controller
{
    #[OA\Get(path: "/api/productos", summary: "Obtiene el catálogo completo", tags: ["Productos"])]
    #[OA\Response(response: 200, description: "Operación exitosa")]
    public function index()
    {
        return response()->json(Producto::with(['categoria', 'marca'])->get(), 200);
    }

    #[OA\Get(path: "/api/productos/destacados", summary: "Obtiene los productos destacados", tags: ["Productos"])]
    #[OA\Response(response: 200, description: "Operación exitosa")]
    public function destacados()
    {
        return response()->json(Producto::with(['categoria', 'marca'])->where('destacado', true)->get(), 200);
    }

    #[OA\Get(path: "/api/productos/{id}", summary: "Busca un producto por su ID", tags: ["Productos"])]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "ID del producto")]
    #[OA\Response(response: 200, description: "Operación exitosa")]
    #[OA\Response(response: 404, description: "Producto no encontrado")]
    public function show($id) {
        
        $producto = Producto::with(['categoria', 'marca'])->find($id);
        
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
        
        return response()->json($producto, 200);
    }

    #[OA\Post(path: "/api/productos", summary: "Crea un nuevo producto", tags: ["Productos"])]
    #[OA\Response(response: 201, description: "Producto creado")]
    public function store(Request $request)
    {
        // En un futuro, esto se protegerá solo para el Admin
        $producto = Producto::create($request->all());
        return response()->json($producto, 201);
    }

    #[OA\Put(path: "/api/productos/{id}", summary: "Actualiza un producto existente", tags: ["Productos"])]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "ID del producto")]
    #[OA\Response(response: 200, description: "Producto actualizado")]
    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);
        if (!$producto) return response()->json(['message' => 'Producto no encontrado'], 404);
        
        $producto->update($request->all());
        return response()->json($producto, 200);
    }

    #[OA\Delete(path: "/api/productos/{id}", summary: "Borra un producto", tags: ["Productos"])]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "ID del producto")]
    #[OA\Response(response: 200, description: "Producto eliminado")]
    public function destroy($id)
    {
        $producto = Producto::find($id);
        if (!$producto) return response()->json(['message' => 'Producto no encontrado'], 404);
        
        $producto->delete();
        return response()->json(['message' => 'Producto eliminado correctamente'], 200);
    }
}