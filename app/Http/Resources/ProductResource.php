<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\BrandResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'brand' => new BrandResource($this->brand),
            'name' => $this->name,
            'sellingPrice' => $this->sellingPrice,
            'productNum' => $this->productNum,
        ];
    }
}