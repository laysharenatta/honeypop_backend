<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateImpuestoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('impuestos', 'nombre')->ignore($this->route('id')),
            ],
            'codigo' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('impuestos', 'codigo')->ignore($this->route('id')),
            ],
            'porcentaje' => 'sometimes|numeric|min:0|max:100',
            'descripcion' => 'sometimes|nullable|string|max:500',
            'activo' => 'sometimes|boolean',
        ];
    }
}
