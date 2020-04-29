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