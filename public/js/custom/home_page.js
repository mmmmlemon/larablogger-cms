$('.share-button').on('click', function(){
    var id = "#"+$(this).attr('for');
    if($(id).hasClass('invisible'))
    {
        $(id).removeClass("invisible");
    }
    else{
        $(id).addClass("invisible");
    }
    
})