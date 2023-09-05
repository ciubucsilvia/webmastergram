@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Email Verification') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('verify/email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="verification_code" class="col-md-4 col-form-label text-md-end">{{ __('Verification code') }}</label>

                            <div class="col-md-6">
                                <input id="verification_code" 
                                    type="text" 
                                    class="form-control" 
                                    name="verification_code" 
                                    value="{{ old('verification_code') }}" 
                                    required 
                                    autofocus>

                                @if ($errors->has('verification_code'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('verification_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
