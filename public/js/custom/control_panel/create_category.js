//скрипты для страницы create_category и edit_сategory

//счетчик символов
$(document).ready(function () {
    $('#title').charCounter();
});

$("#save_category").on('click', function(){
    event.preventDefault();
    $(this).attr("disabled","disabled");
    $("#category_form").submit();
});