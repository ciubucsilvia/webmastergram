<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'follower_id',
        'follow_id'
    ];

    public function follow()
    {
        return $this->belongsTo(User::class, 'follow_id');
    }
}
