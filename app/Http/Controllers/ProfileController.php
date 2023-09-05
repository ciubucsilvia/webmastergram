<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\ProfileUpdateRequest;
use App\Jobs\NewFollowerEmail;
use App\Models\Follow;
use App\Models\User;

use App\Repositories\CoreRepository;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
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
        $title = 'Mein Profil';
        $user = Auth::user();
        $posts = $this->getPostsDesc($user);
        
        return view('profile.index', compact('title', 'user', 'posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        $user = User::find($id);
        $title = 'Profil user ' . $user->username;
        $posts = $this->getPostsDesc($user);
        
        return view('profile.show', compact('title', 'user', 'posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request, string $id)
    {
        $user = User::find($id);
        $image = $request->file('image');
        $user->update($request->all());

        if(isset($image)) {
            $fileName = $user->username . '.' . $image->extension();

            $directory = $this->repository->getPath($this->repository->getUserPath('images'));
            
            $oldImage = Storage::allFiles($directory);
            Storage::delete($oldImage);

            $user->image = $this->repository->getUserPath('images') . '/' . $fileName;    
            $user->save();
            
            $image->storeAs($directory, $fileName);
        }
        return redirect()->route('profile.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        
        if($search) {
            $user = User::all()
                ->where('username', $search)
                ->where('id', '!=', auth()->user()->id)
                ->first();
        
            return view('profile.search', compact('user', 'search'));
            
        }
        return redirect()->route('profile.index');
    }

    public function follow(User $user)
    {
        $follow = Follow::create([
            'follower_id' => Auth::user()->id,
            'follow_id' => $user->id
        ]);

        dispatch(new NewFollowerEmail());
    
        return redirect()->route('profile.index');
    }

    public function unfollow(User $user)
    {
        DB::delete('DELETE FROM follows where follower_id = ? and follow_id = ?', [
            Auth::user()->id,
            $user->id
        ]);
        return redirect()->route('profile.index');
    }

    protected function getPostsDesc($user)
    {
        return $user->posts->sortByDesc('created_at');
    }
}
