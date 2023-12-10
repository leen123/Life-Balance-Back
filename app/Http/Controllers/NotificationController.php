<?php

namespace App\Http\Controllers;

use App\FCM;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\NotificationResource;
use App\Notification;

class NotificationController extends BaseController
{
/**
 * @OA\Post(
 *      path="/api/update-fcm-token",
 *      operationId="addFCMToken",
 *      tags={"FCM Token"},
 *      summary="Add FCM token",
 *      description="Adds FCM token for the authenticated user",
 *      security={
 *          {"bearer_token": {}}
 *      },
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              @OA\Property(
 *                  property="token",
 *                  description="FCM token",
 *                  type="string",
 *              ),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Success",
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *      ),
 * )
 */
    public function add_fcm_token(Request $request)
    {

        $request->validate(['token' => ['required']]);

        $user_id = auth()->id();

        $token = FCM::where('token', $request->token)->where('user_id', $user_id)->first();

        if (!isset($token)) {

            $token = FCM::create(['token' => $request->token, 'user_id' => $user_id]);
        }


        return $this->sendSuccess(true,__('messages.fcmSucessUpdated'));

    }

/**
 * @OA\Get(
 *      path="/api/notifications",
 *      operationId="getNotifications",
 *      tags={"Notifications"},
 *      summary="Get user notifications",
 *      description="Retrieves notifications for the authenticated user",
 *      security={
 *          {"bearer_token": {}}
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="Success",
 *          @OA\JsonContent(ref="#/components/schemas/NotificationResource")
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 * )
 */
    public function index(){

        $user_id = auth()->id();

        $notifications = Notification::where('notifiable_id',$user_id)->get();

        return $this->sendResponse(NotificationResource::collection($notifications), __('messages.notificationSucessRetrieved'));

    }
}
