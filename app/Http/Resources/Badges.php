<?php

namespace App\Http\Resources;


use App\UsersBadges;
use App\Utils\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class Badges extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $user = Helper::user();

        $badge = [
            'id' => $this->id,
            'name' => $this->name,
            'ar_name' => $this->ar_name,
            'image' => isset($this->image) ? asset('uploads/' . $this->image) : '',
            'is_grand_master' => $this->is_grand_master == 1,
            'points' => $this->points,
            'is_from_section' => $this->is_from_section ,
            'count_of_badges' => $this->count_of_badges,
            'section_id' => $this->section_id,
            'badges_id' => $this->badges_id,
        ];

        $user_badge = UsersBadges::where(['user_id' => $user->id , 'badges_id' => $this->id])->first();

        $badge['is_open'] = !is_null($user_badge);
        return $badge;
    }
}
