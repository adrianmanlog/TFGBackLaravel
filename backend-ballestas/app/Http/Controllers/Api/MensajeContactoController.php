<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MensajeContacto;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MensajeContactoController extends Controller
{
    #[OA\Post(path: "/api/contacto", summary: "Envía un nuevo mensaje desde la web", tags: ["Contacto"])]
    #[OA\Response(response: 201, description: "Mensaje enviado correctamente")]
    public function store(Request $request)
    {
        // Validamos que nos manden lo necesario
        $request->validate([
            'nombre_cliente' => 'required|string|max:150',
            'telefono' => 'required|string|max:20',
            'mensaje' => 'required|string'
        ]);

        $mensaje = MensajeContacto::create($request->all());
        
        return response()->json(['message' => 'Mensaje recibido con éxito', 'data' => $mensaje], 201);
    }

    #[OA\Get(path: "/api/contacto", summary: "Obtiene todos los mensajes (Para el panel de Admin)", tags: ["Contacto"])]
    #[OA\Response(response: 200, description: "Operación exitosa")]
    public function index()
    {
        return response()->json(MensajeContacto::orderBy('fecha_envio', 'desc')->get(), 200);
    }
}