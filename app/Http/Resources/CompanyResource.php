<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'ar_name' => $this->ar_name,
            'description' => $this->description,
            'ar_description' => $this->description,
            'address' => $this->address,
            'ar_address' => $this->ar_address,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'long' => $this->long,
            'lat' => $this->lat,
            'social_media' => $this->social_media,
        ];
    }
}
