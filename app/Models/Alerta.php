<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $tipo
 * @property string $mensaje
 * @property bool $leido
 * @property int $usuario_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User $usuario
 */
class Alerta extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'mensaje',
        'leido',
        'usuario_id',
    ];

    protected $casts = [
        'leido' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
