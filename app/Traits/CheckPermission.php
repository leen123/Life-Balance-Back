<?php

namespace App\Traits;

use App\User;
use Illuminate\Support\Facades\Auth;

trait CheckPermission{

    public function canDo($permission){

        $user = User::find(Auth::id());
      
        if ($user->can($permission) == false) {
            abort(response()->json(['error' => 'You dont have this permission.'], 403));
        }
    }
}