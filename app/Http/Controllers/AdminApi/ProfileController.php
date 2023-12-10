<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\AdminRequests\UpdateProfileRequest;
use App\Http\Resources\AdminResources\ProfileResource;
use App\Traits\CheckPermission;
use App\Traits\Media;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends BaseController
{
    use Media,CheckPermission;


    public function update(UpdateProfileRequest $request)
    {
        $userId = auth()->id();

        $user = User::find($userId);

    
        if ($request->password) {
            $user->password = Hash::make($request->password);
            $user->ConfirmPassword =  Hash::make($request->password);
        }

        $user->email = $request->email;
        $user->name = $request->name;
        $user->userName = $request->user_name;
        $user->image = $request->image ?? null;

        $user->save();

        return $this->sendResponse(new ProfileResource($user), __('messages.profileUpdated'));
    }
}
