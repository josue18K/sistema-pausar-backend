<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLaboratorioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'sometimes|string|max:100',
            'carrera_id' => 'sometimes|exists:carreras,id',
            'responsable_id' => 'nullable|exists:users,id',
            'ubicacion' => 'nullable|string|max:150',
        ];
    }
}
