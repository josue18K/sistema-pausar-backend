<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovimientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => 'nullable|exists:items,id',
            'consumible_id' => 'nullable|exists:consumibles,id',
            'tipo' => 'required|in:entrada,salida,traslado,baja,mantenimiento',
            'cantidad' => 'nullable|integer|min:1',
            'origen_id' => 'nullable|exists:laboratorios,id',
            'destino_id' => 'nullable|exists:laboratorios,id',
            'observaciones' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.required' => 'El tipo de movimiento es obligatorio',
            'tipo.in' => 'El tipo debe ser: entrada, salida, traslado, baja o mantenimiento',
        ];
    }
}
