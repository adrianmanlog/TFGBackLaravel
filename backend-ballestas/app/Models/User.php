<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // 1. Apuntamos a la tabla que creamos en PostgreSQL
    protected $table = 'usuarios';
    
    // 2. Apagamos los timestamps automáticos (usamos fecha_registro)
    public $timestamps = false;

    // 3. Campos que se pueden llenar
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'es_admin',
    ];

    // 4. Ocultar la contraseña para que nunca viaje en el JSON de respuesta
    protected $hidden = [
        'password',
    ];
}