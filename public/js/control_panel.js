//скрипты связанные со странцией Control Panel

//отправка формы настроек соц. сетей
  function submit_social_media(){
    //получаем кол-во полей в форме из элемента с id num_of_fields
    var num_of_fields = $('#num_of_fields').html();
    //получаем форму
    var action = $("#form_social").attr("action");
    //меняем атрибут action формы, добавляя параметр с кол-вом полей в конец
    //и отправляем форму
    $("#form_social").attr("action", action + num_of_fields);
    $("#form_social").submit();
  }
