<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusbarResource extends JsonResource
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
            'order_id' => $this->order_id,
            'amp' => $this->amp,
            'quantity' => $this->quantity,
            'priceForMeter' => $this->priceForMeter,
            'PL' => $this->PL,
            'UP' => $this->UP
        ];  
    }
}