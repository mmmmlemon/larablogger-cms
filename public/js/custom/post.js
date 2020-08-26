//scripts for posts page

$(document).ready(function(){
    $('#comment_textarea').trumbowyg({
        tagsToRemove:['script','img'],
        autogrow: true,
        imageWidthModalEdit: true,
        urlProtocol: true,
        btns: [
            ['undo', 'redo'], // Only supported in Blink browsers
            ['strong', 'em'],
            ['link'],
            ['removeformat'],
            ['fullscreen']
        ]
    });
    
    $(document).ready(function () {
        $('#username').charCounter();
    });
    
    
    //list of videos on this page
    var video_list = [];

    $(document).ready(function(){
        var video_players = $(".player");
        for(var video of video_players){
            video_list.push({id:$(video).data("id"), viewed: false});
        }
    });

    //Plyr
    const players = Plyr.setup('.player');
    if(players !== null)
    {
        for(var player of players)
        {
            //on play button clicked, increment view_count for the video
            player.on('play', event => {
                const instance = event.detail.plyr;
                var id = $(instance.media).data("id");
                var index = video_list.findIndex(x => x.id === id);
                if(video_list[index].viewed === false)
                {
                    video_list[index].viewed = true;
    
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
    
                    $.ajax({
                        type: 'POST',
                        url: '/control/increment_view_count',
                        data: {
                            media_id: id
                        },
                        success: function(response){
                            //
                        }
                    })
                }
                        
            });
        } 
    }

    
    $('.share-button').on('click', function () {
        var id = "#" + $(this).attr('for');
        if ($(id).hasClass('invisible')) {
            $(id).removeClass("invisible").addClass("fade-in");
        } else {
            $(id).addClass("invisible");
        }
    });
    
    //show image preview modal
    $(".imagee").click(function () {
        $("#img-modal").addClass("is-active fade-in");
        $("#img-in-modal").attr("src", $(this).attr("src"));
        $("#link-in-modal").attr("href", $(this).attr("src"));
    
        $("#img-in-modal").on('load', function () {
    
            var screen_height = window.screen.height;
            var img_height = $("#img-in-modal").height();
            var img_width = $("#img-in-modal").width();
            if (img_height > screen_height) {
                var new_img_height = img_height / 1.368;
                var new_img_width = img_width / 1.368;
                $("#img-in-modal").width(new_img_width).height(new_img_height)
            }
        })
    });
    
    $("#modal-close").click(function () {
        $("#img-modal").removeClass("is-active");
        $("#img-in-modal").width("").height("");
    });

    if (window.location.hash) {
        var hash = window.location.hash.substring(1);
        if(hash.includes("comment_anchor"))
        {
            var elem = $("#"+hash).addClass("comment-blinking-anim");
        }
    }

    $(".action-reply-button").on('click', function(e){
        var id = $(this).data("id");
        var username = $(this).data("user");

        $("#reply_to").attr("value",id)
        $("#reply_p").text(`A reply to user ${username}`).attr("href",`#comment_anchor_${id}`);
        $("#remove_reply").removeClass("invisible");
    });

    $("#remove_reply").on('click', function(){
        $("#reply_to").val("");
        $("#reply_p").text("");
        $(this).addClass("invisible");
    });


    //paragraph fix
    paragraph_workaround();

    
    $(".a_reply_tag").click(function(){
        var anchor = $(this).attr("href");
        $(anchor).removeClass("comment-blinking-anim").delay(1).queue(function() {  // Wait for 1 second.
            $(this).addClass("comment-blinking-anim").dequeue();
        });
    });
    
});

