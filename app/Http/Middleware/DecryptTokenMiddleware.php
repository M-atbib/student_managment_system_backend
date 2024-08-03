<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class DecryptTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        try {
            $encryptedToken = $request->bearerToken();
            if($encryptedToken){
                $decryptedToken = base64_decode(Crypt::decrypt($encryptedToken));

                $request->headers->set('Authorization', 'Bearer ' . $decryptedToken);
            }
            
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
