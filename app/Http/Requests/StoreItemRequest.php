<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codigo' => 'required|string|max:50|unique:items,codigo',
            'nombre' => 'required|string|max:120',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'laboratorio_id' => 'nullable|exists:laboratorios,id',
            'carrera_id' => 'nullable|exists:carreras,id',
            'estado' => 'required|in:activo,mantenimiento,baja',
            'fecha_adquisicion' => 'nullable|date',
            'responsable_id' => 'nullable|exists:users,id',
            'valor' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'El código es obligatorio',
            'codigo.unique' => 'Ya existe un ítem con ese código',
            'nombre.required' => 'El nombre del ítem es obligatorio',
            'categoria_id.required' => 'La categoría es obligatoria',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser: activo, mantenimiento o baja',
            'foto.image' => 'El archivo debe ser una imagen',
            'foto.max' => 'La imagen no debe superar 2MB',
        ];
    }
}
