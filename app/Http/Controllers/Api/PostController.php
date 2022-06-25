<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Exception;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostController extends Controller
{
    //
    public function Posts(){
       
       $allPosts = Post::orderBy('id' , 'desc')->get();
        //how much comment
       foreach ($allPosts as $post) {
        $post->user = User::where('id' , $post->user_Id)->first();
        $post->LikesCount= Like::where('post_Id' , $post->id)->count();
        $post->commnetsCount  = Comment::where('post_Id' , $post->id)->count();
        $post->comments = Comment::where('post_Id' , $post->id)->get();
        $post->selfLike = false;

       
       $sl = DB::select("SELECT * FROM likes where likes.user_Id = ? and likes.post_Id = ?" , [Auth::user()->id , $post->id]);
     if($sl){
          $post->selfLike = true;
           }

  
       }
       
              return response()->json(['success'=>true ,
        'posts' =>$allPosts
    ]); 
    }
    public function create(Request $request){
        
     try {
        $newPost = new Post();
        $newPost->user_Id = Auth::user()->id;
        $newPost->desc = $request->desc;
        
        if($request->photo != '') {
            
            $photo = time().'.jpg';
            $image = base64_decode( $request->photo );
            $fp = fopen("storage/posts/".$photo , 'wb+');
            fwrite($fp , $image);
            fclose($fp);

            $newPost->photo = $photo;
    
         }
                
        $newPost->save();

        $newPost->user;
            return response()->json(['succes'=>true ,
            'post' =>$newPost , 
           'meassage' =>'Post Created Succefully' 
        ]);

     }catch (Exception $exp){
         return response()->json(['success'=>false , 'err'=>$exp.'']);
     }

    }

 public function update(Request $request){
     $post = Post::where('id' , $request->id)->first();
     

     $post->desc = $request->desc;
         if($request->photo !="") {
             $photo = time().".jpg";
             $image = base64_decode($request->photo);
             $fp = fopen("storage/posts/".$photo , 'wb+');
             fwrite($fp , $image);
             fclose($fp);

         }     
     $post->update();
     return response()->json(['success'=>true , 
     'message' => 'Update Success' , 
     'post'=>$post
    ]);
 }
 
 public function delete_post(Request $request ){

        $id = $request->id;
         Post::where("id" , $request->id)->delete();
         return response()->json(["success"=>true , "message"=>"Deleted successfully"]);
 }
   
}
