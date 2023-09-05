<?php

namespace App\Models;

use App\Http\Controllers\Auth\MustVerifyEmail as AuthMustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use AuthMustVerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function oauth()
    {
        return $this->hasOne(SocialAuth::class);
    }

    public function emailVerification()
    {
        return $this->hasOne(EmailVerification::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function follower()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function follows()
    {
        return $this->hasMany(Follow::class, 'follow_id');
    }

    public function postLikes()
    {
        return $this->belongsTo(User::class, 'user_post_likes');
    }

    public function isFollow($user_id)
    {
        if($this->follows) {
            $result = $this->follows->filter(function($follow) use ($user_id) {
                return ($follow->follower_id == $user_id);
            });
            
            return $result->isNotEmpty();
        }
        return false;
    }
}
