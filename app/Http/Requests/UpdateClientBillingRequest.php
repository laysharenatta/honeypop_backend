<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientBillingRequest extends FormRequest
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
            'nombre_completo' => ['sometimes', 'string', 'max:255'],
            'numero_documento' => ['sometimes', 'string', 'max:50'],
            'direccion' => ['sometimes', 'string', 'max:500'],
            'ciudad' => ['sometimes', 'string', 'max:100'],
            'codigo_postal' => ['sometimes', 'string', 'max:20'],
            'pais' => ['sometimes', 'string', 'max:100'],
            'telefono' => ['sometimes', 'string', 'max:20'],
            'email' => ['sometimes', 'email', 'max:255'],
        ];
    }
}
