<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'reference' => $this->reference,
            // 1. Envoyez le prix comme un nombre (float)
            'price' => (float) $this->price, // Cast en float pour s'assurer que c'est un nombre JavaScript
            // 2. Ajoutez une propriété séparée pour la version formatée (string)
            'formatted_price' => number_format($this->price, 2, ',', ' '), // "189,99"
            'quantity' => $this->quantity,

            'image_path' => $this->image_path, // Chemin relatif de l'image stocké en BD
            'image_mime' => $this->image_mime, // Type MIME de l'image
            'image_size' => $this->image_size,

            // Inclure les relations si elles sont chargées
            'category' => new CategoryResource($this->whenLoaded('category')),
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),


            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}
