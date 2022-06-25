<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    public function User(){
        return $this->belongsTo(User::class);
    }
    public function Likes( ){
        return $this->hasMany(Like::class);
    }
    public function Comments( ){
        return $this->hasMany(Comment::class);
    }
}
