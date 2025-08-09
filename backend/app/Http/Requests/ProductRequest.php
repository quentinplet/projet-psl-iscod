<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // N'oublie pas d'importer la classe Rule

class ProductRequest extends FormRequest
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
        // Récupère l'ID du produit depuis les paramètres de la route si nous sommes en mode 'update'
        // 'product' est le nom par défaut du paramètre pour un Product en route resource (ex: /products/{product})
        $productId = $this->route('product');

        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'reference' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'reference')->ignore($productId),
            ],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * Optionnel : Personnalise les messages d'erreur.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du produit est obligatoire.',
            'name.max' => 'Le nom ne doit pas dépasser :max caractères.',
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix doit être positif.',
            'reference.required' => 'La référence est obligatoire.',
            'reference.unique' => 'Cette référence est déjà utilisée.',
            'quantity.required' => 'La quantité est obligatoire.',
            'quantity.integer' => 'La quantité doit être un nombre entier.',
            'quantity.min' => 'La quantité ne peut pas être négative.',
            'category_id.required' => 'La catégorie est obligatoire.',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'supplier_id.required' => 'Le fournisseur est obligatoire.',
            'supplier_id.exists' => 'Le fournisseur sélectionné n\'existe pas.',
            'slug.unique' => 'Ce slug est déjà utilisé.',
            // 'image.image' => 'Le fichier doit être une image.',
            // 'image.mimes' => 'Le format de l\'image doit être JPEG, PNG, JPG, GIF ou WEBP.',
            // 'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ];
    }
}
