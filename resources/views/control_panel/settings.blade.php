   <!--НАСТРОЙКИ -->
   
   <div id="settings_content" class="current-content">
        
    <div class="columns">
        <div class="column is-12">
            <div class="has-text-centered">
                <span class="icon">
                    <i class="fas fa-cog"></i>
                </span>
                <h3 class="subtitle">Web-site general settings</h3>
            </div>
           <br>
            <form action="control/update_settings" method="POST">
                @csrf
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Site title</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <p class="control is-expanded">
                                <input class="input  @error('site_title') is-danger @enderror" name="site_title" type="text" 
                                required placeholder="Web-site name" maxlength="25"
                            value="@if($errors->any()){{old('site_title')}}@else{{$settings->site_title}}@endif">
                            </p>
                            @error('site_title')
                            <p class="help is-danger"><b> {{ $message }}</b></p>  
                            @enderror
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
                                <input class="input @error('site_subtitle') is-danger @enderror" name="site_subtitle" 
                                type="text" required placeholder="Subtitle" maxlength="55"
                                value="@if($errors->any()){{old('site_subtitle')}}@else{{$settings->site_subtitle}}@endif">
                            </p>
                            @error('site_subtitle')
                            <p class="help is-danger"><b> {{ $message }}</b></p>  
                            @enderror
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
                              <input class="input @error('contact_email') is-danger @enderror" name="contact_email" 
                              type="text" required placeholder="example@yourmail.com" maxlength="30"
                              value="@if($errors->any()){{old('contact_email')}}@else{{$settings->contact_email}}@endif">
                          </p>
                          @error('contact_email')
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
