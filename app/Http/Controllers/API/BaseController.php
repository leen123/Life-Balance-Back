<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message , $badgeId = null , $rewordId = null)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
            'badge' => [
                'isOpenNewBadge' => !is_null($badgeId),
                'badgeId' => $badgeId
             ],
             'reword' => [
                 'isOpenNewReword' => !is_null($rewordId),
                 'rewordId' => $rewordId
              ],
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    public function sendSuccess($success, $successMessages = [], $code = 200 , $badgeId = null , $rewordId = null)
    {
        $response = [
            'success' => true,
            'message' => $success,
            'badge' => [
                'isOpenNewBadge' => !is_null($badgeId),
                'badgeId' => $badgeId
             ],
             'reword' => [
                 'isOpenNewReword' => !is_null($rewordId),
                 'rewordId' => $rewordId
              ],
        ];


        if (!empty($successMessages)) {
            $response['data'] = $successMessages;
        }


        return response()->json($response, $code);
    }
}
