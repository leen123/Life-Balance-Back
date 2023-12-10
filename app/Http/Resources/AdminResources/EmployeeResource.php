<?php

namespace App\Http\Resources\AdminResources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'name'=> $this->name,
            'ar_name'=> $this->ar_name,
            'email'=> $this->email,
            'address'=> $this->address,
            'ar_address'=> $this->ar_address,
            'number'=> $this->number,
            'company_id'=> $this->company_id,
        ];
    }
}
