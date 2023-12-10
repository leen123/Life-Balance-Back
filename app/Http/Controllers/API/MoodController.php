<?php

namespace App\Http\Controllers\API;

use App\Badges;
use App\Goals;
use App\Habits;
use App\Mood;
use Illuminate\Http\Request;
use App\Http\Requests\MoodRequest;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;


use App\Http\Resources\Mood as MoodResource;
use App\Journal;
use App\Reward;
use App\UserMoods;
use App\Utils\Helper;
use Carbon\Carbon;

class MoodController extends BaseController
{


    /**
     * @OA\Get(
     *      path="/api/mood",
     *      operationId="4-getMoodList",
     *      tags={"Mood"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of Mood",
     *      description="Returns list of Mood",
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
        $Mood = Mood::all();
        return $this->sendResponse(MoodResource::collection($Mood), __('messages.moodSucessRetrieved'));
    }


    /**
     * @OA\Get(
     *      path="/api/mood/{id}",
     *      operationId="5-get-Mood",
     *      tags={"Mood"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get Mood",
     *      description="Returns Mood information",
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
        $Mood = Mood::find($id);
        if (is_null($Mood)) {
            return $this->sendError(__('messages.moodNotFound'));
        }
        return $this->sendResponse(new MoodResource($Mood), __('messages.moodSucessRetrieved'));
    }




    /**
     * @OA\Post(
     ** path="/api/mood",
     *   tags={"Mood"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Crete Mood  ",
     *   operationId="create-Mood-data",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass Mood data",
     *    @OA\JsonContent(
     *       required={"name", "image"},
     *       @OA\Property(property="name", type="string", example="String"),
     *       @OA\Property(property="ar_name", type="string", example="تجربة"),
     *       @OA\Property(property="image", type="string", example="test.png"),
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
    public function store(MoodRequest $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $Mood = new Mood();

        $Mood->name = $input['name'];
        $Mood->ar_name = $input['ar_name'] ?? "" ;
        $Mood->image = $input['image'];





        $Mood->save();

        return $this->sendResponse(new MoodResource($Mood), __('messages.imageSucessUpdated'));
    }


    /**
     * @OA\Put(
     ** path="/api/mood/{id}",
     *   tags={"Mood"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Mood Update",
     *   operationId="6-Update-Mood",
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
    public function update($id, MoodRequest $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $Mood = Mood::find($id);
        if (is_null($Mood)) {
            return $this->sendError(__('messages.moodNotFound'));
        }

        $Mood->name = $input['name'];
        $Mood->ar_name = $input['ar_name'] ?? "" ;
        $Mood->image = $input['image'];





        $Mood->save();

        return $this->sendResponse(new MoodResource($Mood), __('messages.imageSucessUpdated'));
    }


    /**
     * @OA\Delete(
     *      path="/api/mood/{id}",
     *      operationId="7-remove-Mood",
     *      tags={"Mood"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="delete Mood",
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
        $Mood = Mood::find($id);
        if (is_null($Mood)) {
            return $this->sendError(__('messages.moodNotFound'));
        }

        $Mood->delete();

        return $this->sendResponse([], __('messages.moodSucessDeleted'));
    }


    /**
     * @OA\Post(
     ** path="/api/moods/do-mood",
     *   tags={"Mood"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="do mood for user ",
     *   operationId="create-do-mood-for-user",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass user mood data",
     *    @OA\JsonContent(
     *       @OA\Property(property="moods_id" , type="number", example=1),
     *       @OA\Property(property="note" , type="string", example="String"),
     *       @OA\Property(property="date" , type="string" , example="2021-12-10"),
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

    public function doMood(Request $request)
    {
        $input = $request->all();
        $user = Helper::user();
        $date = isset($input['date']) ? Carbon::createFromFormat('Y-m-d', $input['date']) : Carbon::now();

        $isAllow = false;

        if ($user->is_active == 0) {
            $moods = UserMoods::where(['user_id' => $user->id, 'dayDate' => $date->day])->get();
            if (count($moods) <= 1) {
                $isAllow = true;
            } else {
                return $this->sendError(__('messages.RenewYourSubscription'), null, 402);
            }
        } else {
            $isAllow = true;
        }
        if ($isAllow) {
            $validator = Validator::make($input, [
                'moods_id' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError(__('messages.validError'), $validator->errors(), 400);
            }

            $moods = Mood::find($input['moods_id']);
            if (is_null($moods)) {
                return $this->sendError(__('messages.moodNotFound'));
            }



            $user_mood = new UserMoods();

            $user_mood->moods_id = $input['moods_id'];
            $user_mood->user_id = $user->id;
            $user_mood->note = isset($input['note']) ? $input['note'] : '';

            $user_mood->date = $date;
            $user_mood->dayDate = $date->day;
            $user_mood->monthDate = $date->month;
            $user_mood->yearDate = $date->year;
            $user_mood->hoursDate = $date->hour;
            $user_mood->minutesDate = $date->minute;


            $note = isset($input['note']) ? $input['note'] : ' ';

            $user_mood->save();
            Helper::save_journal('moodJournalIcon.png', 'Mood', $moods->image, $moods->image, 'Feeling' . $moods->name, ' ', $note, $date);

            return $this->sendSuccess(__('messages.activitySucessAdded'));
        }
    }

    /**
     * @OA\Post(
     ** path="/api/moods/entity-mood",
     *   tags={"Mood"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="do mood for entity ",
     *   operationId="create-do-mood-for-entity",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass entity mood data",
     *    @OA\JsonContent(
     *       @OA\Property(property="mood_id" , type="number", example=1),
     *       @OA\Property(property="entity_id" , type="number", example=1),
     *       @OA\Property(property="entity_type" , type="number" , example=1),
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

    public function entityMood(Request $request)
    {
        $input = $request->all();
        $user = Helper::user();

        $validator = Validator::make($input, [
            'mood_id' => 'required',
            'entity_id' => 'required',
            'entity_type' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $mood = Mood::find($input['mood_id']);
        if (is_null($mood)) {
            return $this->sendError(__('messages.moodNotFound'));
        }
        if ($input['entity_type'] == 1) {
            $goal = Goals::find($input['entity_id']);
            if (is_null($goal)) {
                return $this->sendError(__('messages.goalNotFound'));
            }
            Helper::save_journal('goalsJournalIcon.png', 'Goal', $goal->image, $mood->image, $goal->name, 'Goal Completed', ' ', Carbon::now());
        }else if($input['entity_type'] == 2){
            $habit = Habits::find($input['entity_id']);
            if (is_null($habit)) {
                return $this->sendError(__('messages.habitdNotFound'));
            }
            Helper::save_journal('habitsJournalIcon.png' , 'Habit' , $habit->image , $mood->image ,$habit->name , 'Habit Completed' , ' ' , Carbon::now());
        }else if($input['entity_type'] == 3){
            $badge = Badges::find($input['entity_id']);
            if (is_null($badge)) {
                return $this->sendError(__('messages.badgedNotFound'));
            }
            Helper::save_journal('badgeJournalIcon.png' , 'Badge' , $badge->image , $mood->image ,$badge->name , 'Badge Completed' , ' ' , Carbon::now());
        }else {
            $reword = Reward::find($input['entity_id']);
            if (is_null($reword)) {
                return $this->sendError(__('messages.rewardNotFound'));
            }
            Helper::save_journal('rewordJournalIcon.png' , 'Reword' , $reword->image , $mood->image ,$reword->name , 'Reword Completed' , ' ' , Carbon::now());
        }

        return $this->sendSuccess(__('messages.moodEntitySucessAdded'));

    }
}
