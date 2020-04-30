//скрипты для страницы редактирования поста

$('.textarea').richText({
    imageUpload:false,
    videoEmbed:false,
    fileUpload: false,
    fileUpload: false,
  });

  $('#tags').tagEditor();

  $(document).ready(function(){
    $('#title').charCounter();
  });

  
var tr;

const player = new Plyr('#player');

  //принажатии на имя файла показать превью файла
  $(".preview").click(function() {
        $("#preview-modal").addClass("is-active fade-in"); 
        //если картинка, то показываем тег img
        if($(this).data("type")==="image")
        { $("#content-in-modal").attr("style", "display: block");
          $("#content-in-modal").attr("src", $(this).data("url"));
        }
        //если видео, то показываем плеер
        if($(this).data("type")==="video")
        { $("#player").attr("style", "display: block;");
          $("#content-video").attr("src", $(this).data("url"));
        }
      });

  //закрыть модальное окно с превью
  $("#modal-close").click(function() {
    $("#preview-modal").removeClass("is-active");
    player.stop();
    $("#content-in-modal").attr("style", "display: none");
    $("#player").attr("style", "display: none;");
  });

  //по нажатию на кнопку удаления показать окно подтвреждения удаления
  $(".delete_media").click(function(){
    $(".modalDelete").addClass("is-active fade-in");
    $("#submit_modal").data("id", $(this).data("id"));
    //записываем строку в которой находится файл, чтобы спрятать её после удаления
    tr = $(this).parent().parents()[0];
  });

  //закрыть модальное окно подтвреждения удаления
  $("#close_delete_modal").click(function() {
    $(".modalDelete").removeClass("is-active fade-in");
  });

  //удалить файл
  $("#submit_modal").click(function(){
    //прячем строку таблицы и отправялем ajax
    $(tr).attr("style","display: none;");
    send_delete_media_request($(this).data("id"));
    //убираем модальное окно
   $(".modalDelete").removeClass("is-active fade-in");
  })

  //отправка запроса на удаление файла через ajax
  function send_delete_media_request(media_id)
  {
    $.ajaxSetup({
        headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }});

    $.ajax({
      type:'POST',
      url: '/delete_media',
      data: {id: media_id},
      success: function(){
        console.log("%cThe file has been succesfully p u r g e d from existance.", "color: red;");
      }
    });
  }


//табы

$("#browse_files").click(function(){
  $(this).addClass("is-active");
  $("#add_files").removeClass("is-active");
  $("#file_form").addClass("invisible");
  $("#file_browser").removeClass("invisible").addClass("fade-in");
});

$("#add_files").click(function(){
  $(this).addClass("is-active");
  $("#browse_files").removeClass("is-active");
  $("#file_browser").addClass("invisible");
  $("#file_form").removeClass("invisible").addClass("fade-in");
});

Dropzone.autoDiscover = false;

//dropzone
var dropzone = $("#dropzone_form").dropzone({
  //autoProcessQueue: false, //автозагрузка файлов: 
  chunking: true, //разбиение на чанки
  chunkSize: 20000000, //макс размер чанка: 20 мб
  retryChunks: false, 
  addRemoveLinks: true, //кнопка удаления файлов
  paramName: 'file',
  forceChunking: true,
  maxFiles: 20,
  maxFilesize: 4000, //максимальный размер файла: 4 гб
  parallelUploads: 20,
  init: function(){
    this.on('sending', function(file, xhr, data){
      console.log(`%cSending file ${file.name}`, 'color:grey;');
      data.append("filename", file.name);
    });

    this.on("success", function(file, responseText) {
      console.log(responseText);
  });
  },
  chunksUploaded: function(xhr, done){
    done();
    console.log("The file has been uploaded.");
  },
  success: function(file, xhr){

    console.log(JSON.parse(file.xhr.response));
}
});
