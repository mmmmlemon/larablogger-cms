//scripts for posts page
$("#submit_modal").on('click', function (e) {
    e.preventDefault();
    $(this).attr("disabled","disabled");
    $("#modal_form").submit();
});

//show Contacts modal
$(".showModalDelete").click(function () {
    $(".modalDelete").addClass("is-active fade-in");
    $("#modal_post_title").text($(this).data("title"));
    $("#modal_form_input").val($(this).data("id"));
});

$(".delete").click(function () {
    $(".modalDelete").removeClass("is-active");
});

$(".cancel").click(function () {
    $(".modalDelete").removeClass("is-active");
});
