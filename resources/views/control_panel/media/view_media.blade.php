

@extends('layouts.app')
@section('content')
<div class="container">
    <div class="white-bg">
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
              <li><a href="/control">Control panel</a></li>
              <li><a href="/control/posts">Posts</a></li>
              <li><a href="/control/media" aria-current="page">Media browser</a></li>
            <li class="is-active"><a href="/control">{{$media->display_name}}</a></li>
            </ul>
          </nav>
        <div class="column">
            <a href="{{url()->previous()}}" class="button is-link">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span>
                 Back
                </span>
            </a>
        </div>
        <div class="is-divider"></div>
        <div class="columns">
            <!--информация о файле-->
            <div class="column is-5 white-bg" style="margin-right:5px;">
            <h1 class="title">{{$media->display_name}}</h1>
            <h1 class="subtitle">{{$media->actual_name}} / {{$media->media_type}} / {{$media->date}} / {{$media->size}}</h1>
            <form action="/control/media/edit_media/{{$media->id}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="field">
                    <label class="label">Post</label>
                    <div class="control">
                    <a target="_blank" href="/post/{{$media->post_id}}">{{$media->post_title}}</a>
                    </div>
                </div>  
                <div class="field">
                    <label class="label">Display name</label>
                    <div class="control">
                    <input name="display_name" class="input" type="text" data-type="tags" placeholder="Display name for the file" value="{{$media->display_name}}">
                    </div>
                </div>  
                <div class="field">
                    <!--видимость чекбокс-->
                    <input class="is-checkradio is-link" name="visibility" id="publish_checkbox" type="checkbox" @if($media->visibility == 1) checked @endif>
                    <label class="label" for="publish_checkbox">Visibility</label>
                    <span class="has-tooltip-multiline" data-tooltip="If unchecked, the file will be hidden from public view.">  <i class="fas fa-question-circle"></i> </span>
                </div>
                @if($media->media_type == 'video')
                <label class="label">Subtitles</label>
                <div id="file-js-example" class="file has-name">
                    <label class="file-label">
                      <input class="file-input" id="subtitle_input" accept=".srt, .txt" type="file" name="subtitles[]" multiple>
                      <span class="file-cta">
                        <span class="file-icon">
                          <i class="fas fa-upload"></i>
                        </span>
                        <span class="file-label">
                          Choose a file…
                        </span>
                      </span>
                      <span class="file-name">
                        No file uploaded
                      </span>
                    </label>
                </div>
                <div class="white-bg">
                    <div id="subtitle_list">
                      No subtitles were attached yet
                    </div>
                </div>

                <label class="label">Thumbnail image @if($media->thumbnail_url) (Replace) @endif</label>
                <div id="thumbnail_uploader" class="file has-name">
                  <label class="file-label">
                    <input class="file-input" type="file" name="thumbnail">
                    <span class="file-cta">
                      <span class="file-icon">
                        <i class="fas fa-upload"></i>
                      </span>
                      <span class="file-label">
                        Choose a file…
                      </span>
                    </span>
                    <span class="file-name">
                      No file uploaded
                    </span>
                  </label>
              </div>
              <br>
                @endif

                <!--кнопка сохранения-->
                <button id="submit_post" type="submit" class="button is-link">
                  <span class="icon">
                      <i class="fas fa-save"></i>
                  </span>
                  <span>
                    Save
                  </span>
                </button>
            </form>
            </div>
            <!--превьюшбки-->
            <div class="column is-7 white-bg">
              @if($media->media_type == "video")
                <div class="tabs">
                    <ul>
                      <li class="is-active" id="tab_thumbnail"><a>Thumbnail</a></li>
                      <li id="tab_subtitles"><a>Subtitles</a></li>
                      <li id="tab_preview"><a>Preview</a></li>
                    </ul>
                  </div>
                  <div id="thumbnail">
                    <figure class="image">
                      @if($media->thumbnail_url)
                        <img src="{{asset("/storage/")."/".$media->thumbnail_url}}">
                          <form action="/control/media/remove_thumbnail/{{$media->id}}" method="POST">
                              @csrf
                              <br>
                              <div class="has-text-centered">
                                <button class="button is-danger">
                                  <span class="icon">
                                    <i class="fas fa-trash"></i>
                                  </span>
                                  <span>Remove thumbnail</span>
                                </button>
                              </div>
                             
                          </form>
                      @else
                        <h1 class="subtitle has-text-centered">No thumbnail</h1>
                      @endif

                    </figure>
                  </div>

                  <div id="subtitle_table" class="invisible">
                      @if(count($subs)>0)
                      <table id="subs_table" class="table is-fullwidth is-hover">
                        <thead>
                          <tr><th>Subtitle</th><th>Visibility</th><th></th></tr>
                        </thead>
                        <tbody id="subs_list">
                          @foreach($subs as $sub)
                        <tr id="sub{{$sub->id}}">
                              <td class="subtitle-display-name" data-tooltip="Edit display name">
                                <p>{{$sub->display_name}}</p>
                                <div class="field is-grouped control">
                                  <input class="input is-info" type="text" placeholder="Subtitle display name" value="{{$sub->display_name}}">
                                  <button class="button is-info">
                                    <span class="icon is-small">
                                      <i class="fas fa-check"></i>
                                    </span>
                                  </button>
                                </div>
                              </td>
                        
                            <td>
                              @if($sub->visibility == 1)
                            <button class="button is-warning is-small hide_subs" data-sub="{{$sub->id}}" data-tooltip="Disable these subtitles"><i class="fas fa-eye-slash"></i></button>
                              @else
                                <button class="button is-primary is-small show_subs" data-sub="{{$sub->id}}" data-tooltip="Enable these subtitles"><i class="fas fa-eye"></i></button>
                              @endif
                            </td>
                            <td>
                              <button class="button is-danger is-small delete_subs" data-sub="{{$sub->id}}" data-tooltip="Delete subtitle file"><i class="fas fa-trash"></i></button>
                            </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                      @else
                      <h1 class="subtitle has-text-centered">No subtitles attached to this video</h1>
                      @endif
                  </div>

                  <div id="preview" class="invisible">
                  <video style="" controls="controls" id="player" preload="none" poster = "{{asset('/storage/')."/".$media->thumbnail_url}}">
                    <source src="http://127.0.0.1:8000/storage/{{$media->media_url}}" 
                     
                      id="content-video">
                    </video>
                  </div>
                @endif

              @if($media->media_type == "image")
                <img src="{{asset("/storage/")."/".$media->media_url}}" alt="">
              @endif
        
            </div>
      
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/plyr.js') }}"></script>
<script src="{{ asset('js/custom/control_panel/view_media.js') }}"></script>
@endpush