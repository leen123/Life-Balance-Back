<?php

namespace App\Http\Middleware;

use App\Gym;
use App\Http\Resources\GymLite;
use App\Http\Resources\User;
use App\Utils\Helper;
use Closure;
use function App\Utils\Helper;

class CheckManagerAPi
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (Helper::checkIsManager()) {
            return $next($request);
        }

        $response = [
            'success' => false,
            'message' => 'no permission , login as Manager',
        ];

        return response()->json($response, 403);
    }
}
