<?php

namespace App\Http\Controllers\API;

use App\Ad;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = Carbon::now();

        $ads = Ad::with('company')->where('active', true)
            ->where('starts_at', '<=', $now)->where('ends_at', '>=', $now)->get();

        return $this->sendResponse(AdResource::collection($ads), __('messages.retrievedSucessAds'));
    }
}
