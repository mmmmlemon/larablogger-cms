
//скрипты для страницы create_post

//обработка нажатия кнопки Publish
$("#publish_checkbox").click(function(){
  if($("#publish_checkbox").is(":checked"))
  {
      $("#publish_date").prop("disabled", true)
  } else {
      $("#publish_date").prop("disabled", false)
  }
});

//выключаем autodiscover у Dropzone
Dropzone.autoDiscover = false;

var canceled = false;

//список загруженных файлов
var uploaded_files = [];

//ajax-функция для очистки папки temp
var clear_temp = function() {
  $.ajax('/clear_temp',
      {
        success: function (data, status, xhr){
        console.log("%cTemp directory has been p u r g e d", "color: red;");
      }});
}

//инициализируем dropzone с опциями
var dropzone = $("#file_form").dropzone({
  autoProcessQueue: true, //автозагрузка файлов - вкл 
  chunking: true, //разбиение на чанки
  chunkSize: 20000000, //макс размер чанка: 20 мб
  retryChunks: false, 
  addRemoveLinks: true, //кнопка удаления файлов
  paramName: 'file',
  forceChunking: true,
  maxFiles: 20,
  maxFilesize: 4000, //максимальный размер файла: 4 гб
  parallelUploads: 20,

  //вешаем ивенты на дропзону при инициализации
  init: function(){
    clear_temp();

    var dropzone = this;
    //при отправке файла, так же будет отправляться имя файла
    this.on('sending', function(file, xhr, data){
      console.log(`%cSending file ${file.name}`, 'color:grey;');
      data.append("filename", file.name);
    });
      
    //при нажатии кнопки отмены, убираем все загрузки и очищаем папку temp
    $("#cancel").click(function(){
      canceled = true;
      console.log(dropzone.removeAllFiles(true));
      clear_temp();
    });
    
    //когда все файлы будут загружены, форма с постом будет отправлена
    this.on("complete", function (file) {
      if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) 
      {
        if(canceled === false) //если загрузка не была отменена, то отправляем форму
        {
          console.info("%cAll files are uploaded! Submitting post.", 'color: green;');
          //$("#post_form").submit();
        }
        else //если была отменена, то убираем cообщения и ничего не делаем
        {
          console.info("Uploads were canceled by user.");
          canceled = false;
        }
      }
    });
      
  },
  //когда загрузится файл (или его чанки)
  //обновляем месседжи и выводим в консоль
  chunksUploaded: function(file, done, xhr){
    var response = JSON.parse(file.xhr.response);
    uploaded_files.push(response.filename);
    done();
    console.log(`%cFile ${file.name} has been uploaded`, 'color:green;');
  }
});


 
//отправить пост
$("#submit_post").click(function(){

  console.log("m e m e");

  //установка заголовка с csrf-токеном
  $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }});

  // //отправка запроса
  $.ajax({
    type:'POST',
    url: `/control/create_new_post`,
    data: {
      file_list: JSON.stringify(uploaded_files),
      post_title: $("#post_title").val(),
      post_content: $(".textarea").val(),
      post_visibility: $("#publish_checkbox").val(),
      post_date: $("#publish_date").val(),
      post_category: $("#post_category").val(),
      tags: $("#tags").val()
    },
    //при успешном завершении запроса редиректим к постам
    success: function(response){
      window.location.replace("/control/posts");
    }
  });
});
  

//richText
//редактор текста
$('.textarea').richText({
imageUpload:false,
videoEmbed:false,
fileUpload:false
});

//tagEditor
//редактор тегов
$('#tags').tagEditor();

//character counter
//счетчик символов
$('#post_title').charCounter();