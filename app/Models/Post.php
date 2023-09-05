<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'description',
        'user_id',
        'likes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userPost()
    {
        return $this->belongsToMany(User::class, 'user_post_likes');
    }

    public function hasLike($user)
    {
        $filteredUser = $this->userPost->filter(function($postUser) use ($user) {
            return ($postUser->id == $user->id);
        });

        return $filteredUser->isNotEmpty();
    }

    public function addLike()
    {
        // $this->likes += 1;
        $this->increment('likes');
        $this->save();
        
    }

    public function removeLike()
    {
        // $this->likes -= 1;
        
        $this->decrement('likes');
        $this->save();
        
    }
}
