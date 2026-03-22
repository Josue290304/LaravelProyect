<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'precio',
        'impuesto'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'impuesto' => 'decimal:2'
    ];

    // Accessor para calcular el precio final
    public function getPrecioFinalAttribute()
    {
        return round($this->precio + ($this->precio * ($this->impuesto / 100)), 2);
    }

    // Agregar el precio final cuando se serializa a JSON
    protected $appends = ['precio_final'];
}