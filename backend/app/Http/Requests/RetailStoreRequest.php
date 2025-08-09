<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RetailStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255'],


            // Validation pour l'adresse liée
            'address' => ['required', 'array'], // L’adresse doit être un objet/tableau
            'address.street' => ['required', 'string', 'max:255'],
            'address.city' => ['required', 'string', 'max:100'],
            'address.postal_code' => ['required', 'string', 'max:20'],
            'address.additional_info' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
