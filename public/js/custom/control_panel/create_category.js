//scripts for create_category and edit_сategory

//charCounter
$(document).ready(function () {
    $('#title').charCounter();
});

$("#save_category").on('click', function(){
    event.preventDefault();
    $(this).attr("disabled","disabled");
    $("#category_form").submit();
});