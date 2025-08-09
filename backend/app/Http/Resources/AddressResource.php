<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'street' => $this->street,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'additional_info' => $this->additional_info,
        ];
    }
}
