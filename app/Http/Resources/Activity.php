<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Activity extends JsonResource
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
            'section_id' => $this->section['id'],
            'name' =>  $this->name,
            'ar_name' => $this->ar_name,
            'image' => isset($this->image) ? asset('uploads/' . $this->image) : '',
            'image_name' => $this->image,
            'points' => $this->points
        ];
    }
}
