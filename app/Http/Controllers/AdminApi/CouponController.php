<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Coupon;
use Illuminate\Validation\Rule;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\AdminRequests\CouponRequest;
use App\Http\Requests\AdminRequests\UpdateCouponRequest;
use App\Http\Resources\AdminResources\CouponResource;
use App\Traits\CheckPermission;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CouponController extends BaseController
{
    use CheckPermission;
    /**
     * @OA\Get(
     *      path="/api/admin/coupon",
     *      operationId="1-get-coupon",
     *      tags={"coupons"},
     *      security={
     *          {"bearer_token":{}},
     *      },
     *      summary="Get list of coupon",
     *      description="Returns list of coupon",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
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
     *          description="Not Found"
     *      )
     * )
     */
    public function index()
    {
        $this->canDo('view-coupons');

        $coupon = Coupon::paginate(10);

        return $this->sendResponse(CouponResource::collection($coupon), __('messages.couponSucessRetrieved'));
    }

    /**
     * @OA\Post(
     *     path="/api/admin/coupon",
     *     tags={"coupons"},
     *     security={
     *         {"bearer_token":{}},
     *     },
     *     summary="Create coupon",
     *     operationId="2-create-coupon-data",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass coupon data",
     *         @OA\JsonContent(
     *             required={"name", "description","code", "type" , "value" , "max_uses" , "starts_at" , "ends_at" , "active" , "company_id"},
     *             @OA\Property(property="name", type="string", example="String"),
     *             @OA\Property(property="ar_name", type="string", example="تجربة"),
     *             @OA\Property(property="description", type="string", example="hello"),
     *             @OA\Property(property="ar_description", type="string", example="تجربة"),
     *             @OA\Property(property="code", type="string", example="5a5a"),
     *             @OA\Property(property="type", type="string" , example="fixed"),
     *             @OA\Property(property="value", type="double" , example=3000),
     *             @OA\Property(property="max_uses", type="integer" , example=3),
     *             @OA\Property(property="starts_at", type="dateTime" , example="2023-04-18 00:43:3"),
     *             @OA\Property(property="ends_at", type="dateTime" , example="2023-04-19 00:43:3"),
     *             @OA\Property(property="active", type="boolean" , example=1),
     *             @OA\Property(property="company_id", type="number" , example=1),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     )
     * )
     */

    public function store(CouponRequest $request)
    {
        $this->canDo('add-coupon');

        $data = $request->all();

        $qr_path = 'Qrs/' . uniqid() . '.svg';

        QrCode::generate($data['code'], public_path($qr_path));

        $coupon = Coupon::create(array_merge($data, ['QR' => $qr_path]));

        return $this->sendResponse(new CouponResource($coupon), __('messages.couponSucessCreated'));
    }


    /**
     * @OA\Get(
     *      path="/api/admin/coupon/{id}",
     *      operationId="3-get-coupon",
     *      tags={"coupons"},
     *      security={
     *          {"bearer_token":{}},
     *      },
     *      summary="Get coupon",
     *      description="Returns coupon information",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *      ),
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
    public function show($id)
    {
        $this->canDo('show-coupon');

        $coupon = Coupon::find($id);
        if (is_null($coupon)) {
            return $this->sendError('coupon not found.');
        }

        return $this->sendResponse(new CouponResource($coupon), __('messages.couponSucessRetrieved'));
    }

    /**
     * @OA\Put(
     *     path="/api/admin/coupon/{id}",
     *     tags={"coupons"},
     *     security={
     *         {"bearer_token":{}},
     *     },
     *     summary="coupon Update",
     *     operationId="4-Update-full-coupon",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         description="Update User data",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="String"),
     *             @OA\Property(property="ar_name", type="string", example="تجربة"),
     *             @OA\Property(property="description", type="string", example="hello"),
     *             @OA\Property(property="ar_description", type="string", example="تجربة"),
     *             @OA\Property(property="code", type="string", example="5a5a"),
     *             @OA\Property(property="type", type="string", example="fixed"),
     *             @OA\Property(property="value", type="number", format="double", example=3000),
     *             @OA\Property(property="max_uses", type="integer", example=3),
     *             @OA\Property(property="starts_at", type="string", format="date-time", example="2023-04-18 00:43:3"),
     *             @OA\Property(property="ends_at", type="string", format="date-time", example="2023-04-19 00:43:3"),
     *             @OA\Property(property="active", type="boolean", example=true),
     *             @OA\Property(property="company_id", type="number", example=1)
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     )
     * )
     **/
    public function update(CouponRequest $request, $id)
    {
        $this->canDo('edit-coupon');

        $data = $request->all();

        $coupon = Coupon::find($id);
        if (is_null($coupon)) {
            return $this->sendError('coupon not found.');
        }

        if ($request->code != $coupon->code) {
            $qr_path = 'Qrs/' . uniqid() . '.svg';
            QrCode::generate($data['code'], public_path($qr_path));            
            unlink($coupon->QR);

            $data = array_merge($data, ['QR' => $qr_path]);
        }

        
        $coupon->update($data);

        return $this->sendResponse(new CouponResource($coupon), __('messages.couponSucessUpdated'));
    }

    /**
     * @OA\Delete(
     *      path="/api/admin/coupon/{id}",
     *      operationId="5-remove-coupon",
     *      tags={"coupons"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="delete coupon",
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
        $this->canDo('delete-coupon');

        $coupon = Coupon::find($id);
        if (is_null($coupon)) {
            return $this->sendError('coupon not found.');
        }

        try {

            $coupon->delete();
            unlink($coupon->QR);

            return $this->sendResponse([], __('messages.couponSucessDeleted'));
        } catch (\Throwable $e) {

            return $this->sendError(__('messages.couponcanNotDelete'));
        }
    }
}
