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
            <input class="input" name="site_title" type="text" placeholder="Web-site name" value="{{$site_title}}">
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
              <input class="input" name="site_subtitle" type="text" placeholder="Subtitle" value="{{$site_subtitle}}">
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

  <div class="column is-12 has-text-centered">
    <h3 class="subtitle">Social media (Links)</h3>
  
        @foreach($social_media as $item)
        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <!-- пустой лейбл для того чтобы поля input были вровень с полями формы выше-->
            <label class="label">  </label>
          </div>
          <div class="field-body">
        <div class="field">
        <input class="input" type="text" placeholder="Web-site (or social media platform) name" value="{{$item->platform_name}}">
        </div>
        <div class="field">
          <input class="input" type="text" placeholder="URL" value="{{$item->url}}">
        </div>
      </div>
    </div>
        @endforeach
   
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

  </div>




</div>

@endsection