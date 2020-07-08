<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    protected function unauthenticated($request, array $guards)
    {
        return response()->json('Invalid Token', 403);
    }


//    public function handle($request, Closure $next, ...$guards)
//    {
//        if(!empty(trim($request->bearerToken()))){
//            return $next($request);
//        }
//        return response()->json('Invalid Token', 403);
//    }


//    /**
//     * Get the path the user should be redirected to when they are not authenticated.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return string|null
//     */
//    protected function redirectTo($request)
//    {
//        if (! $request->expectsJson()) {
//            return route('home');
//        }
//    }
}
