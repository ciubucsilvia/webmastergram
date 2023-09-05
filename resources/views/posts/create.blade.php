@extends('layouts.app')

@section('content')
<div class="card-header"><h2>{{ __('Create neu post ') }} </h2></div>

<form 
	action="{{ route('posts.store') }}" 
	method="POST"
	enctype="multipart/form-data">
	@csrf

  <div class="mb-3">
    <label for="image" class="form-label">Image</label>
    <input type="file" 
    	class="form-control" 
    	value="{{ old('image') }}"
    	id="image"
    	name="image" 
    	aria-describedby="fileHelp">
    <div id="fileHelp" class="form-text">Select Image, PNG, JPG, GIF up to 12MB</div>
  </div>

  <div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
  
  <a href="{{ route('profile.index') }}" class="btn btn-light">Cancel</a>
  <button type="submit" class="btn btn-primary">create post</button>
</form>
@endsection