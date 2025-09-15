<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'name' => App::getLocale() === 'en' ? $this->name_en : $this->name_ar,
            // 'id'      => $this->id,
            // 'name_en' => $this->name_en,
            // 'name_ar' => $this->name_ar,
            // 'code'    => $this->code,
            // 'currency' => $this->currency
            'id'           => $this->id,
            'name_en'      => $this->name_en,
            'name_ar'      => $this->name_ar,
            'code'         => $this->code,
            'currency'     => $this->currency,
            'address'      => $this->address,
            'phone'        => $this->phone,
            'mobile'       => $this->mobile,
            'email'        => $this->email,
            'faxNumber'    => $this->faxNumber,
            'bankName'     => $this->bankName,
            'IBAN'         => $this->IBAN,
            'accountNumber' => $this->accountNumber,
            'accountName' => $this->accountName,
            "workWages"    => (int) $this->workWages,
            "generalCost"  => (int) $this->generalCost,
            "profitMargin" => (int) $this->profitMargin,
            "tax"          => (int) $this->tax,
            "wirePrice"    => (int) $this->wirePrice,
            'status'       => $this->status,

        ];
    }
}
