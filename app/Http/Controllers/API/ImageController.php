<?php

namespace App\Http\Controllers\API;

use App\Images;
use Illuminate\Http\Request;
use App\Http\Requests\ImageRequest;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;


use App\Http\Resources\Image as ImageResource;

class ImageController extends BaseController
{


    /**
     * @OA\Post(
     ** path="/api/images",
     *   tags={"Images & Icons"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Crete Images  ",
     *   operationId="create-Images-data",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass Images data",
     *    @OA\JsonContent(
     *       required={"image"},
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
    public function store(ImageRequest $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $image = new Images();

        $image->image = $input['image'];

        $image->save();

        return $this->sendResponse(new ImageResource($image), __('messages.imageSucessUpdated'));
    }




    /**
     * @OA\Get(
     *      path="/api/images",
     *      operationId="4-getImageList",
     *      tags={"Images & Icons"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of Images",
     *      description="Returns list of Images",
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
        $sections = Images::all();
        return $this->sendResponse(ImageResource::collection($sections), __('messages.imageSucessRetrieved'));
    }


    /**
     * @OA\Get(
     *      path="/api/images/{id}",
     *      operationId="5-get-images",
     *      tags={"Images & Icons"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get images",
     *      description="Returns images information",
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
        $section = Images::find($id);
        if (is_null($section)) {
            return $this->sendError(__('messages.imageNotFound'));
        }
        return $this->sendResponse(new ImageResource($section), __('messages.imageSucessRetrieved'));
    }


    /**
     * @OA\Put(
     ** path="/api/images/{id}",
     *   tags={"Images & Icons"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Update",
     *   operationId="6-Update-images",
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
    public function update($id, ImageRequest $request)
    {

        $input = $request->all();

        $section = Images::find($id);

        $validator = Validator::make($input, [
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }


        $section->image = $input['image'];

        $section->save();

        return $this->sendResponse(new ImageResource($section), __('messages.imageSucessUpdated'));
    }


    /**
     * @OA\Delete(
     *      path="/api/images/{id}",
     *      operationId="7-remove-images",
     *      tags={"Images & Icons"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="delete images",
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
        $section = Images::find($id);
        if (is_null($section)) {
            return $this->sendError(__('messages.imageNotFound'));
        }

        $section->delete();

        return $this->sendResponse([], __('messages.imageSucessDeleted'));
    }


}
