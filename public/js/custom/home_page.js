$('.share-button').on('click', function(){
    var id = "#"+$(this).attr('for');
    if($(id).hasClass('invisible'))
    {
        $(id).removeClass("invisible").addClass("fade-in");
    }
    else{
        $(id).addClass("invisible");
    }
    
})