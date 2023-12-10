<?php

namespace App\Http\Resources\AdminResources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
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
            'title'=> $this->title,
            'ar_title'=> $this->ar_title,
            'description'=> $this->description,
            'ar_description'=> $this->ar_description,
            'url'=> $this->url,
            'image'=> $this->image == null ? null :asset('uploads/' . $this->image),
            'video'=> $this->video == null ? null :asset('uploads/' . $this->video),
            'starts_at'=> $this->starts_at,
            'ends_at'=> $this->ends_at,
            'active'=> $this->active,
            'company_id'=> $this->company_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
