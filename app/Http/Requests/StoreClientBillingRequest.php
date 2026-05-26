<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientBillingRequest extends FormRequest
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
            'nombre_completo' => 'required|string|max:255',
            'numero_documento' => 'required|string|max:50',
            'direccion' => 'required|string|max:500',
            'ciudad' => 'required|string|max:100',
            'codigo_postal' => 'required|string|max:20',
            'pais' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ];
    }
}
