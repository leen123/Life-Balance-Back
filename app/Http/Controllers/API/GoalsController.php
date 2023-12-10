<?php

namespace App\Http\Controllers\API;


use App\Goals;
use App\Section;
use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\GoalRequest;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;


use App\Http\Resources\Goal as GoalResource;
use App\Http\Resources\Task as TaskResource;
use App\Task;


class GoalsController extends BaseController
{

    /**
     * @OA\Get(
     *      path="/api/goal",
     *      operationId="4-getGoalList",
     *      tags={"Goals"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of goals",
     *      description="Returns list of goals",
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

        $goals = Goals::where(['user_id' => $user->id])->get();
        return $this->sendResponse(GoalResource::collection($goals), __('messages.goalSucessRetrieved'));
    }


    /**
     * @OA\Get(
     *      path="/api/goal/{id}",
     *      operationId="5-get-goal",
     *      tags={"Goals"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get goals",
     *      description="Returns goal information",
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
        $goal = Goals::find($id);
        if (is_null($goal)) {
            return $this->sendError(__('messages.goalNotFound'));
        }
        return $this->sendResponse(new GoalResource($goal), __('messages.goalSucessRetrieved'));
    }




    /**
     * @OA\Post(
     ** path="/api/goal",
     *   tags={"Goals"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Crete Goal  ",
     *   operationId="create-goal-data",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass goal data",
     *    @OA\JsonContent(
     *       required={"name", "image", "section_id" , "points" , "final_date" , "duration"},
     *       @OA\Property(property="name", type="string", example="String"),
     *       @OA\Property(property="ar_name", type="string", example="تجربة"),
     *       @OA\Property(property="image", type="string", example="test.png"),
     *       @OA\Property(property="points", type="number", example=10),
     *       @OA\Property(property="section_id", type="number" , example=1),
     *       @OA\Property(property="final_date", type="string" , example="1-1-2021"),
     *       @OA\Property(property="duration", type="number" , example=1),
     *       @OA\Property(
     *           property="tasks",
     *          type="array" ,
     *          @OA\Items(
     *              @OA\Property(property="title", type="string", example="string"),
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
    public function store(GoalRequest $request)
    {


        $user = Helper::user();
        $isAllow = false;

        if($user->is_active == 0){
            $goals = Goals::where(['user_id' => $user->id])->get();
            if(count($goals) <= 2){
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
                'final_date' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError(__('messages.validError'), $validator->errors(), 400);
            }

            $Section = Section::find($input['section_id']);
            if (is_null($Section)) {
                return $this->sendError(__('messages.sectionNotFound'));
            }



            $goal = new Goals();

            $goal->name = $input['name'];
            $goal->ar_name = $input['ar_name'] ?? "" ;
            $goal->image = $input['image'];
            $goal->points = $input['points'];
            $goal->section_id = $input['section_id'] ?? '';
            $goal->user_id = $user->id;
            $goal->final_date = $input['final_date'];
            $goal->duration = $input['duration'];
            $goal->save();


            if (isset($input['tasks'])) {
                foreach ($input['tasks'] as $key => $value) {
                    $task = new Task();
                    $task->title = $value['title'];
                    $task->goals_id = $goal->id;
                    $task->user_id = $user->id;
                    $task->is_Finished = false;
                    $task->save();
                }
            }

            return $this->sendResponse(new GoalResource($goal), __('messages.goalSucessAdded'));

        }
    }


    /**
     * @OA\Put(
     ** path="/api/goal/{id}",
     *   tags={"Goals"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Update",
     *   operationId="6-Update-goal",
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
     *       @OA\Property(property="final_date", type="string" , example="1-1-2021"),
     *       @OA\Property(property="duration", type="number" , example=1),
     *       @OA\Property(
     *           property="tasks",
     *          type="array" ,
     *          @OA\Items(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="title", type="string", example="string"),
     *            ),
     *     ),
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
/*'name','final_date','duration','points','is_completed' */

    public function update($id, GoalRequest $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'image' => 'required',
            'points' => 'required',
            'section_id' => 'required',
            'final_date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $goal = Goals::find($id);
        if (is_null($goal)) {
            return $this->sendError(__('messages.goalNotFound'));
        }

        $Section = Section::find($input['section_id']);
        if (is_null($Section)) {
            return $this->sendError(__('messages.sectionNotFound'));
        }

        $user = Helper::user();

        $goal->name = $input['name'];
        $goal->ar_name = $input['ar_name'] ?? "" ;
        $goal->image = $input['image'];
        $goal->points = $input['points'];
        $goal->section_id = $input['section_id'] ?? '';
        $goal->user_id = $user->id;
        $goal->final_date = $input['final_date'];
        $goal->duration = $input['duration'];
        $goal->save();

        if (isset($input['tasks'])) {
            foreach ($input['tasks'] as $key => $value) {
                if (isset($value['id'])) {
                    $task = Task::find($value['id']);
                    if (is_null($task)) {
                        return $this->sendError(__('messages.taskNotFound'));
                    }
                    $task->title = $value['title'];
                    $task->save();
                }
            }
        }

        return $this->sendResponse(new GoalResource($goal), __('messages.goalSucessUpdated'));
    }

