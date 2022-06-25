<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\TextUI\XmlConfiguration\Group;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['namespace'=>'/Api'] , function( ){
    Route::post('/login' , [AuthController::class , 'login']);
    Route::post('/regester' , [AuthController::class , 'regester']);
   Route::post('/logout' , [AuthController::class , 'logout']);
});




Route::get('comments/' ,[CommentController::class , 'comments']);

Route::group(['middleware'=>'JWT_CHECK' ] , function (){
   
    Route::post('posts/create' , [PostController::class , 'create']);
    Route::post('posts/edit' , [PostController::class , 'update'])->middleware('USER_CHECK');
    Route::post('posts/delete' , [PostController::class , 'delete_post'])->middleware('USER_CHECK');
    Route::post('posts/' ,[PostController::class , 'Posts']);

    // comments
    Route::post('commentsOF' , [CommentController::class , "commentsOF"]);
    Route::post('comments/create' , [CommentController::class , 'create']);
    Route::post('comments/edit' , [CommentController::class , 'update'])->middleware('COMMENT_CHECK');
    Route::post('comments/delete' , [CommentController::class , 'delete'])->middleware('COMMENT_CHECK');

   // likes 
   Route::post('/like' , [LikeController::class ,'like']);
    Route::post('/userInfo' ,[AuthController::class , "userInfo"] );

});