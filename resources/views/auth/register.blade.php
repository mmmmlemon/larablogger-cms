@extends('layouts.app')

@section('content')
<div class="container white-bg">
    <h1 class="title">Register</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="label">Username</label>

                            <div class="control has-icons-left">
                                <input id="name" type="text" class="input form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-user"></i>
                                  </span>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="form-group row">
                            <label for="email" class="label">{{ __('E-Mail Address') }}</label>

                            <div class="control has-icons-left">
                                <input id="email" type="email" class="input form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-at"></i>
                                </span>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="label">{{ __('Password') }}</label>

                            <div class="control has-icons-left">
                                <input id="password" type="password" class="input form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-key"></i>
                                </span>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="label">{{ __('Confirm Password') }}</label>

                            <div class="control has-icons-left">
                                <input id="password-confirm" type="password" class="input form-control" name="password_confirmation" required autocomplete="new-password">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-key"></i>
                                </span>
                            </div>
                        </div>
                        <br>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="button is-link">
                                    <span class="icon">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </span>
                                    <span>
                                        Register
                                    </span>
                                </button>
                                <button type="reset" class="button is-link is-light">Reset</button>
                            </div>
                        </div>
                    </form>
        </div>
    </div>
</div>
@endsection
