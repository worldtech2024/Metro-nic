<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\CountryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'country'            => new CountryResource($this->whenLoaded('country')),
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'image' => $this->image,
            'role' => $this->role,
            'token' => $this->token ?? null,
        ];
    }
}
