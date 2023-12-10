<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Traits\CheckPermission;

class ActivityLogController extends BaseController
{
    use CheckPermission;
    /**
     * @OA\Get(
     *      path="/api/admin/activity_log",
     *      operationId="1-get-activity_log",
     *      tags={"Activity_log"},
     *      security={
     *          {"bearer_token":{}},
     *      },
     *      summary="Get list of activity_log",
     *      description="Returns list of activity_log",
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
        $this->canDo('view-activity-log');

        $logs = Activity::with('causer')->paginate(10);

        return response()->json(['data' => $logs], 200);
    }


     /**
     * @OA\Get(
     *      path="/api/admin/activity_log/{id}",
     *      operationId="3-get-activity_log",
     *      tags={"Activity_log"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get activity_log",
     *      description="Returns activity_log information",
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
        $this->canDo('show-activity-log');

        $log = Activity::with('causer')->where('id', $id)->first();
        if (is_null($log)) {
            return $this->sendError('Log Activity not found.');
        }

        return response()->json(['data' => $log], 200);
    }


  /**
     * @OA\Delete(
     *      path="/api/admin/activity_log/{id}",
     *      operationId="5-remove-activity_log",
     *      tags={"Activity_log"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="delete activity_log",
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
        $this->canDo('delete-activity-log');

        $log = Activity::find($id);
        if (is_null($log)) {
            return $this->sendError('Activity not found.');
        }

        $log->delete();

        return response()->json(['data' => null], 204);
    }


    /**
     * delete activity log.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete_log()
    {
        $this->canDo('clean-activity-log');

        Activity::truncate();

        return response()->json(['data' => null], 204);
    
    }
}
