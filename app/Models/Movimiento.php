<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'consumible_id',
        'tipo',
        'cantidad',
        'origen_id',
        'destino_id',
        'observaciones',
        'usuario_id',
    ];

    protected $casts = [
        'cantidad' => 'integer',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function consumible()
    {
        return $this->belongsTo(Consumible::class);
    }

    public function origen()
    {
        return $this->belongsTo(Laboratorio::class, 'origen_id');
    }

    public function destino()
    {
        return $this->belongsTo(Laboratorio::class, 'destino_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
