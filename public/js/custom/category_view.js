//scripts for category_view page

const players = Plyr.setup('.video-player');

//show image preview modal
$(".imagee").click(function () {
    $("#img-modal").addClass("is-active fade-in");
    $("#img-in-modal").attr("src", $(this).attr("src"));
    $("#link-in-modal").attr("href", $(this).attr("src"));

    var screen_height = window.screen.height;
    var img_height = $("#img-in-modal").height();
    var img_width = $("#img-in-modal").width();

    if (img_height > screen_height) {
        var new_img_height = img_height / 1.368;
        var new_img_width = img_width / 1.368;
        $("#img-in-modal").width(new_img_width).height(new_img_height);
    }

});

$("#modal-close").click(function () {
    $("#img-modal").removeClass("is-active");
    $("#img-in-modal").width("").height("");
});

$('.share-button').on('click', function () {
    var id = "#" + $(this).attr('for');
    if ($(id).hasClass('invisible')) {
        $(id).removeClass("invisible").addClass("fade-in");
    } else {
        $(id).addClass("invisible");
    }
});
