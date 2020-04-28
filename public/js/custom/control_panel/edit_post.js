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
