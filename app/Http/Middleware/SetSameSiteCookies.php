<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class SetSameSiteCookies
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
        $response = $next($request);

        if (method_exists($response, 'withCookie')) {
            $response->withCookie(cookie('XSRF-TOKEN', $request->cookie('XSRF-TOKEN'), 0, null, null, true, true, false, 'None'));
        }

        return $response;
    }
}

