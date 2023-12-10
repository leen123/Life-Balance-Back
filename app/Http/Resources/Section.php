<?php

namespace App\Http\Resources;

use App\Utils\Helper;
use Illuminate\Http\Resources\Json\JsonResource;


class Section extends JsonResource
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
            'ar_name' => $this->ar_name,
            'code' => $this->code,
            'image' => isset($this->image) ? asset('uploads/' . $this->image) : '',
            'icon' => isset($this->icon) ? asset('uploads/' . $this->icon) : '',
            'description' => $this->description,
            'ar_description' => $this->ar_description,
        ];

        $activities = array();

        foreach($this->activity as $activity) {
            if ($activity->user_id == null || $activity->user_id == Helper::user()->id) {
                array_push($activities,new Activity($activity));
             }
        }

        $sections['activities'] = $activities;

        return $sections;
    }
}
