
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

//предыдущий активный таб
var previous_tab = $("#tab_thumbnail");
var previous_display = $("#thumbnail");

//ф-ция переключения табов
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


$(document).on('click','.hide_subs', function(){
  var sub = $(this).data("sub");
  var button = $(this);
  $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }});

  //отправка запроса
  $.ajax({
    type:'POST',
    url: '/control/media/change_subs_status',
    data: {sub_id: sub, visibility: 0},
    success: function(response){
      //если запрос выполнился успешно
      console.log("The subtitle file has been hidden.");
      button.removeClass("is-warning").addClass("is-primary").removeClass("hide_subs").addClass("show_subs");
      button.attr("data-tooltip","Enable these subtitles");
      button.html(`<i class="fas fa-eye"></i>`);
    }
  });
});

$(document).on('click', '.show_subs', function(){
  var sub = $(this).data("sub");
  var button = $(this);
  $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }});

  //отправка запроса
  $.ajax({
    type:'POST',
    url: '/control/media/change_subs_status',
    data: {sub_id: sub, visibility: 1},
    success: function(response){
      //если запрос выполнился успешно
      console.log("The subtitle file has been shown.");
      button.removeClass("is-primary").addClass("is-warning").removeClass("show_subs").addClass("hide_subs");
      button.attr("data-tooltip","Disable these subtitles");
      button.html(`<i class="fas fa-eye-slash"></i>`);
    }
  });
});

$(document).on('click', '.delete_subs', function(){
  var sub = $(this).data("sub");
  $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }});

  //отправка запроса
  $.ajax({
    type:'POST',
    url: '/control/media/delete_subs',
    data: {sub_id: sub},
    success: function(response){
      //если запрос выполнился успешно
      console.log("The subtitle file has been deleted.");
      $(`#sub${sub}`).remove();
      var count = $(`#subs_list`).children().length;
      if(count === 0)
      {
        $("#subs_table").html("").append("<tr><td colspan='3'>No subtitles attached</td></tr>")
      }
    }
  });
});



//РЕДАКТИРОВАНИЕ ИМЕНИ ФАЙЛА СУБТИТРОВ
var elem_children; //надпись и input которые нужно показывать\прятать
var edit = false; //открыто ли редактирование в данный момент
var curr_elem, prev_elem;
var id;

//при нажатии на ячейкку таблицы с именем файла
$(document).on('click','.edit_subs', function(el){
  //если редактирование уже открыто
  if(edit == true)
  {//то закрываем открытое, и открываем новое
    elem_children.eq(0).removeClass('invisible').addClass('fade-in'); //убираем надпись 
    elem_children.eq(1).addClass('invisible').addClass("ignore"); //и показываем поле input с кнопкой
  }
   id = $(this).data("sub");
   var elem = $(`#sub_file_${id}`); //получаем элемент в котором должна появиться форма редактирования (td)
   elem_children = $(elem).children(); //получаем "детей" этого элемента
   elem_children.eq(0).addClass('invisible'); //убираем надпись 
   elem_children.eq(1).removeClass('invisible').removeClass("ignore").addClass('fade-in'); //и показываем поле input с кнопкой
   edit = true;
  });

//ОТПРАВКА AJAX НА РЕДАКТИРОВАНИЕ ИМЕНИ ФАЙЛА СУБТИТРОВ
$(document).on('click', '.edit_display_name', function(el){
  //получаем значение input'а
  var value = $(el.target).parent().children().first().val();
  $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }});

  //отправка запроса
  $.ajax({
    type:'POST',
    url: '/control/media/change_subs_display_name',
    data: {display_name: value, sub_id: id},
    success: function(response){
      //если запрос выполнился успешно
      console.log("Display name has been changed for the subtitle file.");
      elem_children.eq(0).removeClass('invisible').addClass('fade-in').html(value); //возвращаем надпись 
      elem_children.eq(1).addClass('invisible').addClass("ignore"); //и прячем поле input с кнопкой
      edit = false; //режим редактирования выключен
      $(`#sub_file_${id}`).find("input").val(value);
  }});

});