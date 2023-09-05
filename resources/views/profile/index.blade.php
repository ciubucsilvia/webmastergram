@extends('layouts.app')

@section('content')
<div class="card-header"><h2>{{ $title }}</h2></div>

<div class="row">
    <div class="col-sm-3">
        
        <img src="{{ asset('storage/' . $user->image) }}" class="img-thumbnail float-left">
    </div>
    <div class="col-sm-3">
        <h2>{{ $user->username }}</h2>
        <a href="{{ route('profile.edit', $user->id) }}">edit profile</a>
        
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Posts</h5>
                <h3 class="card-subtitle mb-2 text-body-secondary">{{ $posts->count() }}</h3>
            </div>
        </div>

        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Follower</h5>
                <h3 class="card-subtitle mb-2 text-body-secondary">{{ $user->follower->count() }}</h3>
            </div>
        </div>

        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">follows</h5>
                <h3 class="card-subtitle mb-2 text-body-secondary">{{ $user->follows->count() }}</h3>
            </div>
        </div>

        <a href="{{ route('posts.create') }}" class="btn btn-primary">add post</a>
    </div>
</div>

@if($posts->isEmpty())
<p>No posts ...</p> 
@else 
<h3>Posts</h3>
<div class="row">
    @foreach($posts as $post)
        <div class="col-md-3">
            <div class="card" style="width: 18rem;">
                <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="...">
                <div class="card-body">
                <p class="card-text">{{ $post->description }}</p>
                <p><b>Likes:</b> {{ $post->likes }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>
    
@endif

@endsection
