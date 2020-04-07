@extends('layouts.app')
@section('content')

<div class="container white-bg">
  <nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
      <li><a href="/control">Control panel</a></li>
      <li><a href="/control/posts" aria-current="page">Posts</a></li>
      <li class="is-active"><a href="#" aria-current="page">Add post</a></li>
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

            <h1 class="title has-text-centered">Add Post</h1>
            <div class="is-divider"></div>

            <form action="control_panel/create_new_post" enctype="multipart/form-data" method="POST">
              @csrf
              <div class="field">
                <label class="label">Category</label>
                <div class="control">
                  <div class="select">
                    <select name="category">
                      @foreach($categories as $categ)
                    <option value="{{$categ->id}}">{{$categ->category_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>


                <div class="field">
                    <div class="control">
                        <p class="help">Title</p>
                      <input maxlength="35" id="title" class="input @error('post_title') is-danger @enderror" type="text" name="post_title" 
                      placeholder="Post title" value="@if($errors->any()){{old('post_title')}}@else @endif">
                    </div>
                    @error('post_title')
                            <p class="help is-danger"><b> {{ $message }}</b></p>  
                    @enderror
                  </div>

              
                        <p class="help">Content</p>
                          <textarea class="textarea" id="textarea" maxlength="700" name="post_content" placeholder="Write your post here"></textarea>
                          @error('post_content')
                            <p class="help is-danger"><b> {{ $message }}</b></p>  
                          @enderror

                  <div class="field">
                    <input class="is-checkradio is-link" name="publish" id="publish_checkbox" type="checkbox" checked="checked">
                    <label for="publish_checkbox">Publish now</label>
                    <span class="has-tooltip-multiline" data-tooltip="If checked, the post will be published immediately, otherwise you have to pick at the picked date">  <i class="fas fa-question-circle"></i> </span>
                  
                  </div>

                  <div class="field">
                    <p class="help">Publish date</p>
                    <p class="control has-icons-left">
                       
                      <input class="input" type="date" name="publish_date" min="{{date('Y-m-d', strtotime($current_date))}}" id="publish_date" placeholder="Date" value={{$current_date}} disabled>
                      <span class="icon is-small is-left">
                        <i class="fas fa-calendar"></i>
                      </span>
                    </p>
                   
                  </div>

                  <div class="field">
                    <div class="control">
                        <p class="help">Tags</p>
                      <input class="input" type="text" id="tags" name="tags" placeholder="video,post,meme,text,whatever">
                    </div>
                    
                  </div>

                  <div id="file-js-example" class="file has-name">
                    <label class="file-label">
                      <input type="file" name="media_input" />
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

@push('scripts')
<script src="{{ asset('js/create_post.js') }}"></script>
<script src="{{ asset('js/jquery.richtext.min.js') }}"></script>
<script src="{{ asset('js/jquery.caret.min.js') }}"></script>
<script src="{{ asset('js/jquery.tag-editor.min.js') }}"></script>
<script src="{{ asset('js/char_counter.js') }}"></script>
<script>
  $('.textarea').richText({
    imageUpload:false,
    videoEmbed:false,
    fileUpload:false
  });

  $('#tags').tagEditor();

  $(document).ready(function(){
    $('#title').charCounter();
  });

</script>

<script>
  const fileInput = document.querySelector('#file-js-example input[type=file]');
  fileInput.onchange = () => {
    if (fileInput.files.length > 0) {
      const fileName = document.querySelector('#file-js-example .file-name');
      fileName.textContent = fileInput.files[0].name;
    }
  }
</script>
@endpush