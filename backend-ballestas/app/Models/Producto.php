<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    public $timestamps = false;
    protected $fillable = ['categoria_id', 'marca_id', 'nombre', 'descripcion', 'precio', 'stock', 'imagen_url', 'destacado'];

    // Relaciones: Un producto pertenece a una categoría y a una marca
    public function categoria() {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function marca() {
        return $this->belongsTo(Marca::class, 'marca_id');
    }
}