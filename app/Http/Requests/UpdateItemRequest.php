<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codigo' => 'sometimes|string|max:50|unique:items,codigo,' . $this->item->id,
            'nombre' => 'sometimes|string|max:120',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'sometimes|exists:categorias,id',
            'laboratorio_id' => 'nullable|exists:laboratorios,id',
            'carrera_id' => 'nullable|exists:carreras,id',
            'estado' => 'sometimes|in:activo,mantenimiento,baja',
            'fecha_adquisicion' => 'nullable|date',
            'responsable_id' => 'nullable|exists:users,id',
            'valor' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|max:2048',
        ];
    }
}
