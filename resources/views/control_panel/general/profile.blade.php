

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
                            <input class="input @error('username') is-danger @enderror" maxlength="25" name="username" 
                            type="text" placeholder="User" 
                            value="@if($errors->any()){{old('username')}}@else{{$current_user->name}}@endif">
                      
                                <span class="icon is-small is-left">
                                  <i class="fas fa-user"></i>
                                </span>
                                <p class="help is-dark has-text-left">0/25</p>
                              </p>
                              
                              @error('username')
                            <p class="help is-danger"><b> {{ $message }}</b></p>  
                            @enderror
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
                                <input class="input @error('email') is-danger @enderror" name="email" type="email" placeholder="example@yourmail.com" 
                                value="@if($errors->any()){{old('email')}}@else{{$current_user->email}}@endif">
                                <span class="icon is-small is-left">
                                  <i class="fas fa-envelope"></i>
                                </span>
                              </p>
                                @error('email')
                                <p class="help is-danger"><b> {{ $message }}</b></p>  
                                @enderror
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