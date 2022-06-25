<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    //
    public function like(Request $request) {
     
    //  try {$like = DB::select('SELECT * FROM likes WHERE likes.user_Id = 1 AND likes.post_Id = 4')
      try {

      $likesOFthisUserForThisPost = DB::select("SELECT * FROM likes WHERE likes.user_Id = ? AND likes.post_Id = ?" , [Auth::user()->id , $request->post_id]);
    
    if (count($likesOFthisUserForThisPost)>0){ 
         // $likesOFthisUserForThisPost[0]->delete();
          
        DB::delete("DELETE FROM likes WHERE likes.user_Id =? and likes.post_Id = ?" , [Auth::user()->id , $request->post_id]);
         return response()->json(0);

    }

        $like = new Like();    

          $like->user_Id = Auth::user()->id;
          $like->post_Id = $request->post_id;
          $like->save();
          return response()->json(1);

     }        
     catch (Exception $e){
         return response()->json(['success'=>false , 'message'=>$e.'']);
         
     }



    } 

    public function isLike(Request $request) {
         $likesOFthisUserForThisPost = Like::where('user_Id' , Auth::user()->id)->get()->where('post_Id' , $request->post_id);
        return response ()->json(["like" =>count($likesOFthisUserForThisPost) != 0]);
      }


} 
