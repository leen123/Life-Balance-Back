<?php

namespace App\Http\Controllers\API;


use App\Activity;
use App\Enums\UserType;
use App\Section;
use App\Utils\Helper;
use Illuminate\Http\Request;
use App\Http\Requests\ActivityRequest;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;


use App\Http\Resources\Activity as ActivityResource;
use App\Http\Resources\Journal;
use App\Http\Resources\Mood as ResourcesMood;
use App\Journal as AppJournal;
use App\Mood;
use App\User;
use App\UserActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class ActivityController extends BaseController
{


    /**
     * @OA\Get(
     *      path="/api/activity",
     *      operationId="4-getactivityList",
     *      tags={"Activity"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of activity",
     *      description="Returns list of activity",
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
        $activity = Activity::where(['user_id' => null])->orWhere(['user_id' => $user->id])->get();
        return $this->sendResponse(ActivityResource::collection($activity), __('messages.activitySucessRetrieved'));
    }


    /**
     * @OA\Get(
     *      path="/api/activity/{id}",
     *      operationId="5-get-activity",
     *      tags={"Activity"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get activity",
     *      description="Returns section information",
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
        $activity = Activity::find($id);
        if (is_null($activity)) {
            return $this->sendError(__('messages.activityNotFound'));
        }
        return $this->sendResponse(new ActivityResource($activity), __('messages.activitySucessRetrieved'));
    }




    /**
     * @OA\Post(
     ** path="/api/activity",
     *   tags={"Activity"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Crete activity  ",
     *   operationId="create-activity-data",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass activity data",
     *    @OA\JsonContent(
     *       required={"name", "image", "section_id" , "points" , "ar_name"},
     *       @OA\Property(property="name", type="string", example="String"),
     *       @OA\Property(property="ar_name", type="string", example="تجربة"),
     *       @OA\Property(property="image", type="string", example="test.png"),
     *       @OA\Property(property="points", type="number", example=10),
     *       @OA\Property(property="section_id", type="number" , example=1),
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
    public function store(ActivityRequest $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'image' => 'required',
            'points' => 'required',
            'section_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $Section = Section::find($input['section_id']);
        if (is_null($Section)) {
            return $this->sendError(__('messages.sectionNotFound'));
        }

        $activity = new Activity();

        $activity->name = $input['name'];
        $activity->image = $input['image'];
        $activity->points = $input['points'];
        $activity->ar_name = $input['ar_name'] ?? "" ;
        $activity->section_id = $input['section_id'] ?? '';

        $user = Helper::user();

        if ($user) {
            if ($user->type === UserType::player) {
                $activity->user_id = $user->id;
            }
        }

        $activity->save();

        return $this->sendResponse(new ActivityResource($activity), __('messages.activitySucessUpdated'));
    }


    /**
     * @OA\Put(
     ** path="/api/activity/{id}",
     *   tags={"Activity"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Activity Update",
     *   operationId="6-Update-Activity",
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
     *       @OA\Property(property="section_id", type="number" , example=1)
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
    public function update($id, ActivityRequest $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'image' => 'required',
            'points' => 'required',
            'section_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $activity = Activity::find($id);
        if (is_null($activity)) {
            return $this->sendError(__('messages.activityNotFound'));
        }

        $activity->name = $input['name'];
        $activity->ar_name = $input['ar_name'] ?? "";
        $activity->image = $input['image'];
        $activity->points = $input['points'];
        $activity->section_id = $input['section_id'] ?? '';

        $activity->save();

        return $this->sendResponse(new ActivityResource($activity), __('messages.activitySucessUpdated'));
    }


    /**
     * @OA\Delete(
     *      path="/api/activity/{id}",
     *      operationId="7-remove-activity",
     *      tags={"Activity"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="delete activity",
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
        $activity = Activity::find($id);
        if (is_null($activity)) {
            return $this->sendError(__('messages.activityNotFound'));
        }

        $activity->delete();

        return $this->sendResponse([], __('messages.activitySucessDeleted'));
    }


    /**
     * @OA\Post(
     ** path="/api/activities/do-activity",
     *   tags={"Activity"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="do activity for user ",
     *   operationId="create-do-activity-for-user",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass user activity data",
     *    @OA\JsonContent(
     *       @OA\Property(property="activity_id" , type="number", example=1),
     *       @OA\Property(property="mood_id" , type="number", example=1),
     *       @OA\Property(property="note" , type="string", example="String"),
     *       @OA\Property(property="form" , type="string" , example="2021-12-11"),
     *       @OA\Property(property="to" , type="string" , example="2021-12-13"),
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

    public function doAction(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'activity_id' => 'required',
            'mood_id' => 'required',
            'form' => 'required',
            'to' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $activity = Activity::find($input['activity_id']);
        if (is_null($activity)) {
            return $this->sendError(__('messages.activityNotFound'));
        }

        $moods = Mood::find($input['mood_id']);
        if (is_null($moods)) {
            return $this->sendError(__('messages.moodNotFound'));
        }

        $user = Helper::user();
        $activityRes = new ActivityResource($activity);
        $moodRes = new ResourcesMood($moods);

        $section_id = $activityRes->section_id;

        $user_activity = new UserActivity();

        $user_activity->activity_id = $input['activity_id'];
        $user_activity->mood_id = $input['mood_id'];
        $user_activity->form =  $input['form'];
        $user_activity->to = $input['to'];
        $user_activity->user_id = $user->id;
        $user_activity->section_id = $section_id;
        $date = isset($input['date']) ? Carbon::createFromFormat('Y-m-d' , $input['date']) :Carbon::now();
        $user_activity->date = $date;
        $user_activity->dayDate = $date->day;
        $user_activity->monthDate = $date->month;
        $user_activity->yearDate = $date->year;
        $user_activity->hoursDate = $date->hour;
        $user_activity->minutesDate = $date->minute;
        $user_activity->note = isset($input['note']) ? $input['note'] : ' ';

        $user_activity->points = $activity->points;

        $note = isset($input['note']) ? $input['note'] : ' ';
        Helper::save_journal('activityJournalIcon.png' , 'Activity' ,$activity->image, $moods->image , $activityRes->name ,'Activity Dane' ,$note ,$date );

        $user_activity->save();

        Helper::add_points_to_user($activity->points , $section_id);

        $isOpen = Helper::cal_badges();
        $isReword = Helper::cal_rewords();

        return $this->sendResponse($user_activity, __('messages.activitySucessAdded') , $isOpen , $isReword);
    }

    /**
     * @OA\Post(
     ** path="/api/activities/do-quick-entry-activity",
     *   tags={"Activity"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="do multi activity for user ",
     *   operationId="create-do-multi-activity-for-user",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass multi activity data",
     *    @OA\JsonContent(
     *      @OA\Property(property="date" , type="string" , example="2021-12-10"),
     *      @OA\Property(
     *          property="activities",
     *              type="array" ,
     *              @OA\Items(
     *                  @OA\Property(property="section_id", type="number" , example=1),
     *                  @OA\Property(property="activity_id" , type="number", example=1),
     *                  @OA\Property(property="mood_id" , type="number", example=1),
     *                  @OA\Property(property="note" , type="string", example="String"),
     *            ),
     *     ),
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

    public function doQuickEntryActivity(Request $request)
    {
        $input = $request->all();
        $activities = $input['activities'];

        if (isset($activities)) {
            $user = Helper::user();
            foreach ($activities as $key => $value) {
                $user_activity = new UserActivity();
                $user_activity->activity_id = $value['activity_id'];
                $user_activity->mood_id = $value['mood_id'];
                $user_activity->user_id = $user->id;

                $date = isset($input['date']) ? Carbon::createFromFormat('Y-m-d' , $input['date']) :Carbon::now();
                $user_activity->date = $date;
                $user_activity->dayDate = $date->day;
                $user_activity->monthDate = $date->month;
                $user_activity->yearDate = $date->year;
                $user_activity->hoursDate = $date->hour;
                $user_activity->minutesDate = $date->minute;


                $activity = Activity::find($value['activity_id']);
                if (is_null($activity)) {
                    return $this->sendError('Activity not found.');
                }

                $moods = Mood::find($value['mood_id']);
                if (is_null($moods)) {
                    return $this->sendError(__('messages.moodNotFound'));
                }

                $user_activity->points = $activity->points;

                $user_activity->section_id = (new ActivityResource($activity))->section_id;

                $note = isset($input['note']) ? $input['note'] : ' ';
                Helper::save_journal('activityJournalIcon.png' , 'Activity' ,$activity->image, $moods->image , $activity->name ,'Activity Dane' ,$note ,$date );


                $user_activity->save();

                Helper::add_points_to_user($activity->points , $value['section_id']);

            }
        }

        $isOpen = Helper::cal_badges();
        $isReword = Helper::cal_rewords();

        return $this->sendSuccess(__('messages.activitySucessAdded'), [], 200, $isOpen , $isReword);
    }
}
