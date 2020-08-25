@extends('layouts.app')
@section('title', 'Error 404'." -")
@section('content')
    <div class="container white-bg">
        <div class="column bounce-in has-text-centered">
            <h1 class="title post_title">404</h1>
            <span class="icon is=large">
                <i class="fas fa-3x fa-exclamation-triangle"></i>
            </span>
            <h1 class="subtitle">There's no such page on this web-site</h1>
            <a href="/" class="button is-link">
                Go to the home page
            </a>
        </div>
    </div>
@endsection