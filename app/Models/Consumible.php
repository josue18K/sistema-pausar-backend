<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumible extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria_id',
        'stock',
        'stock_minimo',
        'unidad_medida',
        'ubicacion',
        'responsable_id',
    ];

    protected $casts = [
        'stock' => 'integer',
        'stock_minimo' => 'integer',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }
}
