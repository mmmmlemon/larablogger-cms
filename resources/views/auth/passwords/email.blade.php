@extends('layouts.app')

@section('content')
<div class="container white-bg">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="column">
                <div class="title">{{ __('Reset Password') }}</div>

                <div class="card-body">
                
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="label">{{ __('E-Mail Address') }}</label>

                            <div class="control has-icons-left">
                                <input id="email" type="email" class="input" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-at"></i>
                                  </span>
                              
                            </div>
                            @if (session('status'))
                            <div class="help is-link" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif
                            @error('email')
                            <span class="help is-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
    
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <br>
                                <button type="submit" class="button is-link">
                                    <span class="icon">
                                        <i class="fas fa-arrow-right"></i>
                                    </span>
                                    <span>{{ __('Send Password Reset Link') }}</span>
                                    
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
