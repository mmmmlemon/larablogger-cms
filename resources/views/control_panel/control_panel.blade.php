
@extends('layouts.app') 

@section('content')
@php
$blank = ""; //empty character for value attribute in inputs
@endphp
<div class="container white-bg">
    <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li class="is-active">
              <a href="#" aria-current="page">Control panel</a>
            </li>
        </ul>
    </nav>

    @if(Auth::check() && Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
        <div class="columns">
            <div class="column is-12">
                <a href="control/create_post" class="button is-link is-medium" data-tooltip="Create new post">
                    <span class="icon">
                        <i class="fas fa-pen"></i>
                    </span>
                    <span>Add post</span>
                </a>

                <a href="control/posts" class="button is-link is-medium" data-tooltip="View/edit posts">
                    <span class="icon">
                        <i class="fas fa-file"></i>
                    </span>
                    <span>Posts</span>
                </a>

                <a href="control/media" class="button is-link is-medium" data-tooltip="Add/edit categories">
                    <span class="icon">
                        <i class="fas fa-video"></i>
                    </span>
                    <span>Media browser</span>
                </a>

                <a href="control/categories" class="button is-link is-medium" data-tooltip="Add/edit categories">
                    <span class="icon">
                        <i class="fas fa-list"></i>
                    </span>
                    <span>Categories</span>
                </a>
            </div>
        </div>
    @endif

    <div class="tabs is-boxed is-centered is-medium">
        <ul>
            @if(Auth::user()->user_type == 0)
            <li class="is-active current-tab" id="settings_tab" onclick="change_tab('settings_content','settings_tab');">
                <a href="#settings">
                    <span class="icon is-small"><i class="fas fa-cog" aria-hidden="true"></i></span>
                    <span>Settings</span>
                </a>
            </li>
            @endif
            
            @if(Auth::user()->user_type == 0)
            <li id="design_tab" onclick="change_tab('design_content','design_tab');">
                <a href="#design">
                    <span class="icon is-small"><i class="fas fa-paint-brush" aria-hidden="true"></i></span>
                    <span>Design</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->user_type == 0 || Auth::user()->user_type == 1)
            <li id="users_tab" onclick="change_tab('users_content','users_tab');">
              <a href="#users">
                  <span class="icon is-small"><i class="fas fa-user" aria-hidden="true"></i></span>
                  <span>Users</span>
              </a>
            </li>
            @endif

             <li id="profile_tab" onclick="change_tab('profile_content','profile_tab');">
                <a href="#profile">
                    <span class="icon is-small"><i class="fas fa-at"></i></span>
                    <span>My Profile</span>
                </a>
            </li>
        </ul>
    </div>

    @if(Auth::user()->user_type == 0)
        @yield('settings', View::make('control_panel/general/settings', compact('settings', 'social_media')))
        @yield('design', View::make('control_panel/general/design', compact('settings')))
    @endif
    @yield('users', View::make('control_panel/general/users', compact('users')))

    @yield('profile', View::make('control_panel/general/profile', compact('current_user')))
</div>
@endsection 

@push('scripts')
<script src="{{ asset('js/custom/shared/char_counter.js') }}"></script>
<script src="{{ asset('js/custom/control_panel/control_panel.js') }}"></script>
<script src="{{ asset('js/custom/control_panel/settings.js') }}"></script>
<script src="{{ asset('js/custom/control_panel/design.js') }}"></script>
<script src="{{ asset('js/custom/control_panel/profile.js') }}"></script>
@endpush

