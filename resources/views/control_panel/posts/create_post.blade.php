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

            <form id="post_form" action="control_panel/create_new_post" enctype="multipart/form-data" method="POST">
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

                     
            </form>
    
            <!--форма для загрузки файлов-->
            <form action="/post/upload_files" class="dropzone" id="file_form">
              @csrf
              <div class="fallback">
                <input name="file" type="file" multiple />
              </div>
            </form>
            <!--спиннер и сообщения о загрузке-->
            <div class="subtitle has-text-centered">
              <h1 class="invisible" id="n_of_n"># of #</h1>
              <span id ="loader" class="invisible icon has-text-info">
                <i class="fas fa-spinner fa-pulse"></i>
              </span>
              <h1 class="invisible" id="upload_msg">Uploading files, please don't leave this page</h1>
            </div>
            <!--кнопка отправки формы-->
            <a id="submit" class="button is-info">
              <span class="icon">
                <i class="fas fa-save"></i>
              </span>
              <span>Save Post</span>
            </a>
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
<script src="{{ asset('js/dropzone.js') }}"></script>

<script>

  //выключаем autodiscover у Dropzone
  Dropzone.autoDiscover = false;
  var count = 0;
  var length = 0;


  //инициализируем dropzone с опициями
  var dropzone = $("#file_form").dropzone({
    autoProcessQueue: false, //автозагрузка файлов: 
    chunking: true, //разбиение на чанки
    chunkSize: 20000000, //макс размер чанка: 20 мб
    retryChunks: false, 
    retryChunksLimit: 5,
    paramName: 'file',
    forceChunking: true,
    maxFiles: 20,
    maxFilesize: 4000,
    parallelUploads: 20,

    //вешаем ивенты на дропзону, при инициализации
    init: function(){

      var dropzone = this;
      //при отправке файла, так же будет отправляться имя файла
      this.on('sending', function(file, xhr, data){
        console.log(`%cSending file ${file.name}`, 'color:grey;');
        data.append("filename", file.name);
        length = dropzone.files.length;
        $("#n_of_n").text(`Uploaded ${count} of ${length}`)
      });

     //при нажатии на кнопку отправки, запустится загрузка файлов
      $("#submit").click(function(){
       dropzone.processQueue();
       $("#n_of_n").removeClass("invisible").addClass("fade-in");
       $("#loader").removeClass("invisible");
       $("#upload_msg").removeClass("invisible").addClass("blinking-anim");

      });
     
     //когда все файлы будут загружены, форма с постом будет отправлена
     this.on("complete", function (file) {
        if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) 
        {
          console.info("%cAll files are uploaded! Submitting post.", 'color: green;');
         // $("#post_form").submit();
        }
     });
        
    },
    //когда загрузится файл (или его чанки) выводим сообщение в консоль
    chunksUploaded: function(file, done){
      count++;
      console.log(done)
      $("#n_of_n").text(`Uploaded ${count} of ${length}`)
      done();
      console.log(`%cFile ${file.name} has been uploaded`, 'color:green;');
    }
  });

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


</script>


@endpush