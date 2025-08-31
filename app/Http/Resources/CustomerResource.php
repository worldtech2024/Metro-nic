<?php
namespace App\Http\Resources;

use App\Http\Resources\CountryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'country'            => new CountryResource($this->whenLoaded('country')),
            'id'                 => $this->id,
            'name'               => $this->name,
            'email'              => $this->email,
            'phone'              => $this->phone,
            'address'            => $this->address,
            'address2'           => $this->address2,
            'city'               => $this->city,
            'street'              => $this->street,
            'neighborhood'       => $this->neighborhood,
            'zipCode'            => $this->zipCode,
            'buildingNumber'     => $this->buildingNumber,
            'additionalNumber'    => $this->additionalNumber,
            'taxNum'             => $this->taxNum,
            'commercialRegister' => $this->commercialRegister,

        ];
    }
}
