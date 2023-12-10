<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ad;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\AdminRequests\AdRequest;
use App\Http\Resources\AdminResources\AdResource;
use App\Traits\CheckPermission;
use App\Traits\Media;
use App\User;
use Spatie\Permission\Models\Role;

class AdController extends BaseController
{
    use Media, CheckPermission;


    /**
     * @OA\Get(
     *      path="/api/admin/ads",
     *      operationId="1-get-ads",
     *      tags={"ads"},
     *      security={
     *          {"bearer_token":{}},
     *      },
     *      summary="Get list of ads",
     *      description="Returns list of ads",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     * )
     */
    public function index()
    {
        $this->canDo('view-ads');

        $ads = Ad::paginate(10);

        return $this->sendResponse(AdResource::collection($ads),__('messages.retrievedSucessAds'));
    }


    /**
     * @OA\Post(
     ** path="/api/admin/ads",
     *   tags={"ads"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Crete ads  ",
     *   operationId="2-create-ads-data",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass ads data",
     *    @OA\JsonContent(
     *       required={"title", "description","url", "image" , "starts_at" , "ends_at" , "company_id" , "active"},
     *       @OA\Property(property="title", type="string", example="String"),
     *       @OA\Property(property="ar_title", type="string", example="تجربة"),
     *       @OA\Property(property="description", type="text", example="hello"),
     *       @OA\Property(property="ar_description", type="string", example="تجربة"),
     *       @OA\Property(property="url", type="text", example="https://www.youtube.com/watch?v=NLJERucsLRc"),
     *       @OA\Property(property="image", type="string" , example="test.png"),
     *       @OA\Property(property="starts_at", type="dateTime" , example="2023-04-18 00:43:3"),
     *       @OA\Property(property="ends_at", type="dateTime" , example="2023-04-19 00:43:3"),
     *       @OA\Property(property="active", type="boolean" , example=1),
     *       @OA\Property(property="company_id", type="number" , example=1),
     *
     *    ),
     *
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

     *)
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdRequest $request)
    {
        $this->canDo('add-ad');

        $ad = Ad::create($request->all());

        return $this->sendResponse(new AdResource($ad), __('messages.createdSucessAds'));
    }


    /**
     * @OA\Get(
     *      path="/api/admin/ads/{id}",
     *      operationId="3-get-ads",
     *      tags={"ads"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get ads",
     *      description="Returns ads information",
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
        $this->canDo('show-ad');

        $ad = Ad::find($id);
        if (is_null($ad)) {
            return $this->sendError('Ad not found.');
        }

        return $this->sendResponse(new AdResource($ad), __('messages.retrievedSucessAds'));
    }




    /**
     * @OA\Put(
     ** path="/api/admin/ads/{id}",
     *   tags={"ads"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="ads Update",
     *   operationId="4-Update-full-ads",
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
     *    description="Update Ads data",
     *      @OA\JsonContent(
     *       @OA\Property(property="title", type="string", example="String"),
     *       @OA\Property(property="ar_title", type="string", example="تجربة"),
     *       @OA\Property(property="description", type="text", example="hello"),
     *       @OA\Property(property="ar_description", type="string", example="تجربة"),
     *       @OA\Property(property="url", type="text", example="https://www.youtube.com/watch?v=NLJERucsLRc"),
     *       @OA\Property(property="image", type="string" , example="test.png"),
     *       @OA\Property(property="starts_at", type="dateTime" , example="2023-04-18 00:43:3"),
     *       @OA\Property(property="ends_at", type="dateTime" , example="2023-04-19 00:43:3"),
     *       @OA\Property(property="active", type="boolean" , example=1),
     *       @OA\Property(property="company_id", type="number" , example=1),
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

    public function update(AdRequest $request, $id)
    {
        $this->canDo('edit-ad');

        $ad = Ad::find($id);
        if (is_null($ad)) {
            return $this->sendError(__('messages.AdNotFound'));
        }

        $ad->update($request->all());

        return $this->sendResponse(new AdResource($ad), __('messages.updatedSucessAds'));
    }




    /**
     * @OA\Delete(
     *      path="/api/admin/ads/{id}",
     *      operationId="5-remove-ads",
     *      tags={"ads"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="delete ads",
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
        $this->canDo('delete-ad');

        $ad = Ad::find($id);
        if (is_null($ad)) {
            return $this->sendError(__('messages.AdNotFound'));
        }
        
        $ad->delete();

        return $this->sendResponse([], __('messages.deletedSucessAds'));
    }
}
