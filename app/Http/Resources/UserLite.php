<?php

namespace App\Http\Resources;

use App\Enums\EmployeeType;
use App\Enums\UserType;
use App\Goals;
use App\Habits;
use App\Http\Resources\Reward as ResourcesReward;
use App\Reward;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLite extends JsonResource
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
            'is_active' => $this->is_active == 1,
            'plan' => $this->plan,
            'expiry_date' => $this->expiry_date,
            'roleId' => UserType::fromValue($this->type),
            'roleName' => UserType::fromValue($this->type)->description,
            'roles' => isset($this->roles) ? Role::collection($this->roles) : [],
            'permissions' => isset($this->permissions) ? Role::collection($this->permissions) : [],
        ];


        return $result;
    }
}
