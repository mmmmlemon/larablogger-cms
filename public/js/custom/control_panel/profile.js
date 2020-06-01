//скрипты для страницы profile

$(document).ready(function () {
    $('#username').charCounter();
});

$("#save_profile").on('click',function(e){
    e.preventDefault();
    $(this).attr("disabled","disabled");
    $("#profile_form").submit();
});
