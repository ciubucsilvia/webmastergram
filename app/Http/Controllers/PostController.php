<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostCreateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Repositories\CoreRepository;

class PostController extends Controller
{
    protected $repository;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified.email');
        $this->repository = app(CoreRepository::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateRequest $request)
    {
        $data = $request->all();

        $image = $request->file('image');
        if(isset($image)) {
            $fileName = Str::random(10) . '.' . $image->extension();
            $directory = $this->repository->getPath($this->repository->getUserPath('posts'));
            
            $post = new Post();
            $post->description = $data['description'];
            $post->user_id = auth()->user()->id;
            $post->image = $this->repository->getUserPath('posts') . '/' . $fileName;    
            $post->save();
            
            $image->storeAs($directory, $fileName);
        }
        return redirect()->route('profile.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }

    public function like($post_id, $user_id)
    {
        $post = Post::find($post_id);
        $user = User::find($user_id);

        $hasLike = $post->hasLike($user);
        
        if($hasLike) {
            $post->userPost()->detach(['user_id' => $user_id]);
            $post->removeLike();
        }  
        $post->userPost()->sync(['user_id' => $user_id]);
        $post->addLike();

        return redirect()->route('home');
    }
}
