@extends('layouts.app')
@section('content')

<div class="container white-bg">
  <nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
      <li><a href="/control">Control panel</a></li>
      <li><a href="/control/posts" aria-current="page">Posts</a></li>
      <li class="is-active"><a href="#" aria-current="page">Edit</a></li>
      <li class="is-active"><a href="#" aria-current="page">{{$post->post_title}}</a></li>
    </ul>
  </nav>
 
        <a href="{{url()->previous()}}" class="button is-link">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span>
                 Back
                </span>
            </a>

        <h1 class="title has-text-centered">Edit Post</h1>
        <div class="is-divider"></div>

        <!--форма с постом-->
        <form id="post_form" action="/post/{{$post->id}}/edit" method="POST">
            @csrf
              <div class="field">
                <label class="label">Category</label>
                <div class="control">
                  <div class="select">
                    <!--выбор категории-->
                    <select name="category" id="post_category">
                      @foreach($categories as $categ)
                    <option value="{{$categ->id}}" @if($categ->id == $post->category_id) selected @endif>{{$categ->category_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

              <!--скрытое после с id поста-->
              <input type="text" id="post_id" value="{{$post->id}}" class="invisible">

                <div class="field">
                    <div class="control">
                        <p class="help">Title</p>
                        <!--заголовок поста-->
                        <input maxlength="35" class="input @error('post_title') is-danger @enderror" type="text" name="post_title" 
                      placeholder="Post title" id="post_title" value="@if($errors->any()){{old('post_title')}}@else{{$post->post_title}}@endif">
                    </div>
                    @error('post_title')
                    <p class="help is-danger"><b> {{ $message }}</b></p>  
                   @enderror
                  </div>

              
                        <p class="help">Content</p>
                        <!--текст и основное содержание поста-->
                          <textarea class="textarea" name="post_content" id="post_content" placeholder="Write your post here">{{$post->post_content}}</textarea>
                          @error('post_content')
                          <p class="help is-danger"><b> {{ $message }}</b></p>  
                         @enderror
                        </div>
              
                  <div class="field">
                    <!--видимость чекбокс-->
                    <input class="is-checkradio is-link" name="publish" id="publish_checkbox" type="checkbox" @if($post->visibility == 1) checked @endif>
                    <label for="publish_checkbox">Visibility</label>
                    <span class="has-tooltip-multiline" data-tooltip="If checked, the post will be visible to everone">  <i class="fas fa-question-circle"></i> </span>
                  </div>

                  <div class="field">
                    <p class="help">Publish date</p>
                    <p class="control has-icons-left">
                       <!--дата публикации-->
                      <input class="input" data-tooltip="You can't change the date of publishing" type="date" id="publish_date" name="publish_date" min="{{date('Y-m-d', strtotime($post->date))}}" id="publish_date" placeholder="Date" value={{$post->date}} disabled>
                      <span class="icon is-small is-left">
                        <i class="fas fa-calendar"></i>
                      </span>
                    </p>
                  </div>
                    <!--теги-->
                    <div class="field">
                      <label class="label">Tags</label>
                      <div class="control">
                      <input class="input" type="text" data-type="tags" id="tags" placeholder="Choose Tags" value="{{$post->tags}}">
                      </div>
                    </div>  
            </form>

            <!--просмотр файлов-->
            <div class="field">
              <div class="white-bg">
                <div class="subtitle   @if(count($media)>0) invisible @endif" id="no_files">No files attached</div>
                <div class="field  @if(count($media)==0) invisible @endif" id="file_browser">
                    <div class="subtitle">Attached media</div>
                    <!--таблица с файлами-->
                    <table class="table is-fullwidth is-hoverable is-narrow" >
                      <thead>
                        <th>Filename</th>
                        <th>Type</th>
                        <th>Actions</th>
                      </thead>
                    <tbody id="tbody">
                      <!--если медиафайлы есть, то выводим их-->
                      @if(count($media) > 0)
                        @foreach($media as $m)
                        <tr>
                        <td><a class="preview" data-type="{{$m->media_type}}" data-url="{{asset("storage/".$m->media_url)}}">{{$m->filename}}</a></td>
                        <td>{{$m->media_type}}</td>
                        <td>
                        <a class="button is-small is-danger delete_media" data-tooltip="Delete this media" data-id="{{$m->id}}">
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
    
                </div>
              </div>
            </div>
            <!--Dropzone-->
            <form action="/upload_files" method="POST" class="dropzone" id="dropzone_form">
              @csrf
              <div class="fallback">
              <input type="text" name="post_id" value="{{$post->id}}">
                <input name="file" type="file" multiple />
              </div>
            </form>
            <!--кнопка отправки поста-->
            <button id="submit_post" type="submit" class="button is-link">
              <span class="icon">
                  <i class="fas fa-save"></i>
              </span>
              <span>
                Save post
              </span>
          </button>
   
</div>
@endsection

@section('modals')
<!--модальное окно превью файла-->
<div class="modal" id="preview-modal">
  <div class="modal-background"></div>
  <div class="modal-content column is-two-thirds-desktop is-12-mobile">
    <p class="image has-text-centered">
      <img style="display:none;" id="content-in-modal" width="90%" src="" alt="">
      <video style="display: none;" controls="controls" id="player">
        <source src="" id="content-video">
      </video>
    </p>
  </div>
  <button class="modal-close is-large" id="modal-close" aria-label="close"></button>
</div>

<!--модальное окно подтверждения удаления файла-->
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
<script src="{{ asset('js/jquery.caret.min.js') }}"></script>
<script src="{{ asset('js/jquery.tag-editor.min.js') }}"></script>
<script src="{{ asset('js/custom/shared/char_counter.js') }}"></script>
<script src="{{ asset('js/plyr.js') }}"></script>
<script src="{{ asset('js/dropzone.js') }}"></script>
<script src="{{ asset('js/custom/control_panel/edit_post.js') }}"></script>
@endpush