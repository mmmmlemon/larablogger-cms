//scripts for media browser page

//Plyr, video player
const player = new Plyr('#player');

//show preview modal
$(document).on('click', ".preview", function () {
    $("#preview-modal").addClass("is-active fade-in");
    //if image, show image tag
    if ($(this).data("type") === "image") {
        $("#content-in-modal").attr("style", "display: block");
        $("#content-in-modal").attr("src", $(this).data("url"));

        $("#content-in-modal").on('load', function () {
            $("#content-in-modal").attr("style", "display: block;");
            var screen_height = window.screen.height;
            var img_height = $("#content-in-modal").height();
            var img_width = $("#content-in-modal").width();

            if (img_height > screen_height) {
                var new_img_height = img_height / 1.368;
                var new_img_width = img_width / 1.368;
                $("#content-in-modal").width(new_img_width).height(new_img_height)
            }
        });

    }
    //if video, show Plyr player
    if ($(this).data("type") === "video") {
        $("#player_div").attr("style", "display: block;");
        $("#content-video").attr("src", $(this).data("url"));
        player.source = {
            type: 'video',
            title: 'Preview',
            sources: [{
                src: $(this).data("url"),
                type: 'video/mp4',
                size: 720,
            }, ],
        };

    }
});

//close the preview modal
$("#modal-close").click(function () {
    $("#preview-modal").removeClass("is-active");
    player.stop(); //стопорим плеер, чтобы видео не играло в фоне :)
    $("#content-in-modal").attr("style", "display: none");
    $("#player_div").attr("style", "display: none;");
    $("#content-in-modal").width("").height("");
});

//delete file
$("#submit_modal").on('click', function (e) {
    e.preventDefault();
    $(this).attr("disabled","disabled");
    $("#modal_form").submit();
});

//show delete file modal
$(".deleteFile").click(function () {
    $(".modalDelete").addClass("is-active fade-in");
    $("#modal_input").attr("value",$(this).data("id"));
});

$(".delete").click(function () {
    $(".modalDelete").removeClass("is-active");
});

$(".cancel").click(function () {
    $(".modalDelete").removeClass("is-active");
});

//on copy link
$(".copy_link").on("click", function(){
    var url = $(this).data("url");
    var temp = $("<input>");
    $("body").append(temp);
    temp.val(url).select();
    document.execCommand("copy");
    temp.remove();

    var rand = Math.floor( Math.random() * 100 / 2 ) * 2;

    $("#notifications").append(`<div id="notification_${rand}" class="white-bg link_copied fade-in">The URL has been copied! <i style="margin-left:5px;" class="fa fa-link"></i></div>`);

    setTimeout(function(){
        $(`#notification_${rand}`).remove();
    }, 3500);
});

