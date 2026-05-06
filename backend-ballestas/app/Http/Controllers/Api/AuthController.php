<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(path: "/api/registro", summary: "Registra un nuevo usuario normal (No Admin)", tags: ["Autenticación"])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(required: ["nombre", "email", "password"], properties: [new OA\Property(property: "nombre", type: "string", example: "Taller Hermanos"), new OA\Property(property: "email", type: "string", example: "taller@mail.com"), new OA\Property(property: "password", type: "string", example: "12345678")]))]
    #[OA\Response(response: 201, description: "Usuario registrado con éxito")]
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|string|email|max:150|unique:usuarios',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'es_admin' => false
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'access_token' => $token,
            'user' => $user
        ], 201);
    }

    #[OA\Post(path: "/api/login", summary: "Inicia sesión y obtiene el Token", tags: ["Autenticación"])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(required: ["email", "password"], properties: [new OA\Property(property: "email", type: "string", example: "admin@ballestasbeni.com"), new OA\Property(property: "password", type: "string", example: "admin123")]))]
    #[OA\Response(response: 200, description: "Login exitoso")]
    #[OA\Response(response: 401, description: "Credenciales incorrectas")]
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Bienvenido ' . $user->nombre,
            'access_token' => $token,
            'es_admin' => $user->es_admin,
            'user' => $user
        ], 200);
    }

    #[OA\Post(path: "/api/logout", summary: "Cierra sesión y destruye el Token", tags: ["Autenticación"], security: [["bearerAuth" => []]])]
    #[OA\Response(response: 200, description: "Sesión cerrada correctamente")]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
    }
}
