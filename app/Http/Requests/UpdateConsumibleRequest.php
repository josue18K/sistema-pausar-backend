<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConsumibleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'sometimes|string|max:100',
            'categoria_id' => 'sometimes|exists:categorias,id',
            'stock' => 'sometimes|integer|min:0',
            'stock_minimo' => 'sometimes|integer|min:0',
            'unidad_medida' => 'sometimes|string|max:50',
            'ubicacion' => 'nullable|string|max:100',
            'responsable_id' => 'nullable|exists:users,id',
        ];
    }
}
