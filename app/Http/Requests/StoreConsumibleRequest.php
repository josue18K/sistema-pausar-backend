<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsumibleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:100',
            'categoria_id' => 'required|exists:categorias,id',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'unidad_medida' => 'required|string|max:50',
            'ubicacion' => 'nullable|string|max:100',
            'responsable_id' => 'nullable|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del consumible es obligatorio',
            'categoria_id.required' => 'La categoría es obligatoria',
            'stock.required' => 'El stock es obligatorio',
            'stock_minimo.required' => 'El stock mínimo es obligatorio',
            'unidad_medida.required' => 'La unidad de medida es obligatoria',
        ];
    }
}
