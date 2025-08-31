<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'id'      => $this->id,
            // 'name' => App::getLocale() === 'en' ? $this->name_en : $this->name_ar,
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'code'    => $this->code,
            'currency' => $this->currency

        ];
    }
}
