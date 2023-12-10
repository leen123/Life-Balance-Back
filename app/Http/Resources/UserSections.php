<?php

namespace App\Http\Resources;

use App\Enums\EmployeeType;
use App\Enums\UserType;
use App\Goals;
use App\Habits;
use App\Http\Resources\Reward as ResourcesReward;
use App\Http\Resources\SectionLite as SectionResource;
use App\Reward;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Section;

class UserSections extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'userName' => $this->userName,
            'points' => $this->points,
            'social_points' => $this->social_points,
            'career_points' => $this->career_points,
            'learn_points' => $this->learn_points,
            'spirit_points' => $this->spirit_points,
            'health_points' => $this->health_points,
            'emotion_points' => $this->emotion_points,
            'sections' => SectionResource::collection(Section::all())
        ];
        return $result;
    }
}
