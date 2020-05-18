//скрипты для страницы с медиа браузером

//Plyr, видеоплеер
const player = new Plyr('#player');

//показать превью файла
$(document).on('click', ".preview", function(){
    $("#preview-modal").addClass("is-active fade-in"); 
    //если картинка, то показываем тег img
    if($(this).data("type")==="image")
    { $("#content-in-modal").attr("style", "display: block");
      $("#content-in-modal").attr("src", $(this).data("url"));
    }
    //если видео, то показываем плеер
    if($(this).data("type")==="video")
    { $("#player_div").attr("style", "display: block;");
      $("#content-video").attr("src", $(this).data("url"));
      player.source = {
        type: 'video',
        title: 'Preview',
        sources: [
          {
            src: $(this).data("url"),
            type: 'video/mp4',
            size: 720,
          },
        ],
      }; 
  
  }
  });

  //закрыть модальное окно с превью
$("#modal-close").click(function() {
    $("#preview-modal").removeClass("is-active");
    player.stop(); //стопорим плеер, чтобы видео не играло в фоне :)
    $("#content-in-modal").attr("style", "display: none");
    $("#player_div").attr("style", "display: none;");
  });
   