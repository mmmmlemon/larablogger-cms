@extends('layouts.app') @section('content')

<div class="container white-bg">
    
    <div class="columns">
        <div class="column is-4">
            <a href="control/create_post" class="button is-link is-medium" data-tooltip="Create new post">
                <span class="icon">
            <i class="fas fa-pen"></i>
          </span>
                <span>Create post</span>
            </a>
        </div>

    </div>
    <div class="tabs is-boxed is-centered is-medium">
        <ul>
            <li class="is-active current-tab" id="settings_tab" onclick="change_tab('settings_content','settings_tab');">
                <a href="#settings">
                    <span class="icon is-small"><i class="fas fa-cog" aria-hidden="true"></i></span>
                    <span>Settings</span>
                </a>
            </li>
 
            <li id="design_tab" onclick="change_tab('design_content','design_tab');">
                <a href="#design">
                    <span class="icon is-small"><i class="fas fa-paint-brush" aria-hidden="true"></i></span>
                    <span>Design</span>
                </a>
            </li>

            <li id="users_tab" onclick="change_tab('users_content','users_tab');">
              <a href="#users">
                  <span class="icon is-small"><i class="fas fa-user" aria-hidden="true"></i></span>
                  <span>Users</span>
              </a>
          </li>

            <li id="profile_tab" onclick="change_tab('profile_content','profile_tab');">
                <a href="#profile">
                    <span class="icon is-small"><i class="fas fa-at"></i></span>
                    <span>My Profile</span>
                </a>
            </li>

            <li id="posts_tab" onclick="change_tab('posts_content','posts_tab');">
                <a href="#posts">
                    <span class="icon is-small"><i class="fas fa-file"></i></span>
                    <span>Posts</span>
                </a>
            </li>
        </ul>
    </div>

    @yield('settings', View::make('control_panel/settings', compact('settings', 'social_media')))

    @yield('users', View::make('control_panel/users', compact('users')))

    @yield('design', View::make('control_panel/design'))

    @yield('profile', View::make('control_panel/profile', compact('current_user')))

    @yield('posts', View::make('control_panel/posts'))


</div>

@endsection @push('scripts')
<script src="{{ asset('js/control_panel.js') }}"></script>
@endpush