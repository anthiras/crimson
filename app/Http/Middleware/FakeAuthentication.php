<?php

namespace App\Http\Middleware;

use App\Persistence\UserModel;
use Closure;
use Illuminate\Support\Facades\Auth;

class FakeAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $someUser = UserModel::query()->first();
        if ($someUser != null) {
            Auth::login($someUser);
        }

        return $next($request);
    }
}
