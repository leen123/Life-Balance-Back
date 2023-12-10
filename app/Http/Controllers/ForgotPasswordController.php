<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\CheckResetPasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\SendOtpResetPassword;
use App\OtpResetPassword;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends BaseController
{

    public function sendOtpCode(ForgotPasswordRequest $request)
    {
       try {
            // delete all previous otp codes 
            OtpResetPassword::where('email', $request->email)->delete();

            // generate new otp code 
            $otpCode = mt_rand(100000, 999999);

            // add otp to DB
            OtpResetPassword::create(['email' => $request->email, 'code' => $otpCode, 'expires_at' => Carbon::now()->addHour()]);

            // send mail to user
            Mail::to($request->email)->send(new SendOtpResetPassword($otpCode));

            return $this->sendSuccess(__('messages.OtpSuccesSent'));
        } catch (\Throwable $e) {
            logger('Error while sending reset password otp',[$e]);
            return $this->sendError([],__('messages.OtpErrorSending'),500);
        }
    }


    public function validateOtpCode(CheckResetPasswordRequest $request)
    {
        $now = Carbon::now();
        $otpData = OtpResetPassword::where(['email' => $request->email, 'code' => $request->code])->first();

        if (isset($otpData) && $now->isAfter($otpData->expires_at)) {
            $otpData->delete();
            return $this->sendError([],__('messages.validateOtpCodeError'),500);
        } else {
            return $this->sendSuccess(__('messages.validateOtpCodeSuccess'));
        }
    }


    public function resetPassword(ResetPasswordRequest $request)
    {
        $now = Carbon::now();
        $otpData = OtpResetPassword::where(['email' => $request->email, 'code' => $request->code])->first();

        if (isset($otpData) && $now->isAfter($otpData->expires_at)) {
            $otpData->delete();
            return $this->sendError([],__('messages.validateOtpCodeError'),500);
        } else {
            $user = User::where('email', $request->email)->first();
            $user->update(['password' => Hash::make($request->password)]);
            $otpData->delete();

            return $this->sendSuccess(__('messages.ResetPasswordSuccess'));
        }
    }
}
