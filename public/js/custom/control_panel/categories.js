//скрипты для страницы categories

//показать модальное окно удаления категории
$(".showModalDelete").click(function () {
    $(".modalDelete").addClass("is-active fade-in");
    $("#modal_post_title").text($(this).data("title"));
    $("#modal_form_input").val($(this).data("id"));
});

//подтвердить удаление категории
$("#submit_modal").on('click', function () {
    $(this).attr("disabled","disabled")
    $("#modal_form").submit();
});

//закрыть модальное окно
$(".delete").click(function () {
    $(".modalDelete").removeClass("is-active");
});

//отмена, закрыть модальное окно
$(".cancel").click(function () {
    $(".modalDelete").removeClass("is-active");
});
