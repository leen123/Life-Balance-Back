<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Habit extends JsonResource
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
            'name' => $this->name,
            'ar_name' => $this->ar_name,
            'image' => $this->image,
            'points' => $this->points,
            'active_date' => $this->active_date,
            'repetition_type' => $this->repetition_type,
            'repetition_number' => $this->repetition_number - $this->doHabits,
            'date_type' => $this->date_type,
            'doHabits' => $this->doHabits,
            'op_points' => $this->op_points
        ];
    }
}
