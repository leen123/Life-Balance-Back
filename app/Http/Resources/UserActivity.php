<?php

namespace App\Http\Resources;

use App\Http\Resources\Mood as ResourcesMood;
use App\Mood;
use Illuminate\Http\Resources\Json\JsonResource;

class UserActivity extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $activities = [
            'id' => $this->id,
            'section_id' => $this->section_id,
            'activity' => new Activity($this->activity),
        ];

        $mood = new ResourcesMood(Mood::find($this->mood_id));

        $activities['mood'] = $mood;
        return $activities;
    }
}
