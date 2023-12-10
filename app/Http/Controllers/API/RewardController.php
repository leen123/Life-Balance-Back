<?php

namespace App\Http\Controllers\API;

use App\Reward;
use Illuminate\Http\Request;
use App\Http\Requests\RewardRequest;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;


use App\Http\Resources\Reward as RewardResource;

class RewardController extends BaseController
{


    /**
     * @OA\Post(
     ** path="/api/reward",
     *   tags={"Reward"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Crete Reward  ",
     *   operationId="create-Reward-data",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass Reward data",
     *    @OA\JsonContent(
     *       required={"image" , "name"},
     *       @OA\property(property="name", type="string", example="String"),
     *       @OA\Property(property="ar_name", type="string", example="تجربة"),
     *       @OA\property(property="code", type="string", example="String"),
     *       @OA\property(property="image", type="string", example="image.png"),
     *       @OA\property(property="quantity_points", type="number", example="1000"),
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
    public function store(RewardRequest $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'image' => 'required',
            'quantity_points' => 'required',
            'code' => 'required|unique:rewards|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $reward = new Reward();

        $reward->image = $input['image'];
        $reward->name = $input['name'];
        $reward->ar_name = $input['ar_name'] ?? "" ;
        $reward->quantity_points = $input['quantity_points'];
        $reward->code= $input['code'];

        $reward->save();

        return $this->sendResponse(new RewardResource($reward), __('messages.rewardSucessUpdated'));
    }




    /**
     * @OA\Get(
     *      path="/api/reward",
     *      operationId="4-getImageList",
     *      tags={"Reward"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of Reward",
     *      description="Returns list of Reward",
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
        $rewards = Reward::all();
        return $this->sendResponse(RewardResource::collection($rewards), __('messages.rewardSucessRetrieved'));
    }


    /**
     * @OA\Get(
     *      path="/api/reward/{id}",
     *      operationId="5-get-reward",
     *      tags={"Reward"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get rewards",
     *      description="Returns rewards information",
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
        $reward = Reward::find($id);
        if (is_null($reward)) {
            return $this->sendError(__('messages.rewardNotFound'));
        }
        return $this->sendResponse(new RewardResource($reward), __('messages.rewardSucessRetrieved'));
    }


    /**
     * @OA\Put(
     ** path="/api/reward/{id}",
     *   tags={"Reward"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Update",
     *   operationId="6-Update-reward",
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
     *    description="Update images data",
     *      @OA\JsonContent(
     *       @OA\property(property="name", type="string", example="String"),
     *       @OA\Property(property="ar_name", type="string", example="تجربة"),
     *       @OA\property(property="image", type="string", example="image.png"),
     *       @OA\property(property="quantity_points", type="number", example="1000"),
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
    public function update($id, RewardRequest $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'image' => 'required',
            'quantity_points' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $reward = Reward::find($id);

        if (is_null($reward)) {
            return $this->sendError(__('messages.rewardNotFound'));
        }


        $reward->image = $input['image'];
        $reward->name = $input['name'];
        $reward->ar_name = $input['ar_name'] ?? "" ;
        $reward->quantity_points = $input['quantity_points'];

        $reward->save();

        return $this->sendResponse(new RewardResource($reward), __('messages.rewardSucessUpdated'));
    }


    /**
     * @OA\Delete(
     *      path="/api/reward/{id}",
     *      operationId="7-remove-reward",
     *      tags={"Reward"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="delete reward",
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
        $reward = Reward::find($id);
        if (is_null($reward)) {
            return $this->sendError(__('messages.rewardNotFound'));
        }

        $reward->delete();

        return $this->sendResponse([], __('messages.imageSucessDeleted'));
    }


}
