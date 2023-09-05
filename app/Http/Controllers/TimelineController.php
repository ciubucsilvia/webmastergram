<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TimelineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified.email');
    }

    public function index()
    {
        $user = Auth::user();
        $title = 'Timeline';
        
        $posts = $user->posts;
        
        foreach($user->follower as $follower)
        {
            foreach($follower->follow->posts as $post) {
                $posts->add($post);
            } 
        }

        $posts->sortDesc();
        
        return view('timeline', compact('title', 'posts', 'user'));
    }
}
