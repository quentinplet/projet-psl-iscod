<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
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
            'status' => $this->status,
            'total_price' => $this->total_price,
            'number_of_items' => $this->items->sum('quantity'),
            'user' => new UserResource($this->user),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'expected_delivery_date' => $this->expected_delivery_date ? Carbon::parse($this->expected_delivery_date)->format('d-m-Y') : null,
            'delivered_at' => $this->delivered_at ? Carbon::parse($this->delivered_at)->format('d-m-Y H:i:s') : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
