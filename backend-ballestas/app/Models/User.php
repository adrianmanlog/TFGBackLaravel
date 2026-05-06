<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    public $timestamps = false;

    // 3. Campos que se pueden llenar
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'es_admin',
    ];

    protected $hidden = [
        'password',
    ];
}
