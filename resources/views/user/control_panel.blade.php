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
      <h3 class="subtitle">Web-site settings</h3>

      <div class="field is-horizontal">
        <div class="field-label is-normal">
          <label class="label">Site title</label>
        </div>
        <div class="field-body">
          <div class="field">
            <p class="control is-expanded">
              <input class="input" type="text" placeholder="Web-site name">
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
              <input class="input" type="text" placeholder="Subtitle">
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

    </div>
  </div>



</div>

@endsection