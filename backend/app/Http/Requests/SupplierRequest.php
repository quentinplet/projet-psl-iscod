<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('suppliers')->ignore($this->supplier)],
            'phone' => 'required|string|max:20',
            'siret' => ['nullable', 'numeric', Rule::unique('suppliers')->ignore($this->supplier)],
            'description' => 'nullable|string',

            // Validation pour l'adresse liée
            'address' => ['required', 'array'], // L’adresse doit être un objet/tableau
            'address.street' => ['required', 'string', 'max:255'],
            'address.city' => ['required', 'string', 'max:100'],
            'address.postal_code' => ['required', 'string', 'max:20'],
            'address.additional_info' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
