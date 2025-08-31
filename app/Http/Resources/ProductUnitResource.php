<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductUnitResource extends JsonResource
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
            // 'product_id' => $this->product_id,
            'product' => new ProductResource($this->product),
            'order_unit_id' => $this->order_unit_id,
            'quantity' => $this->quantity
        ];
    }
}