@extends('layouts.app')

@section('content')
<div class="card-header"><h2>{{ $title }}</h2></div>

<div class="row">
    <div class="col-md-9">
        @if($posts->isEmpty())
        <p>No posts ...</p> 
        @else 
        <h3>Posts</h3>
        <div class="row">
            <div class="col-md-9">
            @foreach($user->posts as $post)
                <div class="card mb-3">
                    <img class="card-img-top" src="{{ asset('storage/' . $post->image) }}" alt="Card image cap">
                    <div class="card-body">
                        @if($user->username !== $post->user->username)
                            <h5 class="card-title">{{ $post->user->username }}</h5>
                        @endif

                        <p class="card-text">{{ $post->description }}</p>
                        <p> {{ $post->created_at }}</p>

                        @if($user->username !== $post->user->username)
                            @if($post->hasLike($user))
                                <a href="{{ route('like', [$post->id, $user->id]) }}" class="btn btn-primary">Unlike</a>
                            @else
                                <a href="{{ route('like', [$post->id, $user->id]) }}" class="btn btn-primary">Like</a>
                            @endif
                        @endif
                        
                        <b>Likes:</b>  {{ $post->likes }}
                    </div>
                </div>
            @endforeach
            </div>
        </div>   
        @endif
    </div>

    <div class="col-md-3">
        <ul>
            @foreach($user->follower as $follower)
            <li><a href="{{ route('profile.show', $follower->follow_id) }}">{{ $follower->follow->username }}</a></li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
