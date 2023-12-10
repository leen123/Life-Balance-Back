<?php

namespace App\Http\Resources;

use App\Badges;
use App\Enums\EmployeeType;
use App\Enums\UserType;
use App\Goals;
use App\Habits;
use App\Http\Resources\Badges as ResourcesBadges;
use App\Http\Resources\Reward as ResourcesReward;
use App\Reward;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'image' => isset($this->image) ? asset('uploads/' . $this->image) : '',
            'points' => $this->points,
            'email' => $this->email,
            'roleId' => UserType::fromValue($this->type),
            'roleName' => UserType::fromValue($this->type)->description,
            'roles' => isset($this->roles) ? Role::collection($this->roles) : [],
            'permissions' => isset($this->permissions) ? Role::collection($this->permissions) : [],
        ];

        $habits = Habits::where('user_id', '<=', $this->id)->count();
        $goals = Goals::where('user_id', '<=', $this->id)->count();

        $result['total_habits'] = $habits;
        $result['total_goals'] = $goals;

        $rewards = Reward::all();
        $result['rewards'] = ResourcesReward::collection($rewards);

        $badges = Badges::all();
        $request['badges'] = ResourcesBadges::collection($badges);

        return $result;
    }
}
