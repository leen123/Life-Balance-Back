<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'ar_name' => $this->ar_name,
            'en_name' => $this->name,
            'ar_description' => $this->ar_description,
            'en_description' => $this->description,
            'type' => $this->type,
            'value' => $this->value,
            'required_points' => $this->points,
            'quantity' => $this->max_uses,
            'expires_at' => $this->ends_at,
            'ar_name_company' => $this->company->ar_name,
            'en_name_company' => $this->company->name,
            'available_to_use' => auth()->user()->points >= $this->points ? true : false,
        ];
    }
}
