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

            <form id="form" action="control_panel/create_new_post" enctype="multipart/form-data" method="POST">
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

                  <div class="field">
                    <div class="control">
                      <div id="file-js-example" class="file has-name">
                        <label class="file-label">
                          <input class="file-input" type="file" name="media_input[]" multiple>
                          <span class="file-cta">
                            <span class="file-icon">
                              <i class="fas fa-upload"></i>
                            </span>
                            <span class="file-label">
                              Choose a file…
                            </span>
                          </span>
                   
                            <button class="button is-dark is-outlined" id="clear_files">Clear</button>
                   
                        </label>
                      </div>
    
                    </div>
                    
                  </div>
                  
            

           
                  <div class="white-bg" id="file_container">
                    <h1 class='title is-5'>Files (0)</h1>
                  </div>

              
                  <button type="submit" id="submit" class="button is-link">
                    <span class="icon">
                        <i class="fas fa-save"></i>
                    </span>
                    <span>
                      Save post
                    </span>
                </button>
            </form>
    
        
        

            </div>
  
        
</div>

@endsection


@section('modals')
<div class="modal" id="progress-modal">
  <div class="modal-background"></div>
  <div class="modal-content">

    <div class="box">
      <article class="media">
        <div class="media-content">
          <div class="content has-text-centered">
            <p id="progress-modal-message">Your files are uploading, this might take a while</p>
            <p class="subtitle" id="progress-modal-countdown"></p>
            <progress id="progress-bar" class="progress is-large is-info" value="0" max="100">0%</progress>
            <button class="button is-danger" id="cancel_upload">Cancel upload</button>
          </div>
    
        </div>
      </article>
    </div>
  </div>
</div>


@endsection

@push('scripts')
<script src="{{ asset('js/create_post.js') }}"></script>
<script src="{{ asset('js/jquery.richtext.min.js') }}"></script>
<script src="{{ asset('js/jquery.caret.min.js') }}"></script>
<script src="{{ asset('js/jquery.tag-editor.min.js') }}"></script>
<script src="{{ asset('js/char_counter.js') }}"></script>
<script src="{{ asset('js/file_container.js') }}"></script>
<script src="http://malsup.github.com/jquery.form.js"></script>
<script>


$(document).ready(function(){

    //richText
    $('.textarea').richText({
      imageUpload:false,
      videoEmbed:false,
      fileUpload:false
    });

    //tagEditor
    $('#tags').tagEditor();

    //character counter
    $('#title').charCounter();

    //file display
    $("#file_container").fileContainer();

    $("#form").dropzone({ url: "/control_panel/create_new_post" });

    //progress bar
    var bar = $('#progress-bar');
    var cancel_button = $("#cancel_upload");
    var uploaded = false;
    //form + ajaxForm
    var form = $('#form').ajaxForm({
        //beforeSubmit: validate,
        beforeSend: function(xhr) {
            var percentVal = '0';
            var posterValue = $('input[name=file]').fieldValue();
            bar.val(percentVal);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete;
            bar.val(percentVal);
            bar.text(percentVal + "%");
        },
        success: function() {
            var percentVal = '100';
            bar.val(percentVal);
            bar.text(percentVal + "%");
        },
        complete: function(xhr) {
            //если запрос был отменен
            if(xhr.responseText == undefined)
            { //то пишем в консоль ошибку и убираем окошко
              console.warn("File upload canceled. ajaxForm has been aborted by the user.");
              bar.val(0);
              bar.text("0%");
            }
            else
            {
              //если загрузка завершилась успешно, то оповещаем пользователя и редиректим его через 5 секунд
              //на страницу с постами
              uploaded = true;
              bar.removeClass("is-info").addClass("is-primary");
              cancel_button.removeClass("is-danger").addClass("is-info").attr("href","/control/posts").text("Redirect");
              var msg = $("#progress-modal-message");
              msg.text("Your files have been successfully uploaded and the post has been saved. Redirecting to 'Posts' in ")
              var countdown = $("#progress-modal-countdown");
              var counter = 10;
              var interval = setInterval(function() {
                  counter--;
                  countdown.addClass("fade-in").text(`${counter} seconds`)
                  if (counter == 0) {
                      // Display a login box
                      clearInterval(interval);
                      countdown.addClass("fade-in").text("Redirecting...")
                      window.location.href = "/control/posts";
                  }
              }, 1000);
              
            }    
        }
    });

    //если была нажата кнопка "Cancel upload", то отменяем ajax запрос
    $("#cancel_upload").click(function(){
      if(uploaded == true)
      {
        window.location.href = "/control/posts";
      }
      else
      { 
        var xhr = form.data('jqxhr');
        if(xhr != undefined)
        {
          $("#progress-modal").removeClass("is-active");
          xhr.abort();
        }
        else
        {
          $("#progress-modal").text("ERROR. xhr is undefined.")
        }
      }
    });

     //вызвать модальное окно с прогресс баром
     $("#submit").click(function() {
        $("#progress-modal").addClass("is-active fade-in"); 
      });
    
  });

</script>
@endpush