<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCheck
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
    
       try {
           if (Auth::user()->id != Post::where('id', $request->id)->first()->user_Id) {
               return response()->json(['succes'=>false ,
            'message'=>'غير مسموح لك بالدخول ايا قود ايا '
        ]);
           }
       }catch (Exception $exp){
           return response()->json(['success'=>false , 
           'message' => 'probebly post Does not exist in DB']);
       }
        return $next($request);
    }
}
