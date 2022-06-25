<?php

namespace App\Http\Middleware;

use App\Models\Comment;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentMIddleware
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
        
        $comment = Comment::where('id', $request->id)->first();
        try {
            if ($comment->user_Id != Auth::user()->id) {
                return response()->json(['success' => false, 'message' => 'غير مسموح لك  بهده العملية ايا قود -انا هو ال كومنت ميدلوار و الله ما تفوتها ايا ']);
            }
        }catch (Exception $e) {
            return response()->json(['success'=>false  , 
            'err' => 'Ereur this comment does not exist']);
        }

        return $next($request);
    }
}
