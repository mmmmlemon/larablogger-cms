//скрипты для страницы с media

//Plyr, видеоплеер
const player = new Plyr('#player');

//показать превью файла
$(document).on('click', ".preview", function () {
    $("#preview-modal").addClass("is-active fade-in");
    //если картинка, то показываем тег img
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
    //если видео, то показываем плеер
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

//закрыть модальное окно с превью
$("#modal-close").click(function () {
    $("#preview-modal").removeClass("is-active");
    player.stop(); //стопорим плеер, чтобы видео не играло в фоне :)
    $("#content-in-modal").attr("style", "display: none");
    $("#player_div").attr("style", "display: none;");
    $("#content-in-modal").width("").height("");
});
