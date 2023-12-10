<?php

namespace App\Http\Resources;

use App\Utils\Helper;
use Illuminate\Http\Resources\Json\JsonResource;


class SectionLite extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $sections = [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'image' => isset($this->image) ? asset('uploads/' . $this->image) : '',
            'icon' => isset($this->icon) ? asset('uploads/' . $this->icon) : '',
            'description' => $this->description,

        ];
        return $sections;
    }
}
