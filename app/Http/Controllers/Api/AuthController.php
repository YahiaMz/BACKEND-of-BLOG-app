<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTAuth as JWTAuthJWTAuth;

class AuthController extends Controller
{

 public function userInfo(Request $request){

   $user = User::find(Auth::user()->id);
   
    if($request->name && $request->last_Name ) {
           $user->name = $request->name;
           $user->last_Name	 = $request->last_Name; 
           $photo = "";
               if($request->photo !="") {
                   $photo = time().".jpg";



                 //  file_put_contents("storage/profiles/".$photo , base64_decode($request->$photo));
                 $image = base64_decode($request->photo);
                 $fp = fopen("storage/profiles/".$photo,'wb+');
                 fwrite($fp,$image);
                 fclose($fp);

                   $user->photo = $photo;
               }
            $user->update();
            return response()->json(["success"=>true,  "message"=>"updated" , 
            "user"=>$user
            ]);
           
    }else return  response()->json(["success"=>false ,"message"=>"All data required"]);
}

    public function login(Request $request)
    {       
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([ "success"=>false ,
             'message' => 'invalid credinials'], 401);

        }

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => Auth::user()
        ]);
    }
    public function regester( Request $request){
        try {
            
            //     if ($request->password && $request->name) {
            //         $user = new User();
            //         // $user->name = $request->name;
            //         $user->email = $request->email;
            //         $user->password = Hash::make($request->password);
            //         $user->save();
            //         return $this->login($request);
            //         return response()->json(['success'=>true ,
            //         'new User' =>$user
            //    ]); }


             if( User::where("email" , $request->email)->first()){
                 return response()->json(["success"=>false , "message"=>"Email Exist"]);
             }
             if( !$request->email || !$request->password) {
                 return response(["message"=>false , "message"=>"email or pssword is Empty"]);
             }
             $new_User = new User();
             $new_User->email = $request->email;
             $new_User->password = Hash::make($request->password);

            $new_User->save();

            
             return $this->login($request);
            return response()->json(["succes"=>true , "newUser" =>  $new_User]);

        }catch (\Exception $exp) {
           return response()->json(['success'=>false , 'message' => $exp]);
        }   

        return response()->json(['success'=>false , 'message' => 'Something wrong']);
    }


    public function logout(Request $request) {
        try {
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
             return response()->json(['success'=>true , 
            'message'=>'Logout Succes'
            ]);
        } catch(Exception $exp){
            return response()->json(['success'=>false , 
            'message'=>$exp
        ]);
        }
    }
}
