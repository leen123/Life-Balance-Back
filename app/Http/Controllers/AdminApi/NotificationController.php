<?php

namespace App\Http\Controllers\AdminApi;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequests\NotificationRequest;
use App\Notifications\GeneralNotification;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Traits\CheckPermission;

class NotificationController extends BaseController
{
    use CheckPermission;
    /**
 * @OA\Post(
 *     path="/api/send-notifications",
 *     operationId="sendNotification",
 *     tags={"Notification"},
 *     summary="Send notification to players",
 *     description="Send a notification to all players",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/NotificationRequest")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Notification sent successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Invalid request")
 *         )
 *     ),
 *     security={
 *         {"bearer_token": {}}
 *     }
 * )
 */

    public function send(NotificationRequest $request){

        $this->canDo('manage-notifications');

        $users = User::where('type',UserType::player)->get();

        foreach($users as $user){

            $user->notify((new GeneralNotification(['title' => $request->title, 'description' => $request->description])));

        }


        return $this->sendSuccess(true,__('messages.notifactionSucessSent'));
    }
}
