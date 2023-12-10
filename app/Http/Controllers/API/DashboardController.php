<?php

namespace App\Http\Controllers\API;

use App\Goals;
use App\Habits;
use App\Utils\Helper;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Activity as ActivityResource;
use App\Http\Resources\Goal;
use App\Http\Resources\Mood as ResourcesMood;
use App\Http\Resources\SectionLite;
use App\Http\Resources\UserSections as UserPoints;
use App\Journal;
use App\Mood;
use App\Section;
use App\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController
{


    /**
     * @OA\Get(
     *      path="/api/dashboard",
     *      operationId="4-getDashboardData",
     *      tags={"Dashboard"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get data of Dashboard",
     *      description="Returns data of Dashboard",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function index()
    {
        $user = Helper::user();

        $userRes = new UserPoints($user);
        $data['points'] = $userRes->points;
        $data['social_points'] = $userRes->social_points;
        $data['career_points'] = $userRes->career_points;
        $data['learn_points'] = $userRes->learn_points;
        $data['spirit_points'] = $userRes->spirit_points;
        $data['health_points'] = $userRes->health_points;
        $data['emotion_points'] = $userRes->emotion_points;

        $data['social_points_per'] = Helper::cal_percentage($userRes->social_points , $userRes->points);
        $data['career_points_per'] = Helper::cal_percentage($userRes->career_points , $userRes->points);
        $data['learn_points_per'] =  Helper::cal_percentage($userRes->learn_points , $userRes->points);
        $data['spirit_points_per'] = Helper::cal_percentage($userRes->spirit_points , $userRes->points);
        $data['health_points_per'] = Helper::cal_percentage($userRes->health_points , $userRes->points);
        $data['emotion_points_per'] = Helper::cal_percentage($userRes->emotion_points , $userRes->points);

        $activity_info = DB::table('user_activities')
                 ->where('user_id' , '=' , $user->id)
                 ->select('section_id', DB::raw('count(*) as total'))
                 ->groupBy('section_id')
                 ->orderBy('section_id')
                 ->get();

        $data['user_activity_in_section'] = $activity_info;


        $section_activity_info = DB::table('activities')
        ->select('section_id', DB::raw('count(*) as total'))
        ->groupBy('section_id')
        ->orderBy('section_id')
        ->get();

        $data['all_activities_in_sections'] = $section_activity_info;

        $sum = null;
        foreach ($section_activity_info as $key => $value) {
            $sum = $sum + $value->total;
        }

        $data['total_activities'] = $sum;


        $min_activity = DB::table('user_activities')
                 ->where('user_id' , '=' , $user->id)
                 ->groupBy('section_id')
                 ->orderByRaw('count(*) ASC')
                 ->select('section_id')
                 ->get();




        $data['common_least_activity'] = isset($min_activity[0]) ? new SectionLite(Section::find($min_activity[0]->section_id)) : [];

        $data['focus_more_on'] = isset($min_activity[1]) ? new SectionLite(Section::find($min_activity[1]->section_id)) : [];

        $max_activity = DB::table('user_activities')
                 ->where('user_id' , '=' , $user->id)
                 ->groupBy('section_id')
                 ->orderByRaw('count(*) DESC')
                 ->select('section_id')
                 ->first();

        $data['common_activity'] = isset($max_activity->section_id) ? new SectionLite(Section::find($max_activity->section_id)) : [];


        $max_mood = DB::table('user_moods')
        ->where('user_id' , '=' , $user->id)
        ->groupBy('moods_id')
        ->orderByRaw('count(*) DESC')
        ->select('moods_id')
        ->first();


        $mood = isset($max_mood->moods_id) ? Mood::find($max_mood->moods_id) : null;

        $data['common_mood'] = !is_null($mood) ?  new ResourcesMood($mood) : [];


        $end_date = Carbon::now();
        $start_date = Carbon::now()->subDays(10);

        $habits_info = DB::table('journals')
        ->where('user_id' , '=' , $user->id)
        ->where('nameType', '=' ,'Habit')
        ->whereBetween('date',[$start_date , $end_date])
        ->select(DB::raw('DAYNAME(date) as day'), DB::raw('count(*) as total'))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $data['habits'] = $habits_info;


        $end_month_date  = Carbon::now()->endOfMonth();
        $start_month_date = Carbon::now()->startOfMonth();

        $habits_in_month =  Habits::where(['user_id' => $user->id])->whereBetween('active_date', [$start_month_date, $end_month_date])->get();



        $data['habits_in_month'] = $habits_in_month;



        $goals_info = DB::table('journals')
        ->where('user_id' , '=' , $user->id)
        ->where('nameType', '=' ,'Goal')
        ->whereBetween('date',[$start_date , $end_date])
        ->select(DB::raw('DAYNAME(date) as day'), DB::raw('count(*) as total'))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $data['goals_completed'] = $goals_info;


        $goals_complete = Goals::where(['user_id' => $user->id , 'is_completed' => 1])->count();

        $data['goals_complete_count'] = $goals_complete;


        $tasks_complete = Task::where(['user_id' => $user->id , 'is_Finished' => 1])->count();

        $data['tasks_complete_count'] = $tasks_complete;


        $goals = Goals::where(['user_id' => $user->id])->get();

        $data['goals'] = Goal::collection($goals);


        return $this->sendResponse($data , __('messages.dashboardSucessRetrieved'));
    }
}
