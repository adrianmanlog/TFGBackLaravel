<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    public $timestamps = false; // Como no creamos created_at en SQL, lo apagamos
    protected $fillable = ['nombre', 'icono', 'descripcion'];

    // Relación: Una categoría tiene muchos productos
    public function productos() {
        return $this->hasMany(Producto::class, 'categoria_id');
    }
}