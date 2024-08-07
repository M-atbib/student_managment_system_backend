<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Cookie;

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

        if ($response instanceof \Illuminate\Http\Response) {
            $cookie = $response->headers->getCookies()[0];

            $secure = config('session.secure') ?? false;
            $sameSite = config('session.same_site') ?? 'Lax';

            $newCookie = new Cookie(
                $cookie->getName(),
                $cookie->getValue(),
                $cookie->getExpiresTime(),
                $cookie->getPath(),
                $cookie->getDomain(),
                $secure,
                $cookie->isHttpOnly(),
                false,
                $sameSite
            );

            $response->headers->setCookie($newCookie);
        }

        return $response;
    }
}
