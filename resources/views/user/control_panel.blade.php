@extends('layouts.app')

@section('content')
   
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
      <li class="is-active">
        <a>
          <span class="icon is-small"><i class="fas fa-cog" aria-hidden="true"></i></span>
          <span>Settings</span>
        </a>
      </li>
      <li>
        <a>
          <span class="icon is-small"><i class="fas fa-user" aria-hidden="true"></i></span>
          <span>Users</span>
        </a>
      </li>
      <li>
        <a>
          <span class="icon is-small"><i class="fas fa-file-alt" aria-hidden="true"></i></span>
          <span>~</span>
        </a>
      </li>
      <li>
        <a>
          <span class="icon is-small"><i class="far fa-file-alt" aria-hidden="true"></i></span>
          <span>~</span>
        </a>
      </li>
    </ul>
  </div>

  <div class="columns">
    <div class="column is-12 has-text-centered">
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
            <input class="input" name="site_title" type="text" placeholder="Web-site name" value="{{$settings->site_title}}">
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
              <input class="input" name="site_subtitle" type="text" placeholder="Subtitle" value="{{$settings->site_subtitle}}">
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
      <h3 class="subtitle">Social media (Links)</h3>
      @php
      $count = 0;   
     @endphp
    <form id = "form_social" action="control/update_social/" method="POST">
    @csrf
   
    @foreach($social_media as $item)
      <div class="field is-horizontal">
        <div class="field-label is-normal">
        <label class="label">{{$item->platform_name}}</label>
        </div>
        <div class="field-body">
          <div class="field">
          <input class="input invisible" type="text" name="id_{{$count}}" value="{{$item->id}}">
          <input class="input" type="text" placeholder="Web-site (or social media platform) name" name="platform_{{$count}}" value="{{$item->platform_name}}">
            </div>
            <div class="field">
              <input class="input" type="text" placeholder="URL" name="url_{{$count}}" value="{{$item->url}}">
            </div>
        </div>
      </div>
    @php
     $count += 1;   
    @endphp
    @endforeach
      
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
              <button type="submit" class="button is-link"  onclick="event.preventDefault();
              submit_social_media();">
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

@endsection

<script>
  function submit_social_media(){
    var num_of_fields = $('#num_of_fields').html();
    var action = $("#form_social").attr("action");
    $("#form_social").attr("action", action + num_of_fields);
    $("#form_social").submit();
  }
  
</script>