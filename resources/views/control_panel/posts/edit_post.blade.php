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

            <h1 class="title has-text-centered">Create Post</h1>
            <div class="is-divider"></div>

        <form action="/post/{{$post->id}}/edit" method="POST">
            @csrf
              <div class="field">
                <label class="label">Category</label>
                <div class="control">
                  <div class="select">
                    <select name="category">
                      @foreach($categories as $categ)
                    <option value="{{$categ->id}}" @if($categ->id == $post->category_id) selected @endif>{{$categ->category_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>


                <div class="field">
                    <div class="control">
                        <p class="help">Title</p>
                        <input maxlength="35" class="input @error('post_title') is-danger @enderror" type="text" name="post_title" 
                      placeholder="Post title" id="title" value="@if($errors->any()){{old('post_title')}}@else{{$post->post_title}}@endif">
                    </div>
                    @error('post_title')
                    <p class="help is-danger"><b> {{ $message }}</b></p>  
                   @enderror
                  </div>

              
                        <p class="help">Content</p>
                          <textarea class="textarea" name="post_content"  placeholder="Write your post here">{{$post->post_content}}</textarea>
                          @error('post_content')
                          <p class="help is-danger"><b> {{ $message }}</b></p>  
                         @enderror
                        </div>
              
                  <div class="field">
                    <input class="is-checkradio is-link" name="publish" id="publish_checkbox" type="checkbox" @if($post->visibility == 1) checked @endif>
                    <label for="publish_checkbox">Visibility</label>
                    <span class="has-tooltip-multiline" data-tooltip="If checked, the post will be visible to everone">  <i class="fas fa-question-circle"></i> </span>
                  
                  </div>

                  <div class="field">
                    <p class="help">Publish date</p>
                    <p class="control has-icons-left">
                       
                      <input class="input" data-tooltip="You can't change the date of publishing" type="date" name="publish_date" min="{{date('Y-m-d', strtotime($post->date))}}" id="publish_date" placeholder="Date" value={{$post->date}} disabled>
                      <span class="icon is-small is-left">
                        <i class="fas fa-calendar"></i>
                      </span>
                    </p>
                   
                  </div>

                  <div class="field">
                    <div class="control">
                        <p class="help">Tags</p>
                    <input class="input" type="text" id="tags" name="tags" value="{{$post->tags}}" placeholder="video,post,meme,text,whatever">
                    </div>
                    
                  </div>

                  <div class="field">
                    <div class="white-bg">
                      <div class="subtitle">Attached media</div>
                      <table class="table is-fullwidth is-hoverable">
                        <thead>
                          <th>Filename</th>
                          <th>Type</th>
                          <th>Actions</th>
                        </thead>
                      <tbody>
                      @foreach($media as $m)
                      <tr>
                      <td><a class="preview" data-type="{{$m->media_type}}" data-url="{{asset("storage/".$m->media_url)}}">{{$m->filename}}</a></td>
                      <td>{{$m->media_type}}</td>
                      </tr>
                    
                      @endforeach
                    </tbody>
                  </table>
                    </div>
                  </div>

                  <button type="submit" class="button is-link">
                    <span class="icon">
                        <i class="fas fa-save"></i>
                    </span>
                    <span>
                      Save post
                    </span>
                </button>
            </form>
   
</div>

@endsection

@section('modals')
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
@endsection


@push('scripts')
<script src="{{ asset('js/jquery.richtext.min.js') }}"></script>
<script src="{{ asset('js/jquery.caret.min.js') }}"></script>
<script src="{{ asset('js/jquery.tag-editor.min.js') }}"></script>
<script src="{{ asset('js/custom/shared/char_counter.js') }}"></script>
<script src="{{ asset('js/plyr.js') }}"></script>

<script>

const player = new Plyr('#player');
  $(".preview").click(function() {
        $("#preview-modal").addClass("is-active fade-in"); 
        if($(this).data("type")==="image")
        { $("#content-in-modal").attr("style", "display: block");
          $("#content-in-modal").attr("src", $(this).data("url"));
        }
        if($(this).data("type")==="video")
        { $("#player").attr("style", "display: block;");
          $("#content-video").attr("src", $(this).data("url"));
        }
      });

  $("#modal-close").click(function() {
    $("#preview-modal").removeClass("is-active");
    player.stop();
    $("#content-in-modal").attr("style", "display: none");
    $("#player").attr("style", "display: none;");
  });

</script>

@endpush