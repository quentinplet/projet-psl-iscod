<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'cart_items' => ['required', 'array', 'min:1'], // Doit être un tableau et non vide
            'cart_items.*.product_id' => ['required', 'integer', 'exists:products,id'], // Chaque item doit avoir un product_id valide qui existe dans la table 'products'
            'cart_items.*.quantity' => ['required', 'integer', 'min:1'], // Chaque item doit avoir une quantité > 0
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'cart_items.required' => 'Le panier est vide.',
            'cart_items.array' => 'Le format du panier est invalide.',
            'cart_items.min' => 'Le panier doit contenir au moins un article.',
            'cart_items.*.product_id.required' => 'L\'ID du produit est manquant pour un article du panier.',
            'cart_items.*.product_id.integer' => 'L\'ID du produit doit être un nombre entier.',
            'cart_items.*.product_id.exists' => 'Un produit dans le panier n\'existe pas.',
            'cart_items.*.quantity.required' => 'La quantité est manquante pour un article du panier.',
            'cart_items.*.quantity.integer' => 'La quantité doit être un nombre entier.',
            'cart_items.*.quantity.min' => 'La quantité doit être d\'au moins 1 pour un article du panier.',
        ];
    }
}
