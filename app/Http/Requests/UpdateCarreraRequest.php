<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarreraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'sometimes|string|max:100|unique:carreras,nombre,' . $this->carrera->id,
            'abreviatura' => 'sometimes|string|max:10|unique:carreras,abreviatura,' . $this->carrera->id,
            'descripcion' => 'nullable|string',
        ];
    }
}
