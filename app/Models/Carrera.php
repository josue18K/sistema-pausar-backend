<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'abreviatura',
        'descripcion',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function laboratorios()
    {
        return $this->hasMany(Laboratorio::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
