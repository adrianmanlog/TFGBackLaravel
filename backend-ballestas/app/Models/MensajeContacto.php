<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MensajeContacto extends Model
{
    protected $table = 'mensajes_contacto';
    public $timestamps = false; // La fecha ya la gestionamos en PostgreSQL
    protected $fillable = ['nombre_cliente', 'telefono', 'mensaje', 'fecha_envio', 'leido'];
}
