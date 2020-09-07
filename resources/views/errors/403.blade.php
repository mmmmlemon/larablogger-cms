@extends('layouts.app')
@section('title', 'Error 403'." -")
@section('content')
    <div class="container white-bg">
        <div class="column zoom-in has-text-centered">
            <h1 class="title post_title">403</h1>
            <span class="icon is=large">
                <i class="fas fa-3x fa-exclamation-triangle"></i>
            </span>
            <h1 class="subtitle">You are not allowed to perform this action or enter this page.</h1>
            <a href="/" class="button is-link">
                Go to the home page
            </a>
        </div>
    </div>
@endsection