
  const fileInput = document.querySelector('#file-js-example input[type=file]');
  fileInput.onchange = () => {
    if (fileInput.files.length > 0) {
      const fileName = document.querySelector('#file-js-example .file-name');
      fileName.textContent = fileInput.files[0].name;
    }
  }

  const fileInputt = document.querySelector('#thumbnail_uploader input[type=file]');
  fileInputt.onchange = () => {
    if (fileInputt.files.length > 0) {
      const fileName = document.querySelector('#thumbnail_uploader .file-name');
      fileName.textContent = fileInputt.files[0].name;
    }
  }



  //Plyr, видеоплеер
const player = new Plyr('#player', {});

//текущий активный таб
var previous_tab = $("#tab_thumbnail");
var previous_display = $("#thumbnail");

function switch_tab(current_tab, current_display)
{
  if(current_tab != previous_tab)
  {
    $(current_tab).addClass("is-active");
    $(current_display).removeClass("invisible").addClass("fade-in");
    previous_tab.removeClass("is-active");
    previous_display.addClass("invisible");
    player.pause();
  
    previous_tab = $(current_tab);
    previous_display = $(current_display);
  }

}

$("#tab_thumbnail").on('click', function(){
  switch_tab("#tab_thumbnail", "#thumbnail");
});

$("#tab_subtitles").on('click', function(){
  switch_tab("#tab_subtitles", "#subtitle_table");
});

$("#tab_preview").on('click', function(){
  switch_tab("#tab_preview", "#preview");
});



//если пользователь добавить файлы субтитров
$("#subtitle_input").on('change', function(el){
  //получаем список файлов из input'а
  var files = el.target.files;
  $("#subtitle_list").html("<b>Attached subtitle files</b>");
  for(var i = 0; i < files.length; i++){
      $("#subtitle_list").append(`<h1>${files[i].name}</h1>`);
  }
});