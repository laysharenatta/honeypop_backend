<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImpuestoRequest extends FormRequest
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
            'nombre' => 'required|string|max:255|unique:impuestos,nombre',
            'codigo' => 'required|string|max:50|unique:impuestos,codigo',
            'porcentaje' => 'required|numeric|min:0|max:100',
            'descripcion' => 'nullable|string|max:500',
            'activo' => 'nullable|boolean',
        ];
    }
}
