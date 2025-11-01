<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarreraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:100|unique:carreras,nombre',
            'abreviatura' => 'required|string|max:10|unique:carreras,abreviatura',
            'descripcion' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la carrera es obligatorio',
            'nombre.unique' => 'Ya existe una carrera con ese nombre',
            'abreviatura.required' => 'La abreviatura es obligatoria',
            'abreviatura.unique' => 'Ya existe una carrera con esa abreviatura',
        ];
    }
}
