<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;

class DashboardController extends Controller
{
    public function stats()
    {
        $totalProductos = Producto::count();
        $totalCategorias = Categoria::count();

        $alertasStock = Producto::with(['marca', 'categoria'])
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->get();

        $graficoCategorias = Categoria::withCount('productos')->get();

        return response()->json([
            'total_productos' => $totalProductos,
            'total_categorias' => $totalCategorias,
            'alertas_stock' => $alertasStock,
            'grafico_categorias' => $graficoCategorias
        ], 200);
    }
}
