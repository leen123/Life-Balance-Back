<?php

namespace App\Http\Controllers\Api;

use App\Coupon;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\CouponResource;
use App\Http\Resources\MyCouponResource;
use App\User as AppUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CouponController extends BaseController
{
    public function index()
    {
        $userId = auth()->id();

        $coupons = Coupon::available($userId)->with('company')->get();

        return $this->sendResponse(CouponResource::collection($coupons), __('messages.couponSucessRetrieved'));
    }


    public function consume(Request $request)
    {

        try {
            DB::beginTransaction();

            $userId = auth()->id();
            $user = AppUser::find($userId);
            $code = $request->code;

            $coupon = Coupon::available($userId)->where('code', $code)->first();

            if (isset($coupon) && $user->points >= $coupon->points) {

                // assign coupon to user
                $newUserPoints = $user->points - $coupon->points;
                $user->coupons()->attach($coupon->id, ['points' => $coupon->points]);
                $user->update(['points' => $newUserPoints]);

                // consume coupon  (update max uses)
                $newMaxUses = $coupon->max_uses - 1;
                $coupon->update(['max_uses' => $newMaxUses]);

                DB::commit();
                return $this->sendSuccess(__('messages.CouponConsumeSuccess'));
            }

            return $this->sendError([], __('messages.validateCouponCodeError'), 500);
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->sendError([], __('messages.validateCouponCodeError'), 500);
        }
    }

   
    public function myCoupons()
    {
        $userId = auth()->id();
        $user = AppUser::find($userId);

        return $this->sendResponse(MyCouponResource::collection($user->coupons), __('messages.couponSucessRetrieved'));
    }
}
