const players = Plyr.setup('.video-player');

//вызвать модальное окно с картинкой
$(".imagee").click(function() {
$("#img-modal").addClass("is-active fade-in"); 
$("#img-in-modal").attr("src", $(this).attr("src"));
$("#link-in-modal").attr("href", $(this).attr("src"));
});

$("#modal-close").click(function() {
$("#img-modal").removeClass("is-active");
});

$('.share-button').on('click', function(){
    var id = "#"+$(this).attr('for');
    if($(id).hasClass('invisible'))
    {
        $(id).removeClass("invisible").addClass("fade-in");
    }
    else{
        $(id).addClass("invisible");
    }
});