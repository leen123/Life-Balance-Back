<?php

namespace App\Http\Controllers\API;


use App\Enums\UserType;

use App\Http\Controllers\API\BaseController as BaseController;

use App\Http\Resources\User;
use App\Http\Resources\UserLite;

class ConfigController extends BaseController
{


    /**
     * @OA\Get(
     *      path="/api/configs",
     *      operationId="configs",
     *      tags={"Configs"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get All Configs",
     *      description="Returns list of configs",
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
        $user = request()->user();
        $data['user'] = new UserLite($user);
        return $this->sendResponse($data, __('messages.configSucessRetrieved'));
    }
}
