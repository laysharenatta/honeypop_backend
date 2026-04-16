<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAtencionTicketRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'asunto.required' => 'El asunto es requerido',
            'asunto.string' => 'El asunto debe ser texto',
            'asunto.max' => 'El asunto no puede exceder 255 caracteres',
            'mensaje.required' => 'El mensaje es requerido',
            'mensaje.string' => 'El mensaje debe ser texto',
        ];
    }
}
