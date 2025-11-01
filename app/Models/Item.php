<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'categoria_id',
        'laboratorio_id',
        'carrera_id',
        'estado',
        'fecha_adquisicion',
        'responsable_id',
        'valor',
        'foto',
    ];

    protected $casts = [
        'fecha_adquisicion' => 'date',
        'valor' => 'decimal:2',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function laboratorio()
    {
        return $this->belongsTo(Laboratorio::class);
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
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
