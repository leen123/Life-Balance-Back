<?php

namespace App\Http\Resources\AdminResources;

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
        'name'=> $this->name,
        'ar_name'=> $this->ar_name,
        'description'=> $this->description,
        'ar_description'=> $this->ar_description,
        'code'=> $this->code,
        'type'=> $this->type,
        'value'=> $this->value,
        'max_uses'=> $this->max_uses,
        'active'=> $this->active,
        'QR'=> url($this->QR),
        'points'=> $this->points,
        'starts_at'=> $this->starts_at,
        'ends_at'=> $this->ends_at,
        'company_id'=> $this->company_id,
        ];
    }
}
