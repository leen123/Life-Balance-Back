<?php

namespace App\Utils;

use App\Badges;
use App\Journal;
use App\Reward;
use App\User;
use App\UserBadges;
use App\UsersBadges;

class Helper
{

    public static function user()
    {
        return request()->user();
    }

    public static function cal_percentage($num_amount, $num_total)
    {
        if ($num_amount != 0) {
            $count1 = $num_amount / $num_total;
            $count2 = $count1 * 100;
            $count = number_format($count2, 0);
            return $count . ' %';
        } else {
            return '0 %';
        }
    }


    public static function cal_badges()
    {
        $badges = Badges::all();
        $user = User::find(self::user()->id);
        $is_open_new_badge = null;
        foreach ($badges as $key => $badge) {
            $user_badge = UsersBadges::where(['user_id' => $user->id , 'badges_id' => $badge->id])->first();
            if(is_null($user_badge)){
                if($badge->is_from_section == 1){
                    if($badge->section_id == 1){
                        if($user->social_points >= $badge->points){
                            $userBadge = new UsersBadges();
                            $userBadge-> user_id = $user->id;
                            $userBadge->badges_id = $badge->id;
                            $userBadge->is_grand_master = $badge->is_grand_master;
                            $userBadge->save();
                            $is_open_new_badge = $badge->id;
                        }
                    }
                    if($badge->section_id == 2){
                        if($user->career_points >= $badge->points){
                            $userBadge = new UsersBadges();
                            $userBadge-> user_id = $user->id;
                            $userBadge->badges_id = $badge->id;
                            $userBadge->is_grand_master = $badge->is_grand_master;
                            $userBadge->save();
                            $is_open_new_badge = $badge->id;
                        }
                    }
                    if($badge->section_id == 3){
                        if($user->learn_points >= $badge->points){
                            $userBadge = new UsersBadges();
                            $userBadge-> user_id = $user->id;
                            $userBadge->badges_id = $badge->id;
                            $userBadge->is_grand_master = $badge->is_grand_master;
                            $userBadge->save();
                            $is_open_new_badge = $badge->id;
                        }
                    }
                    if($badge->section_id == 4){
                        if($user->spirit_points >= $badge->points){
                            $userBadge = new UsersBadges();
                            $userBadge-> user_id = $user->id;
                            $userBadge->badges_id = $badge->id;
                            $userBadge->is_grand_master = $badge->is_grand_master;
                            $userBadge->save();
                            $is_open_new_badge = $badge->id;
                        }
                    }
                    if($badge->section_id == 5){
                        if($user->health_points >= $badge->points){
                            $userBadge = new UsersBadges();
                            $userBadge-> user_id = $user->id;
                            $userBadge->badges_id = $badge->id;
                            $userBadge->is_grand_master = $badge->is_grand_master;
                            $userBadge->save();
                            $is_open_new_badge = $badge->id;
                        }
                    }
                    if($badge->section_id == 6){
                        if($user->emotion_points >= $badge->points){
                            $userBadge = new UsersBadges();
                            $userBadge-> user_id = $user->id;
                            $userBadge->badges_id = $badge->id;
                            $userBadge->is_grand_master = $badge->is_grand_master;
                            $userBadge->save();
                            $is_open_new_badge = $badge->id;
                        }
                    }
                }else{
                    $user_grand_badge = UsersBadges::where(['user_id' => $user->id , 'badges_id' => $badge->id , 'is_grand_master' => 1])->get();
                    if(count($user_grand_badge) >= $badge->count_of_badges){
                        $userBadge = new UsersBadges();
                        $userBadge-> user_id = $user->id;
                        $userBadge->badges_id = $badge->id;
                        $userBadge->is_grand_master = $badge->is_grand_master;
                        $userBadge->save();
                        $is_open_new_badge = $badge->id;
                    }
                }
            }

        }

        return $is_open_new_badge;
    }


    public static function save_journal($iconType , $nameType , $image , $moodImage , $title , $subTitle , $description , $date){

        $journal = new Journal();
        $journal->user_id = self::user()->id;
        $journal->iconType = $iconType;
        $journal->nameType = $nameType;
        $journal->image = $image;
        $journal->moodImage = $moodImage;
        $journal->title = $title;
        $journal->subtitle = $subTitle;
        $journal->description = $description;
        $journal->date = $date;
        $journal->dayDate = $date->day;
        $journal->monthDate = $date->month;
        $journal->yearDate = $date->year;
        $journal->hoursDate = $date->hour;
        $journal->minutesDate = $date->minute;
        $journal->save();
    }

    public static function add_points_to_user($points , $section_id){

        $id = self::user()->id;
        $user = User::find($id);

        if(!is_null($user)){
            $user->points = $user->points + $points;
            if ($section_id == 1) {
                $user->social_points = $user->social_points + $points;
            } else if ($section_id == 2) {
                $user->career_points = $user->career_points + $points;
            } else if ($section_id == 3) {
                $user->learn_points = $user->learn_points + $points;
            } else if ($section_id == 4) {
                $user->spirit_points = $user->spirit_points + $points;
            } else if ($section_id == 5) {
                $user->health_points = $user->health_points + $points;
            } else {
                $user->emotion_points = $user->emotion_points + $points;
            }

            $user->save();
        }
    }


    public static function sub_points_to_user($points , $section_id){

        $id = self::user()->id;
        $user = User::find($id);

        if(!is_null($user)){
            $user->points = $user->points - $points;
            if ($section_id == 1) {
                $user->social_points = $user->social_points - $points;
            } else if ($section_id == 2) {
                $user->career_points = $user->career_points - $points;
            } else if ($section_id == 3) {
                $user->learn_points = $user->learn_points - $points;
            } else if ($section_id == 4) {
                $user->spirit_points = $user->spirit_points - $points;
            } else if ($section_id == 5) {
                $user->health_points = $user->health_points - $points;
            } else {
                $user->emotion_points = $user->emotion_points - $points;
            }

            $user->save();
        }
    }


    public static function cal_rewords()
    {
        $user = self::user();
        $user_points = $user->points;
        $rewords = Reward::all();
        $is_open_new_reword = null;
        foreach ($rewords as $key => $rewords) {
            if($user_points >= $rewords->quantity_points){
                $is_open_new_reword = $rewords->id;
            }
        }

        return $is_open_new_reword;
    }

}
