<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // <--- ¡Asegúrate de que pone User!

class Pedido extends Model
{
    protected $table = 'pedidos';
    public $timestamps = false; 
    protected $fillable = ['usuario_id', 'total', 'estado'];

    public function lineas() {
        return $this->hasMany(LineaPedido::class, 'pedido_id');
    }

    public function usuario() {
        return $this->belongsTo(User::class, 'usuario_id'); // <--- ¡Asegúrate de que pone User::class!
    }
}