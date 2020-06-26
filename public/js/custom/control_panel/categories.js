//сscripts for categories page

//show delete category modal
$(".showModalDelete").click(function () {
    $(".modalDelete").addClass("is-active fade-in");
    $("#modal_post_title").text($(this).data("title"));
    $("#modal_form_input").val($(this).data("id"));
});

//confirm delete
$("#submit_modal").on('click', function () {
    $(this).attr("disabled","disabled")
    $("#modal_form").submit();
});

//close the modal
$(".delete").click(function () {
    $(".modalDelete").removeClass("is-active");
});

//cancel, close the modal
$(".cancel").click(function () {
    $(".modalDelete").removeClass("is-active");
});
