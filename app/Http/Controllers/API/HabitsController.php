<?php

namespace App\Http\Controllers\API;


use App\Habits;
use App\Section;
use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\HabitRequest;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;


use App\Http\Resources\Activity as ActivityResource;

use App\Http\Resources\Habit as HabitResource;
use App\Journal;
use App\User;

class HabitsController extends BaseController
{


    /**
     * @OA\Get(
     *      path="/api/habit",
     *      operationId="4-gethabitList",
     *      tags={"Habits"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of habits",
     *      description="Returns list of habits",
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

        $habits = Habits::where(['user_id' => $user->id])->get();
        return $this->sendResponse(HabitResource::collection($habits), __('messages.habitSucessRetrieved'));
    }


    /**
     * @OA\Get(
     *      path="/api/habit/{id}",
     *      operationId="5-get-habit",
     *      tags={"Habits"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get habit",
     *      description="Returns habit information",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
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

    public function show($id)
    {
        $habit = Habits::find($id);
        if (is_null($habit)) {
            return $this->sendError(__('messages.habitdNotFound'));
        }
        return $this->sendResponse(new HabitResource($habit), __('messages.habitSucessRetrieved'));
    }




    /**
     * @OA\Post(
     ** path="/api/habit",
     *   tags={"Habits"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Crete habit  ",
     *   operationId="create-habit-data",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass habit data",
     *    @OA\JsonContent(
     *       required={"name", "image", "section_id" , "points" , "date_type" , "repetition_type" , "repetition_number"},
     *       @OA\Property(property="name", type="string", example="String"),
     *       @OA\Property(property="ar_name", type="string", example="تجربة"),
     *       @OA\Property(property="image", type="string", example="test.png"),
     *       @OA\Property(property="points", type="number", example=10),
     *       @OA\Property(property="section_id", type="number" , example=1),
     *       @OA\Property(property="date_type", type="number" , example=1),
     *       @OA\Property(property="repetition_type", type="number" , example=1),
     *       @OA\Property(property="repetition_number", type="number" , example=1),
     *    ),
     * ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(HabitRequest $request)
    {

        $user = Helper::user();
        $isAllow = false;

        if($user->is_active == 0){
            $habits = Habits::where(['user_id' => $user->id])->get();
            if(count($habits) <= 3){
                $isAllow = true;
            }else{
                return $this->sendError(__('messages.RenewYourSubscription') ,null ,402);
            }
        }else{
            $isAllow = true;
        }
        if($isAllow){
            $input = $request->all();

            $validator = Validator::make($input, [
                'name' => 'required',
                'image' => 'required',
                'points' => 'required',
                'section_id' => 'required',
                'date_type' => 'required',
                'repetition_type' => 'required',
                'repetition_number' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError(__('messages.validError'), $validator->errors(), 400);
            }

            $Section = Section::find($input['section_id']);
            if (is_null($Section)) {
                return $this->sendError(__('messages.sectionNotFound'));
            }


            $habit = new Habits();

            $habit->name = $input['name'];
            $habit->ar_name = $input['ar_name'] ?? "" ;
            $habit->image = $input['image'];
            $habit->points = $input['points'];
            $habit->section_id = $input['section_id'] ?? '';
            $habit->user_id = $user->id;

            if (isset($input['date_type'])) {
                if ($input['date_type'] == 1) {
                    $habit->active_date = Carbon::now();
                } else if ($input['date_type'] == 2) {
                    $habit->active_date = Carbon::now()->addDays(1);
                } else {
                    $habit->active_date = Carbon::now()->addDays(7);
                }
            }

            $habit->date_type = $input['date_type'];
            $habit->doHabits = 0;
            $habit->repetition_type = $input['repetition_type'];
            $habit->repetition_number = $input['repetition_number'];


            $habit->save();

            return $this->sendResponse(new HabitResource($habit), __('messages.habitSucessUpdated'));

        }
    }


        /**
     * @OA\Put(
     ** path="/api/update-habit/{id}",
     *   tags={"Habits"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="habit Update",
     *   operationId="6-Update-full-habit",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *     @OA\RequestBody(
     *    required=false,
     *    description="Update User data",
     *      @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="String"),
     *       @OA\Property(property="ar_name", type="string", example="تجربة"),
     *       @OA\Property(property="image", type="string", example="test.png"),
     *       @OA\Property(property="points", type="number", example=10),
     *       @OA\Property(property="section_id", type="number" , example=1),
     *       @OA\Property(property="date_type", type="number" , example=1),
     *       @OA\Property(property="repetition_type", type="number" , example=1),
     *       @OA\Property(property="repetition_number", type="number" , example=1),
     *      ),
     * ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/

    public function updateHabit($id, HabitRequest $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'image' => 'required',
            'points' => 'required',
            'section_id' => 'required',
            'date_type' => 'required',
            'repetition_type' => 'required',
            'repetition_number' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $Section = Section::find($input['section_id']);
        if (is_null($Section)) {
            return $this->sendError(__('messages.sectionNotFound'));
        }

        $user = Helper::user();
        $habit = Habits::find($id);

        if (is_null($habit)) {
            return $this->sendError(__('messages.habitdNotFound'));
        }

        $habit->name = $input['name'];
        $habit->ar_name = $input['ar_name'] ?? "" ;
        $habit->image = $input['image'];
        $habit->points = $input['points'];
        $habit->section_id = $input['section_id'] ?? '';
        $habit->user_id = $user->id;

        if (isset($input['date_type'])) {
            if ($input['date_type'] == 1) {
                $habit->active_date = Carbon::now();
            } else if ($input['date_type'] == 2) {
                $habit->active_date = Carbon::now()->addDays(1);
            } else {
                $habit->active_date = Carbon::now()->addDays(7);
            }
        }

        $habit->date_type = $input['date_type'];
        $habit->repetition_type = $input['repetition_type'];
        $habit->repetition_number = $input['repetition_number'];


        $habit->save();

    }




    /**
     * @OA\Put(
     ** path="/api/habit/{id}",
     *   tags={"Habits"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="habit Update",
     *   operationId="6-Update-habit",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *     @OA\RequestBody(
     *    required=false,
     *    description="Update User data",
     *      @OA\JsonContent(
     *       @OA\Property(property="move_type", type="number", example=1),
     *      ),
     * ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function update($id, Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'move_type' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $habit = Habits::find($id);
        if (is_null($habit)) {
            return $this->sendError(__('messages.habitdNotFound'));
        }

        $user_id = $habit->user_id;
        $user = User::find($user_id);

        $mood = $input['move_type'];

        if ($habit->active_date <= Carbon::now()) {
            $habit->doHabits = $habit->doHabits + 1;
            if ($mood == 1) {
                $habit->op_points =  $habit->op_points = + (intdiv($habit->points , $habit->repetition_number ));
            } else {
                $habit->op_points = $habit->op_points  - (intdiv($habit->points , $habit->repetition_number ));
            }


            if ($habit->repetition_number == $habit->doHabits) {
                $repeats = $habit->repetition_type;

                if ($repeats == 1) {
                    $habit->active_date = Carbon::now()->addDay(1);
                } else if ($repeats == 2) {
                    $habit->active_date = Carbon::now()->addDay(7);
                } else {
                    $habit->active_date = Carbon::now()->addDay(30);
                }
                $habit->doHabits = 0;
                $habit->op_points = 0;

                Helper::save_journal('habitsJournalIcon.png' , 'Habit' , $habit->image , '3Cool1_1639046022.gif' ,$habit->name , 'Habit Completed' , ' ' , Carbon::now());
            }
            if ($mood == 1) {
                // add points to user
                $points =  (intdiv($habit->points , $habit->repetition_number ));
                Helper::add_points_to_user($points , $habit->section_id);

            } else {
                $points = (intdiv($habit->points , $habit->repetition_number ));
                Helper::sub_points_to_user($points , $habit->section_id);
            }

        } else {
            return $this->sendError(__('messages.NonValidDate'), [], 400);
        }

        $habit->save();

        $habits = Habits::where(['user_id' => $user->id])->get();

        $isOpen = Helper::cal_badges();
        $isReword = Helper::cal_rewords();

        return $this->sendResponse(HabitResource::collection($habits), __('messages.habitSucessUpdated') , $isOpen , $isReword);
    }


        /**
     * @OA\Delete(
     *      path="/api/habit/{id}",
     *      operationId="7-remove-habit",
     *      tags={"Habits"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="delete habit",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
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
    public function destroy($id)
    {
        $habit = Habits::find($id);
        if (is_null($habit)) {
            return $this->sendError(__('messages.habitdNotFound'));
        }

        $habit->delete();

        return $this->sendResponse([], __('messages.habitSucessDeleted'));
    }
}
