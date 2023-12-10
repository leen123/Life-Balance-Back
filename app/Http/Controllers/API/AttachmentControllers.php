<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Gym as GymResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

//use Validator;

class AttachmentControllers extends BaseController
{


    /**
     * @OA\Post(
     ** path="/api/save-file",
     *   tags={"Attachments"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="upload file to system",
     *   operationId="upload-file",
     * @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="file to upload",
     *                     property="file",
     *                     type="file",
     *                ),
     *                 required={"file"}
     *             )
     *         )
     *     ),
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

    public function uploadFile(Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 500);
        }
        $name = str_replace(' ', '-', pathinfo($request->file->getClientOriginalName(), PATHINFO_FILENAME));
        $name = preg_replace('/[^A-Za-z0-9\-]/', '', $name);
        $name = preg_replace('/-+/', '-', $name);
        $fileName = $name . '_' . time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $fileName);
        $url = asset('uploads/' . $fileName);
        return $this->sendResponse(["fileName" => $fileName, "url" => $url], __('messages.uploadSucessfile'));

    }

}
