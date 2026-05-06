<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(title: "API REST - Ballestas Beni", version: "1.0.0", description: "Documentación oficial de la API para el TFG")]
#[OA\Server(url: "http://localhost:8000", description: "Servidor Local")]

#[OA\SecurityScheme(securityScheme: "bearerAuth", type: "http", scheme: "bearer", bearerFormat: "JWT")]
abstract class Controller {}
