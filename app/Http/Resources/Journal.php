<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Journal extends JsonResource
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
            'iconType' => isset($this->iconType) ? asset('uploads/' . $this->iconType) : '',
            'nameType' => $this->nameType,
            'image' => isset($this->image) ? asset('uploads/' . $this->image) : '',
            'moodImage' => isset($this->moodImage) ? asset('uploads/' . $this->moodImage) : '',
            'title' => $this->title,
            'ar_title' => $this->ar_title,
            'subtitle' => $this->subtitle,
            'ar_subtitle' => $this->ar_subtitle,
            'description' => $this->description,
            'ar_description' => $this->ar_description,
            'date' => $this->date,

        ];
    }
}
