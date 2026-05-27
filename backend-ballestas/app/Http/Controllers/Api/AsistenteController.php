<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AsistenteController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'mensaje' => 'required|string'
        ]);

        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        $promptContext = "Eres el asistente virtual experto de la tienda 'Ballestas Beni', especializada en repuestos para vehículos industriales. 
        Tu trabajo es ayudar a los clientes de forma amable, breve y profesional. Nunca inventes precios ni prometas stock. 
        Pregunta del cliente: " . $request->mensaje;

        $response = Http::post($url, [
            'contents' => [
                ['parts' => [['text' => $promptContext]]]
            ]
        ]);

        if ($response->successful()) {
            $respuestaIA = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'Error al leer la respuesta.';
            return response()->json(['respuesta' => $respuestaIA], 200);
        }

        return response()->json(['error' => 'Error al comunicarse con la IA'], 500);
    }
}
