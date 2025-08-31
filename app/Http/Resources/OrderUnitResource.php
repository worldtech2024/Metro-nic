<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ProductUnitResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderUnitResource extends JsonResource
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
            'name' => $this->name,
            'subTotal' => $this->subTotal,
            'brandDiscount' => $this->brandDiscount,
            'totalBusbar' => $this->totalBusbar,
            'workWages' => $this->workWages,
            'workWagesPercentage' => $this->workWagesPercentage,
            'generalCost' => $this->generalCost,
            'generalCostPercentage' => $this->generalCostPercentage,
            'profitMargin' => $this->profitMargin,
            'profitMarginPercentage' => $this->profitMarginPercentage,
            'vat' => $this->vat,
            'vatPercentage' => $this->vatPercentage,
            'finalDiscount' => $this->finalDiscount,
            'totalPrice' => $this->totalPrice,
            'notes' => $this->notes,
            'busbares' => BusbarResource::collection($this->whenLoaded('busbares')) ?? null,
            'product_units' => ProductUnitResource::collection($this->whenLoaded('productUnits')),
        ];
    }
}