    /**
     * @OA\Delete(
     *      path="/api/goal/{id}",
     *      operationId="7-remove-goal",
     *      tags={"Goals"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="delete task",
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
        $goal = Goals::find($id);
        if (is_null($goal)) {
            return $this->sendError(__('messages.goalNotFound'));
        }

        $goal->delete();

        return $this->sendResponse([], __('messages.goalSucessDeleted'));
    }


    /**
     * @OA\Put(
     ** path="/api/goals/complete/{id}",
     *   tags={"Goals"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Complete Goals",
     *   operationId="6-complete-goal",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
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

    public function completeGoal($id, Request $request)
    {

        $user = Helper::user();
        $goal = Goals::find($id);
        if (is_null($goal)) {
            return $this->sendError(__('messages.goalNotFound'));
        }

        if (!$goal->is_completed) {
            $isOk = true;
            $tasks = $goal->tasks;
            if (isset($tasks)) {
                foreach ($tasks as $key => $value) {
                    if ($value['is_Finished'] == false) {
                        $isOk = false;
                        break;
                    }
                }
            }
            if ($isOk == true) {
                $goal->is_completed = true;
                $goal->save();

                Helper::add_points_to_user($goal->points , $goal->section_id);

            } else {
                return $this->sendError(__('messages.canNotCompleteGoalCheckTasks'), [], 400);
            }
        } else {
            return $this->sendError(__('messages.goalIsCompleted'), [], 400);
        }


        $isOpen = Helper::cal_badges();
        $isReword = Helper::cal_rewords();

        return $this->sendResponse(new GoalResource($goal), __('messages.goalSucessCompleted') , $isOpen , $isReword);
    }


    /**
     * @OA\Post(
     ** path="/api/task",
     *   tags={"Tasks"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Crete Task  ",
     *   operationId="create-task-data",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass task data",
     *    @OA\JsonContent(
     *       required={"title", "goal_id", "is_Finished"},
     *       @OA\Property(property="title", type="string", example="String"),
     *       @OA\Property(property="goal_id", type="number", example=11),
     *       @OA\Property(property="is_Finished", type="boolean" , example=false),
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
    public function storeTask(Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'goal_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $goal = Goals::find($input['goal_id']);
        if (is_null($goal)) {
            return $this->sendError(__('messages.goalNotFound'));
        }


        $user = Helper::user();
        $task = new Task();

        $task->title = $input['title'];
        $task->goals_id = $input['goal_id'];
        $task->user_id = $user->id;
        $task->is_Finished = false;

        $goal->save();

        $task->save();

        return $this->sendResponse(new TaskResource($task),__('messages.taskSucessAdded'));
    }

    /**
     * @OA\Delete(
     *      path="/api/task/{id}",
     *      operationId="7-remove-task",
     *      tags={"Tasks"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="delete task",
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
    public function destroyTask($id)
    {
        $task = Task::find($id);
        if (is_null($task)) {
            return $this->sendError(__('messages.taskNotFound'));
        }

        $task->delete();

        return $this->sendResponse([], __('messages.taskSucessdeleted'));
    }



    /**
     * @OA\Put(
     ** path="/api/tasks/finished-or-unFinished/{id}",
     *   tags={"Tasks"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Update",
     *   operationId="6-Update-task",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
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

    public function updateTask($id, Request $request)
    {

        $task = Task::find($id);
        if (is_null($task)) {
            return $this->sendError(__('messages.taskNotFound'));
        }

        $task->is_Finished = !$task->is_Finished;
        $task->save();

        return $this->sendResponse(new TaskResource($task), __('messages.goalSucessUpdated'));
    }

    /**
     * @OA\Put(
     ** path="/api/task/{id}",
     *   tags={"Tasks"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Update Task",
     *   operationId="6-Update-task-full",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass task data",
     *    @OA\JsonContent(
     *       required={"title", "is_Finished"},
     *       @OA\Property(property="title", type="string", example="String"),
     *       @OA\Property(property="is_Finished", type="boolean" , example=false),
     *    ),
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

    public function updateFullTask($id, Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'is_Finished' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $task = Task::find($id);
        if (is_null($task)) {
            return $this->sendError(__('messages.taskNotFound'));
        }

        $task->is_Finished = $input['is_Finished'];
        $task->title = $input['title'];
        $task->save();

        return $this->sendResponse(new TaskResource($task), __('messages.taskSucessUpdated'));
    }
}
