<?php

namespace App\Http\Middleware;

use Closure;

class JsonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Automatically add json header if the user did not set it
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
