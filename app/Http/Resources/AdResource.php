<?php

namespace App\Http\Resources;

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
            'id' => $this->id,
            'title' => $this->title,
            'ar_title' => $this->ar_title,
            'image' => url($this->image),
            'video' => url($this->video),
            'company' => new CompanyResource($this->company),
        ];
    }
}
