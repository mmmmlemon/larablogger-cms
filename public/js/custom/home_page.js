//scripts for the index page

$('.share-button').on('click', function () {
    var id = "#" + $(this).attr('for');
    if ($(id).hasClass('invisible')) {
        $(id).removeClass("invisible").addClass("fade-in");
    } else {
        $(id).addClass("invisible");
    }
});

var img_height = $("#img-in-modal").height();

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
        $("#img-in-modal").width(new_img_width).height(new_img_height)
    }
});

$("#modal-close").click(function () {
    $("#img-modal").removeClass("is-active");
    $("#img-in-modal").width("").height("");
});


//list of videos on this page
var video_list = [];

$(document).ready(function(){
    var video_players = $(".video-player");
    for(var video of video_players){
        video_list.push({id:$(video).data("id"), viewed: false});
    }
});

//Plyr
const players = Plyr.setup('.video-player');

for(var player of players)
{
    player.on('play', event => {
        const instance = event.detail.plyr;
        var id = $(instance.media).data("id");
        var index = video_list.findIndex(x => x.id === id);
        video_list[index].viewed = true;
      });
}

// show grid after the page has been loaded
$(window).on('load',function(){
    $(".grid_element").removeClass("transparent").addClass("fade-in");
})