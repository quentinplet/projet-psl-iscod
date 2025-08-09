<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'siret' => $this->siret,
            'description' => $this->description,
            'address' => new AddressResource($this->whenLoaded('address')),
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'updated_at' => (new \DateTime($this->updated_at))->format('d-m-Y H:i:s'),
        ];
    }
}
