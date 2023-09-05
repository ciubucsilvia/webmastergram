@extends('layouts.app')

@section('content')
    @if(isset($user))
    <div class="col-md-3">
        <div class="card" style="width: 18rem;">
            <img src="{{ asset('storage/' . $user->image) }}" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title"><a href="{{ route('profile.show', $user->id) }}">{{ $user->username }}</a></h5>
                @if($user->isFollow(auth()->user()->id))
                    <a href="{{ route('unfollow', $user->id) }}" class="btn btn-primary">unfollow</a>
                @else
                    <a href="{{ route('follow', $user->id) }}" class="btn btn-primary">follow</a>
                @endif
            </div>
        </div>
    </div>
    @else 
        <p>User {{ $search }} not found!</p>
        <p><a href="{{ route('profile.index') }}">Mein Profil</a></p>
    @endif
@endsection