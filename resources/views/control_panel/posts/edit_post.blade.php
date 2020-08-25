@extends('layouts.app')

@section('content')
<div class="container white-bg">
  <nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
      <li><a href="/control">Control panel</a></li>
      <li><a href="/control/posts" aria-current="page">Posts</a></li>
      <li class="is-active"><a href="#" aria-current="page">Edit</a></li>
      <li class="is-active"><a href="#" aria-current="page">{{Str::limit($post->post_title,30,"...")}}</a></li>
    </ul>
  </nav>
 
  <h1 class="title has-text-centered">Edit Post</h1>
<h2 class="subtitle has-text-centered"><a href="/post/{{$post->id}}">{{$post->post_title}}</a></h2>
  <div class="is-divider"></div>

  <form id="post_form" action="/post/{{$post->id}}/edit" method="POST">
      @csrf
      <div class="field">
        <label class="label">Category</label>
        <div class="control">
          <div class="select">
            <select name="category" id="post_category">
              @foreach($categories as $categ)
                <option value="{{$categ->id}}" @if($categ->id == $post->category_id) selected @endif>{{$categ->category_name}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <input type="text" id="post_id" value="{{$post->id}}" class="invisible">
      <br>
      <div class="field">
        <div class="control">
          <label class="label">Title</label>
          <input maxlength="70" class="input @error('post_title') is-danger @enderror" type="text" name="post_title" 
            placeholder="Post title" id="post_title" value="@if($errors->any()){{old('post_title')}}@else{{$post->post_title}}@endif">
        </div>
          @error('post_title')
            <p class="help is-danger"><b> {{ $message }}</b></p>  
          @enderror
      </div>

      <div class="field">
        <label class="label">Main text/content</label>
        <textarea class="textarea" name="post_content" id="post_content" placeholder="Write your post here">{{$post->post_content}}</textarea>
        @error('post_content')
          <p class="help is-danger"><b> {{ $message }}</b></p>  
        @enderror
      </div>
        
      <div class="field">
        <input class="is-checkradio is-link" name="pinned" id="pinned_checkbox" type="checkbox" @if($post->pinned == 1) checked @endif>
        <label class="label" for="pinned_checkbox">Pinned</label>
        <span class="has-tooltip-multiline" data-tooltip="If checked, the post will be pinned to the top of the page"">
          <i class="fas fa-question-circle"></i>
        </span>
      </div>
      <div class="field">
        <input class="is-checkradio is-link" name="publish" id="publish_checkbox" type="checkbox" @if($post->visibility == 1) checked @endif>
        <label class="label" for="publish_checkbox">Visibility</label>
        <span class="has-tooltip-multiline" data-tooltip="If checked, the post will be visible to everyone">
          <i class="fas fa-question-circle"></i>
        </span>
      </div>

      <div class="field">
        <label class="label">Publish date</label>
        <p class="control has-icons-left">
          <input class="input" data-tooltip="You can't change the date of publishing" type="date" id="publish_date" name="publish_date" min="{{date('Y-m-d', strtotime($post->date))}}" id="publish_date" placeholder="Date" value={{$post->date}} disabled>
          <span class="icon is-small is-left">
            <i class="fas fa-calendar"></i>
          </span>
        </p>
      </div>

      <div class="field">
        <label class="label">Tags</label>
        <div class="control">
          <input class="input" type="text" data-type="tags" id="tags" name="tags" placeholder="Choose Tags" value="{{$post->tags}}">
        </div>
      </div>  
      </form>

      <div class="field">
        <div class="white-bg">
          <div style="margin:0;" class="subtitle @if(count($media)>0) invisible @endif" id="no_files">No files attached</div>
          <div class="field  @if(count($media)==0) invisible @endif" id="file_browser">
              <div class="subtitle">Attached media</div>
              @if(config('isMobile') != true)
                <table class="table is-fullwidth is-hoverable is-narrow" >
                  <thead>
                    <th>Filename</th>
                    <th></th>
                    <th>Type</th>
                    <th>Actions</th>
                  </thead>
                  <tbody id="tbody">
                  
                      @if(count($media) > 0)
                        @foreach($media as $m)
                        <tr>
                          <td><a class="preview" data-type="{{$m->media_type}}" data-url="{{asset("storage/".$m->media_url)}}">{{$m->display_name}}</a></td>
                          <td><a target="_blank" href="/control/media/{{$m->id}}">Edit</a></td>
                          <td>{{$m->media_type}}</td>
                          <td>
                            <a class="button is-small is-danger delete_media" data-tooltip="Delete this media file" data-id="{{$m->id}}">
                                <span class="icon">
                                  <i class="fas fa-trash"></i>
                                </span>
                            </a>
                          </td>
                        </tr>
                        @endforeach
                      @endif
              
                  </tbody>
              </table>
              @else
              <table class="table is-fullwidth is-hoverable is-narrow" >
                <tbody id="tbody">
                
                    @if(count($media) > 0)
                      @foreach($media as $m)
                      <tr>
                        <td>
                          <a class="preview" data-type="{{$m->media_type}}" data-url="{{asset("storage/".$m->media_url)}}">{{Str::limit($m->display_name, 15, "...")}}</a>
                        </td>
                        <td>
                          <div class="field has-addons">
                            <p class="control">
                            <a href="/control/media/{{$m->id}}" class="button is-link">
                                <span class="icon is-small">
                                  <i class="fas fa-edit"></i>
                                </span>
                              </a>
                            </p>
                            <p class="control">
                              <button class="button is-danger delete_media" data-tooltip="Delete this media file" data-id="{{$m->id}}" data-ismobile="true">
                                <span class="icon is-small">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </button>
                            </p>
                          </div>
                        </td>
                      </tr>
                      @endforeach
                    @endif
            
                </tbody>
            </table>
            @endif
          </div>
        </div>
      </div>

      <form action="/post/upload_files" method="POST" class="dropzone" id="dropzone_form">
        @csrf
        <div class="fallback">
        <input type="text" name="post_id" value="{{$post->id}}">
          <input name="file" type="file" accept=".jpeg,.jpg,.png,.mp4" multiple />
        </div>
      </form>
      <br>
      <button id="submit_post" type="submit" class="button is-link @if(config('isMobile')) is-fullwidth @endif">
        <span class="icon">
            <i class="fas fa-save" id="submit_icon"></i>
        </span>
        <span>Save post</span>
      </button>
</div>
@endsection

@section('modals')
<div class="modal" id="preview-modal">
  <div class="modal-background"></div>
  <div class="modal-content column is-two-thirds-desktop is-12-mobile">
    <p class="image has-text-centered">
        <div class="has-text-centered">
          <img id="content-in-modal" width="90%" class="centered_image" src="" alt="" style="padding:0px;">
          <div id="player_div" style="display: none;">
              <video controls="controls" id="player">
                  <source src="" id="content-video">
              </video>
          </div>
        </div>
    </p>
  </div>
  <button class="modal-close is-large" id="modal-close" aria-label="close"></button>
</div>

<div class="modal modalDelete">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">You sure?</p>
      <button class="delete" id="close_delete_modal" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
      <p>Are you sure you want to delete this file?</p>
      <b id="modal_file_title"></b>
      <p class="has-text-danger">This action cannot be undone.</p>
    </section>
    <footer class="modal-card-foot">
      <button class="button is-danger" id="submit_modal" data-id="">Delete</button>
    </footer>
  </div>
</div>
@endsection


@push('scripts')
<script src="{{ asset('js/tags-input.js') }}"></script>
<script src="{{ asset('js/jquery.richtext.min.js') }}"></script>
<script src="{{ asset('js/plyr.js') }}"></script>
<script src="{{ asset('js/dropzone.js') }}"></script>
<script src="{{ asset('js/custom/shared/char_counter.js') }}"></script>
<script src="{{ asset('js/custom/control_panel/edit_post.js') }}"></script>
@if(config('isMobile') != true)
<script src="{{ asset('js/custom/control_panel/edit_post_dropzone_desktop.js') }}"></script>
@else
<script src="{{ asset('js/custom/control_panel/edit_post_dropzone_mobile.js') }}"></script>
@endif
@endpush