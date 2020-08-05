<div id="users_content"  class="invisible">
    <div class="has-text-centered">
        <span class="icon">
            <i class="fas fa-user"></i>
        </span>
        <h3 class="subtitle">User list</h3>
    </div>
    <br>
    <div class="columns">
     
        @if(config('isMobile') != true)
            <table class="table is-fullwidth is-hoverable">
                <thead>
                    <th>Username</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Registered</th>
                    <th>Actions</th>
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
                            <td>{{date('d.m.Y',strtotime($user->created_at))}}</td>
                            <td>
                                @if($user->user_type == 1 && $user->id != Auth::user()->id)
                                    <button class="button is-danger"  data-tooltip="Downgrade to User">
                                        <span class="icon is-small"
                                        onclick="document.getElementById('change_user_{{$user->id}}').submit();">
                                            <i class="fas fa-arrow-down"></i>
                                        </span>
                                    </button>
                                    <form id="change_user_{{$user->id}}" action="control/change_user_type" method="POST" class="invisible">
                                        @csrf
                                        <input type="text" name="user_id" value="{{$user->id}}">
                                        <input type="text" name="user_type" value="admin"> 
                                    </form>
                                @elseif($user->user_type == 0 && $user->user_id != Auth::user()->id)
                                    <button class="button is-warning"  data-tooltip="This user is Super Admin">
                                        <span class="icon is-small">
                                            <i class="fas fa-crown"></i>
                                        </span>
                                    </button>
                                @elseif($user->user_type == 2 && $user->id != Auth::user()->id)   
                                    <button class="button is-primary"  data-tooltip="Upgrade to Admin">
                                        <span class="icon is-small" onclick="document.getElementById('change_user_{{$user->id}}').submit();">
                                            <i class="fas fa-arrow-up"></i>
                                        </span>
                                    </button>
                                    <form id="change_user_{{$user->id}}" action="control/change_user_type" method="POST" class="invisible">
                                        @csrf
                                        <input type="text" name="user_id" value="{{$user->id}}">
                                        <input type="text" name="user_type" value="user"> 
                                    </form>
                                @else
                                    <button class="button is-disabled"  data-tooltip="It's You! :)">
                                        <span class="icon is-small">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>  
        @else
            <table class="table is-fullwidth is-hoverable">
                @foreach($users as $user)
                    <tr>
                    <td>
                        <p style="font-size:15pt;">{{Str::limit($user->name,10,"...")}}</p>
                        <p style="font-size: 10pt;">{{$user->email}}</p>
                        <p style="font-size: 10pt; color: grey;">{{date('d.m.Y',strtotime($user->created_at))}}</p>
                    </td>
                    <td>
                        @if($user->user_type == 0)
                            <button class="button is-warning is-medium" data-tooltip="Super Admin">
                                <span class="icon">
                                    <i class="fas fa-crown"></i>
                                </span>
                            </button>
                        @elseif($user->user_type == 1)
                            <button class="button is-link is-medium" data-tooltip="Admin">
                                <span class="icon">
                                    <i class="fas fa-user-tie"></i>
                                </span>
                            </button>
                        @elseif($user->user_type == 2)
                            <button class="button is-medium" data-tooltip="User">
                                <span class="icon">
                                    <i class="fas fa-user"></i>
                                </span>
                            </button>
                        @endif
                    </td>
                    <td>
                        @if($user->user_type == 0 && $user->id != Auth::user()->id)
                            <button class="button is-warning is-medium" disabled>
                                <span class="icon">
                                    <i class="fas fa-minus"></i>
                                </span>
                            </button>
                        @elseif($user->user_type == 1 && $user->id != Auth::user()->id)
                            <button class="button is-danger is-medium"  data-tooltip="Downgrade to User">
                                <span class="icon is-small"
                                onclick="document.getElementById('change_user_{{$user->id}}').submit();">
                                    <i class="fas fa-arrow-down"></i>
                                </span>
                            </button>
                            <form id="change_user_{{$user->id}}" action="control/change_user_type" method="POST" class="invisible">
                                @csrf
                                <input type="text" name="user_id" value="{{$user->id}}">
                                <input type="text" name="user_type" value="admin"> 
                            </form>
                        @elseif($user->user_type == 2 && $user->id != Auth::user()->id)
                            <button class="button is-primary is-medium"  data-tooltip="Upgrade to Admin">
                                <span class="icon is-small" onclick="document.getElementById('change_user_{{$user->id}}').submit();">
                                    <i class="fas fa-arrow-up"></i>
                                </span>
                            </button>
                            <form id="change_user_{{$user->id}}" action="control/change_user_type" method="POST" class="invisible">
                                @csrf
                                <input type="text" name="user_id" value="{{$user->id}}">
                                <input type="text" name="user_type" value="user"> 
                            </form>
                        @else
                            <button class="button is-medium" disabled>
                                <span class="icon">
                                    <i class="fas fa-minus"></i>
                                </span>
                            </button>
                        @endif
                    </td>
                    </tr>
                @endforeach
            </table>
        @endif
        <br>
    </div>

    @if(Auth::user()->user_type == 1) 
    <script>
        $(document).ready(function(){
            $("#users_tab").click();
        })
      
    </script>
    @endif

    <div>
        {{ $users->links('pagination.default') }}
    </div>
</div>
