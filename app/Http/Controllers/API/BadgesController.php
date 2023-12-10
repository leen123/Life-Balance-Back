<?php

namespace App\Http\Controllers\API;



use App\Badges;
use Illuminate\Http\Request;
use App\Http\Requests\BudgesRequest;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\activity;
use App\Http\Resources\Badges as BadgeRes;
use App\Section;
use Illuminate\Support\Facades\Validator;



class BadgesController extends BaseController
{


    /**
     * @OA\Get(
     *      path="/api/badge",
     *      operationId="4-getBadgesList",
     *      tags={"Badges"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of Badges",
     *      description="Returns list of Badges",
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
        $badges = Badges::all();
        return $this->sendResponse(BadgeRes::collection($badges), __('messages.badgesSucessRetrieved'));
    }


    /**
     * @OA\Get(
     *      path="/api/badge/{id}",
     *      operationId="5-get-badge",
     *      tags={"Badges"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get badge",
     *      description="Returns badge information",
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
        $badge = Badges::find($id);
        if (is_null($badge)) {
            return $this->sendError(__('messages.badgedNotFound'));
        }
        return $this->sendResponse(new BadgeRes($badge), __('messages.badgesSucessRetrieved'));
    }




    /**
     * @OA\Post(
     ** path="/api/badge",
     *   tags={"Badges"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Crete badge  ",
     *   operationId="create-badge-data",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass badge data",
     *    @OA\JsonContent(
     *       required={"name", "is_from_section", "points", "section_id", "badges_id" , "count_of_badges"},
     *       @OA\Property(property="name", type="string", example="String"),
     *       @OA\Property(property="ar_name", type="string", example="تجربة"),
     *       @OA\Property(property="image", type="string", example="String.png"),*
     *       @OA\Property(property="is_from_section", type="boolean", example=false),
     *       @OA\Property(property="is_grand_master", type="boolean", example=false),
     *       @OA\Property(property="points", type="number", example=10),
     *       @OA\Property(property="section_id", type="number" , example=1),
     *       @OA\Property(property="badges_id", type="number" , example=1),
     *       @OA\Property(property="count_of_badges", type="number" , example=1),
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
    public function store(BudgesRequest $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'is_from_section' => 'required',
            'image' => 'required' ,
            'is_grand_master' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        if($input['is_from_section']){
            if(!isset($input['section_id'])){
                return $this->sendError(__('messages.validError'), __('messages.SectionIsRequired'), 400);
            }
            if(!isset($input['points'])){
                return $this->sendError(__('messages.validError'), __('messages.PointsIsRequired'), 400);
            }
        }else{
            if(!isset($input['badges_id'])){
                return $this->sendError(__('messages.validError'), __('messages.BadgeIsRequired'), 400);
            }
            if(!isset($input['count_of_badges'])){
                return $this->sendError(__('messages.validError'), __('messages.CountOfBadgesIsRequired'), 400);
            }
        }

        if(isset($input['section_id']) && isset($input['badges_id'])){
            return $this->sendError(__('messages.validError'), __('messages.SectionOrBadge'), 400);
        }


        if(isset($input['section_id'])){
            $Section = Section::find($input['section_id']);
            if (is_null($Section)) {
                return $this->sendError(__('messages.sectionNotFound'));
            }
        }

        if(isset($input['badges_id'])){
            $badge = Badges::find($input['badges_id']);
            if (is_null($badge)) {
                return $this->sendError(__('messages.badgedNotFound'));
            }

            if($badge->is_grand_master == 0){
                return $this->sendError(__('messages.validError'), __('messages.GrandMaster'), 400);
            }
        }

        $badge = new Badges();

        $badge->name = $input['name'];
        $badge->ar_name = $input['ar_name'] ?? "" ;
        $badge->image = $input['image'];
        $badge->is_from_section = $input['is_from_section'];
        $badge->points = isset($input['points']) ? $input['points'] : 0;
        $badge->section_id = isset($input['section_id']) ? $input['section_id'] : null;
        $badge->badges_id = isset($input['badges_id']) ? $input['badges_id'] : null;
        $badge->count_of_badges = isset($input['count_of_badges']) ? $input['count_of_badges'] : 0;

        $badge->save();

        return $this->sendResponse(new BadgeRes($badge), __('messages.badgesSucessUpdated'));
    }


    /**
     * @OA\Put(
     ** path="/api/badge/{id}",
     *   tags={"Badges"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="badge Update",
     *   operationId="6-Update-badge",
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
     *    description="Update badge data",
     *      @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="String"),
     *       @OA\Property(property="ar_name", type="string", example="تجربة"),
     *       @OA\Property(property="image", type="string", example="test.png"),
     *
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
    public function update($id, BudgesRequest $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $badge = Badges::find($id);
        if (is_null($badge)) {
            return $this->sendError(__('messages.badgedNotFound'));
        }

        $badge->name = $input['name'];
        $badge->ar_name = $input['ar_name'] ?? "" ;
        $badge->image = $input['image'];

        $badge->save();

        return $this->sendResponse(new BadgeRes($badge),__('messages.badgesSucessUpdated'));
    }


    /**
     * @OA\Delete(
     *      path="/api/badge/{id}",
     *      operationId="7-remove-badge",
     *      tags={"Badges"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="delete badge",
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
        $badge = Badges::find($id);
        if (is_null($badge)) {
            return $this->sendError(__('messages.badgedNotFound'));
        }

        $badge->delete();

        return $this->sendResponse([], __('messages.badgesSucessDeleted'));
    }
}
