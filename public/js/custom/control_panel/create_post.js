
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

var count = 0;
var length = 0;
var canceled = false;

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
  autoProcessQueue: false, //автозагрузка файлов: 
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
      clear_temp();
      if(dropzone.files.length === 0)
      {
        //если файлы не были добавлены в дропзону, то отправляем пост
      $("#post_form").submit();
      }
      else
      {
      //если были добавлены файлы, то начинаем загрузку и показываем сообщения
      dropzone.processQueue();
      $("#cancel").removeClass("invisible");
      $("#n_of_n").removeClass("invisible").addClass("fade-in");
      $("#loader").removeClass("invisible");
      $("#upload_msg").removeClass("invisible").addClass("blinking-anim");
      }
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
          $("#post_form").submit();
        }
        else //если была отменена, то убираем вообщения и ничего не делаем
        {
          console.info("Uploads were canceled by user.");
          canceled = false;
          $("#cancel").addClass("invisible");
          $("#n_of_n").addClass("invisible");
          $("#loader").addClass("invisible");
          $("#upload_msg").addClass("invisible");
        }
      }
    });
      
  },
  //когда загрузится файл (или его чанки)
  //обновляем месседжи и выводим в консоль
  chunksUploaded: function(file, done){
    count++;
    console.log(done)
    $("#n_of_n").text(`Uploaded ${count} of ${length}`)
    done();
    console.log(`%cFile ${file.name} has been uploaded`, 'color:green;');
  }
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
$('#title').charCounter();