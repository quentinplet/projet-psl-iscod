<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
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
            'price' => number_format($this->price, 2, ',', ' '), // Formatage du prix
            'quantity' => $this->quantity,
            'image_path' => $this->image_path, // Le chemin de l'image (ex: 'products/mon_image.jpg')

            // Inclure les relations si elles sont chargées
            'category' => new CategoryResource($this->whenLoaded('category')),
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),


            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}
