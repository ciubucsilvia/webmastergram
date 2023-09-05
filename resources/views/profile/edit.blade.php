@extends('layouts.app')

@section('content')
<div class="card-header"><h2>{{ __('Update your profile ') . $user->name }} </h2></div>

<form 
	action="{{ route('profile.update', $user->id) }}" 
	method="POST"
	enctype="multipart/form-data">
	@csrf
	@method('PUT')
  <div class="mb-3">
    <label for="username" class="form-label">Username</label>
    <input type="text" 
    	value="{{ old('username', $user->username) }}"
    	class="form-control" 
    	id="username" 
    	name="username"
    	aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="image" class="form-label">Image</label>
    <input type="file" 
    	class="form-control" 
    	value="{{ old('image', $user->image) }}"
    	id="image"
    	name="image" 
    	aria-describedby="fileHelp">
    <div id="fileHelp" class="form-text">Select Image, PNG, JPG, GIF up to 12MB</div>
  </div>
  
  <a href="{{ route('profile.index') }}" class="btn btn-light">Cancel</a>
  <button type="submit" class="btn btn-primary">update profile</button>
</form>
@endsection