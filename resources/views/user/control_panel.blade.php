@extends('layouts.app') @section('content')

<div class="container white-bg">
    <div class="columns">
        <div class="column is-4">
            <button class="button is-link is-medium">
                <span class="icon">
            <i class="fas fa-pen"></i>
          </span>
                <span>Create post</span>
            </button>
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
        </ul>
    </div>

    <!--НАСТРОЙКИ -->
    <div id="settings_content" class="current-content">
        
    <div class="columns">
        <div class="column is-12 has-text-centered">
            <span class="icon">
        <i class="fas fa-cog"></i>
      </span>
            <h3 class="subtitle">Web-site general settings</h3>

            <form action="control/update_settings" method="POST">
                @csrf
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Site title</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <p class="control is-expanded">
                                <input class="input" name="site_title" type="text" required placeholder="Web-site name" value="{{$settings->site_title}}">
                            </p>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Site subtitle</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <p class="control is-expanded">
                                <input class="input" name="site_subtitle" type="text" required placeholder="Subtitle" value="{{$settings->site_subtitle}}">
                            </p>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                  <div class="field-label is-normal">
                      <label class="label">Contact E-Mail</label>
                  </div>
                  <div class="field-body">
                      <div class="field">
                          <p class="control is-expanded">
                              <input class="input" name="contact_email" type="email" required placeholder="example@yourmail.com" value="{{$settings->contact_email}}">
                          </p>
                      </div>
                  </div>
              </div>

                <div class="field is-horizontal">
                    <div class="field-label">
                        <!-- Left empty for spacing -->
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <button type="submit" class="button is-link">
                                    <span class="icon">
                  <i class="fas fa-save"></i>
                </span>
                                    <span>
                  Save
                </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    

    <div class="columns">
        <div class="column is-12 has-text-centered">
            <span class="icon">
        <i class="fas fa-link"></i>
      </span>
            <h3 class="subtitle">Social media (Links)</h3> @php $count = 0; @endphp
            <form id="form_social" action="control/update_social/" method="POST">
                @csrf @foreach($social_media as $item)
                <div class="field is-horizontal" id="soc-media-field-{{$count}}">

                    <div class="field-label is-normal">
                        <label class="label"># {{$count+1}}</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <input class="input invisible" type="text" name="id_{{$count}}" value="{{$item->id}}">
                            <input class="input" type="text" placeholder="Web-site (or social media platform) name" name="platform_{{$count}}" maxlength="20" value="{{$item->platform_name}}">

                        </div>
                        <div class="field">
                            <input class="input" type="url" placeholder="https://web-site.com" name="url_{{$count}}" value="{{$item->url}}">
                        </div>
                    </div>
                </div>

                @php $count += 1; @endphp @endforeach

                <!--govnokod alert-->
                <!-- в элементе num_of_fields сохраняем количество полей для соцсетей, чтобы передать это значение при отправке формы -->
                <!-- при добавлении нового поля делаем +1 при помощи jQuery -->
                <div id="num_of_fields" class="invisible">{{$count}}</div>
                <div class="field is-horizontal">
                    <div class="field-label">
                        <!-- Left empty for spacing -->
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <button type="submit" class="button is-link">
                                    <span class="icon">
                  <i class="fas fa-save"></i>
                </span>
                                    <span>
                  Save
                </span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

    </div>

    <!-- ПОЛЬЗОВАТЕЛИ -->
    <div id="users_content" class="invisible">
        <div class="columns">
            <table class="table is-fullwidth is-hoverable is-striped">
                <thead>
                <th>
                Username  
                </th>
                <th>
                Email
                </th>
                <th>
                User Type
                </th>
                <th>
                Registered
                </th>
                <th>
                Actions
                </th>
                </thead>    
                <tbody>
                @foreach($users as $user)
                <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>
                    @if($user->user_type == 1)
                    <b>Admin</b>
                    @elseif($user->user_type == 0)
                    <b>Super Admin</b>
                    @else
                    User
                    @endif
                </td>
                <td>{{$user->created_at}}</td>
                <td>
                    @if($user->user_type == 1 && $user->id != Auth::user()->id)
                    <button class="button is-danger"  data-tooltip="Downgrade to User"><span class="icon is-small"
                        onclick="document.getElementById('change_user_{{$user->id}}').submit();">
                        <i class="fas fa-arrow-down"></i>
                      </span></button>
                    <form id="change_user_{{$user->id}}" action="control/change_user_type" method="POST" class="invisible">
                        @csrf
                    <input type="text" name="user_id" value="{{$user->id}}">
                    <input type="text" name="user_type" value="admin"> 
                    </form>
                    @elseif($user->user_type == 0 && $user->user_id != Auth::user()->id)   
                    <button class="button is-warning"  data-tooltip="This user is Super Admin"><span class="icon is-small">
                        <i class="fas fa-crown"></i>
                    </span></button>
                    @elseif($user->user_type == 2 && $user->id != Auth::user()->id)   
                    <button class="button is-primary"  data-tooltip="Upgrade to Admin"><span class="icon is-small"
                        onclick="document.getElementById('change_user_{{$user->id}}').submit();">
                        <i class="fas fa-arrow-up"></i>
                      </span></button>
                      <form id="change_user_{{$user->id}}" action="control/change_user_type" method="POST" class="invisible">
                        @csrf
                    <input type="text" name="user_id" value="{{$user->id}}">
                    <input type="text" name="user_type" value="user"> 
                    </form>
                    @else
                    <button class="button is-disabled"  data-tooltip="It's You! :)"><span class="icon is-small">
                        <i class="fas fa-user"></i>
                    </span></button>
                    @endif
                </td>
                </tr>
                @endforeach
                </tbody>
            </table>  
            <br>
           
          
        </div>
        <div>
            {{ $users->links('pagination.default') }}
        </div>
      
    </div>


    <!-- ПРОФИЛЬ -->
    <div id="profile_content" class="invisible">
        <div class="columns">
        <div class="column has-text-centered">
            <span class="icon">
                @if($current_user->user_type == 0)
                <i class="fas fa-crown" ></i>
                @elseif($current_user->user_type == 1)
                <i class="fas fa-user-ninja"></i>
                @else
                <i class="fas fa-user"></i>
                @endif
              </span>
                    <h3 class="subtitle">{{$current_user->name}}</h3>
            <form action="control/update_profile" method="POST">
                @csrf
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Username</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <p class="control has-icons-left has-icons-right">
                                <input class="input" name="username" type="email" placeholder="User">
                                <span class="icon is-small is-left">
                                  <i class="fas fa-user"></i>
                                </span>
                              </p>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">E-Mail</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <p class="control has-icons-left has-icons-right">
                                <input class="input" name="email" type="email" placeholder="example@yourmail.com">
                                <span class="icon is-small is-left">
                                  <i class="fas fa-envelope"></i>
                                </span>
                              </p>
                        </div>
                    </div>
                </div>

                 <div class="field is-horizontal">
                    <div class="field-label">
                        <!-- Left empty for spacing -->
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <button type="submit" class="button is-link">
                                    <span class="icon">
                <i class="fas fa-save"></i>
                </span>
                                    <span>
                Save
                </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        </div>
    </div>

</div>

@endsection @push('scripts')
<script src="{{ asset('js/control_panel.js') }}"></script>
@endpush