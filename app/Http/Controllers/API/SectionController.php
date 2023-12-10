<?php

namespace App\Http\Controllers\API;

use App\Section;
use Illuminate\Http\Request;
use App\Http\Requests\SectionRequest;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;


use App\Http\Resources\Section as SectionResource;
use App\Http\Resources\UserActivity as ResourcesUserActivity;
use App\Http\Resources\UserSections;
use App\UserActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SectionController extends BaseController
{


    /**
     * @OA\Post(
     ** path="/api/section",
     *   tags={"Section"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Crete Section  ",
     *   operationId="create-Section-data",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass activity data",
     *    @OA\JsonContent(
     *       required={"name", "code", "description" , "image"},
     *       @OA\property(property="name", type="string", example="name"),
     *       @OA\Property(property="ar_name", type="string", example="تجربة"),
     *       @OA\property(property="code", type="string", example="code"),
     *       @OA\property(property="description", type="string", example="description"),
     *       @OA\Property(property="ar_description", type="string", example="2تجربة"),
     *       @OA\property(property="image", type="string", example="image.png")
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
    public function store(SectionRequest $request)
    {

        $input = $request->all();

        $section = new Section();

        $section->name = $input['name'];
        $section->ar_name = $input['ar_name'] ?? "" ;
        $section->image = $input['image'];
        $section->code = $input['code'];
        $section->description = $input['description'] ?? '';
        $section->ar_description = $input['ar_description'] ?? "" ;

        $section->save();

        return $this->sendResponse(new SectionResource($section), __('messages.sectionSucessUpdated'));
    }




    /**
     * @OA\Get(
     *      path="/api/section",
     *      operationId="4-getSectionList",
     *      tags={"Section"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of section",
     *      description="Returns list of section",
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
        $sections = Section::all();
        return $this->sendResponse(SectionResource::collection($sections), __('messages.sectionSucessRetrieved'));
    }


    /**
     * @OA\Get(
     *      path="/api/section/{id}",
     *      operationId="5-get-section",
     *      tags={"Section"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get Service",
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
        $section = Section::find($id);
        if (is_null($section)) {
            return $this->sendError(__('messages.sectionNotFound'));
        }

        $user = request()->user();
        $data['section_points'] = new UserSections($user);

        $previous_week = strtotime("-1 week +1 day");
        $start_week = strtotime("last sunday midnight",$previous_week);
        $end_week = strtotime("next saturday",$start_week);
        $start_week = date("Y-m-d",$start_week);
        $end_week = date("Y-m-d",$end_week);

        $activity_info = DB::table('user_activities')
        ->where('user_id' , '=' , $user->id)
        ->where('section_id' , '=' , $id)
        ->whereBetween('date',[$start_week , $end_week])
        ->select(DB::raw('DAYNAME(date) as day') , DB::raw('sum(points) as total'))
        ->groupBy('date')
        ->get();

        $data['activity_last_week'] = $activity_info;


        $currentDateTime = Carbon::now();
        $newDateTime = Carbon::now()->subMonths(6);

        $activity_info_month = DB::table('user_activities')
        ->where('user_id' , '=' , $user->id)
        ->where('section_id' , '=' , $id)
        ->whereBetween('date',[$newDateTime , $currentDateTime])
        ->select(DB::raw('monthname(str_to_date(monthDate, "%m")) as month'), DB::raw('sum(points) as total'))
        ->groupBy('monthDate')
        ->orderBy('date')
        ->get();

        $data['activity_last_six_month'] = $activity_info_month;


        $last_activities = UserActivity::where(['user_id' => $user->id , 'section_id' => $id])->orderBy('id', 'DESC')->take(10)->get();

        $data['last_activities'] = ResourcesUserActivity::collection($last_activities);

        return $this->sendResponse($data, __('messages.sectionSucessRetrieved'));
    }


    /**
     * @OA\Put(
     ** path="/api/section/{id}",
     *   tags={"Section"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Update",
     *   operationId="6-Update-section",
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
     *        @OA\property(property="name", type="string", example="name"),
     *        @OA\Property(property="ar_name", type="string", example="تجربة"),
     *        @OA\property(property="code", type="string", example="code"),
     *        @OA\property(property="description", type="string", example="description"),
     *        @OA\Property(property="ar_description", type="string", example="تجربة"),
     *        @OA\property(property="image", type="string", example="image.png")
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
    public function update($id, SectionRequest $request)
    {

        $input = $request->all();

        $section = Section::find($id);

        $section->name = $input['name'];
        $section->ar_name = $input['ar_name'] ?? "" ;
        $section->image = $input['image'];
        $section->code = $input['code'];
        $section->description = $input['description'] ?? '';
        $section->ar_description= $input['ar_description'] ?? "" ;

        $section->save();

        return $this->sendResponse(new SectionResource($section), __('messages.sectionSucessUpdated'));
    }


    /**
     * @OA\Delete(
     *      path="/api/section/{id}",
     *      operationId="7-remove-sections",
     *      tags={"Section"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="delete section",
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
        $section = Section::find($id);
        if (is_null($section)) {
            return $this->sendError(__('messages.sectionNotFound'));
        }

        $section->delete();

        return $this->sendResponse([], __('messages.sectionSucessDeleted'));
    }
}
