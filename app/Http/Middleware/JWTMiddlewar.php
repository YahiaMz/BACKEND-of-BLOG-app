<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTAuth as JWTAuthJWTAuth;

class JWTMiddlewar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {     
        
        $message = '';
        try {
            JWTAuth::parseToken()->authenticate();
            return $next($request);
        }catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $exp ){
            $message = 'Token Expired';
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $exp) {
            $message = 'Token Invalid';
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $exp) {
               $message = 'Provide TOken';
        }

        return response()->json(['success'=>false , 'message'=>$message]);
        
    }
}
