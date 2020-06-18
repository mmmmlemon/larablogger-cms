@extends('layouts.app')

@section('content')
<div class="container white-bg">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="column">
                <div class="title">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="label">{{ __('E-Mail Address') }}</label>

                            <div class="control has-icons-left">
                                <input id="email" type="email" class="input" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-at"></i>
                                  </span>
                                @error('email')
                                    <span class="help is-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="label">{{ __('Password') }}</label>

                            <div class="control has-icons-left">
                                <input id="password" type="password" class="input" name="password" required autocomplete="new-password">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-key"></i>
                                </span>
                                @error('password')
                                    <span class="help is-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="label">{{ __('Confirm Password') }}</label>

                            <div class="control has-icons-left">
                                <input id="password-confirm" type="password" class="input" name="password_confirmation" required autocomplete="new-password">
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
                                        <i class="fas fa-undo-alt"></i>
                                    </span>
                                    <span>{{ __('Reset Password') }}</span>
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
