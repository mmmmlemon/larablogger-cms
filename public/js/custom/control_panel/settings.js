//скрипты для страницы settings

$(document).ready(function () {
    $('#site_title').charCounter();
    $('#site_subtitle').charCounter();
    $("#contact_disclaimer").charCounter();
});

$("#save_general").on('click', function(e){
    e.preventDefault();
    $(this).attr("disabled","disabled");
    $("#general_form").submit();
});

$("#save_social").on("click", function(e){
    e.preventDefault();
    $(this).attr("disabled","disabled");
    $("#form_social").submit();
})

