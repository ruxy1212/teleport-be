<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Helpers\ResponseHelper;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try { 
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return ResponseHelper::response("Unauthorized", "Token is Invalid, Log in again", null, 401);
            } else if ($e instanceof TokenExpiredException) {
                return ResponseHelper::response("Unauthorized", "Token is Expired, Log in again", null, 401);
            } else {
                return ResponseHelper::response("Unauthorized", "Authorization Token not found, Log in again", null, 401);
            }
        }
        return $next($request);
    }
}