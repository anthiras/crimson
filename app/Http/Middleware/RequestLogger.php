<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RequestLogger
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
        $method = strtoupper($request->getMethod());
        if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
            $userId = Auth::id();
            $uri = $request->getPathInfo();
            $bodyAsJson = json_encode($request->all());
            $message = "{$method} {$uri} {$userId} {$bodyAsJson}";

            Log::channel('requests')->info($message);
        }
        return $next($request);
    }
}
