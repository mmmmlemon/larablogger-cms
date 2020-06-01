//скрипты связанные со странцией Control Panel

//убирает красную подсветку у инпутов после ошибки
$("input").change(function () {
    $(this).removeClass("is-danger")
});

//отправка формы настроек соц. сетей
function submit_social_media() {
    //получаем кол-во полей в форме из элемента с id num_of_fields
    var num_of_fields = $('#num_of_fields').html();
    //получаем форму
    var action = $("#form_social").attr("action");
    //меняем атрибут action формы, добавляя параметр с кол-вом полей в конец
    //и отправляем форму
    $("#form_social").attr("action", action + num_of_fields);
    $("#form_social").submit();
}

//смена вкладки с настройками по клику 
//(id div-блока который нужено показать и id вкладки которую нужно подсветить)
function change_tab(div_name, tab_name) {
    //убираем текущий div-блок, добавляя класс invisible
    $(".current-content").removeClass("current-content").addClass("invisible");
    //показываем новый div-блок
    $("#" + div_name).removeClass("invisible").addClass("current-content fade-in");

    //подсвечиваем вкладку
    $(".current-tab").removeClass("current-tab is-active");
    $("#" + tab_name).addClass("is-active current-tab");

    $("#footer").attr("style", `top: ${$(document).height()}px;`)
}

//если в url страницы есть якорь, то переключаем на соответствующий таб
if (window.location.hash) {
    var hash = window.location.hash.substring(1);
    if (hash == 'settings') {
        $("#settings_tab").click();
    } else if (hash == 'design') {
        $("#design_tab").click();
    } else if (hash == 'users') {
        $("#users_tab").click();
    } else if (hash == 'profile') {
        $("#profile_tab").click();
    } else if (hash == 'posts') {
        $("#posts_tab").click();
    } else { /*do nothing*/}
}
