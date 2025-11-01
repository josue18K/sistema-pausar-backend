<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'carrera_id',
        'responsable_id',
        'ubicacion',
    ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function movimientosOrigen()
    {
        return $this->hasMany(Movimiento::class, 'origen_id');
    }

    public function movimientosDestino()
    {
        return $this->hasMany(Movimiento::class, 'destino_id');
    }
}
