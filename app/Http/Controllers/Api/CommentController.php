<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CommentController extends Controller
{
    //


    public function commentsOf(Request $request)
    {
        try {
            // $comments = new Comment();
            $comments = DB::select("SELECT * FROM comments WHERE comments.post_Id = ? ", [ $request->post_id]);
            foreach ($comments as $comment) {
                $user = DB::select("SELECT users.id , users.name ,users.last_Name , users.photo FROM users where users.id  = ?", [$comment->user_Id]);
                $comment->user = $user[0];
            }
            return response()->json(["success" => true, "comments" => $comments]);
        } catch (Exception $e) {
            return response()->json(["success" => false, "message" => $e]);
         
        }
    }




    public function create(Request $request)
    {
        $newCommment = new Comment();
        try {
            $newCommment->user_Id = Auth::user()->id;
            $newCommment->comment = $request->comment;
            $newCommment->post_Id = $request->post_id;

            $newCommment->save();
           // $user = User::where("id" , Auth::user()->id)->first();
           

          
            return response()->json(['success' => true,'comment' => $newCommment]);
        } catch (Exception $exp) {
            return response()->json([
                'success' => false,
                'message' => $exp
            ]);
        }
    }
    public function update(Request $request)
    {
        $comment = Comment::where('id', $request->id)->first();

        try {
            if ($comment->user_Id != Auth::user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'unautherized .'
                ]);
            }
            $comment->comment = $request->comment;
            $comment->update();

            return response()->json([
                'success' => true,
                'message' => 'UPDATED SUCCESFULY'
            ]);
        } catch (Exception $exp) {
            return response()->json([
                'success' => false,
                'message' => 'something Wrong [Probebly this comment does not exist]   '
            ]);
        }
    }

    public function delete(Request $request)
    {
        $comment = Comment::where('id', $request->id)->first();

        try {
         
            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => ' Comment Deleted'
            ]);


            return response()->json(['message' => 'Delete Comment']);
        } catch (Exception $e) {
            return response()->json([
                'success' => 'false',
                'message' => 'commmnt does not exist in DB'
            ]);
        }
    }

    public function comments(Request $request)
    {
        return $comments = Comment::where('post_Id', $request->post_id)->get();
    }
}
