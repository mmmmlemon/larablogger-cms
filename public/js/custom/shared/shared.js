//shared functions
    //ajax-request for temp folder purge
    var clear_temp = function () {
        $.ajax('/clear_temp', {
            success: function (data, status, xhr) {
                console.log("%cTemp directory has been purged.", "color: red;");
            }
        });
    }

    //trumblewyg paragraph margin workaround
    var paragraph_workaround = function () {
        var p_fixes =  $(".p_fix"); //get all elements that require a workaround
        for(p of p_fixes)
        {
            //get all p elements in
            var elems = $(p).find("p");  
            //remove bottom margin
            for(el of elems)
            {
                $(el).css("margin-bottom" ,"0")
            }
        }
    }


$(document).ready(function(){

    var lastScrollTop = 0;
    $(window).scroll(function (event) {
        var st = $(this).scrollTop();
        if (st > lastScrollTop){
            // downscroll code
            if(st >= 1000)
            {
                $("#rollup_button").removeClass("invisible").addClass("fade-in");
            }
        } else {
            // upscroll code

            if(st <= 1000)
            {
                $("#rollup_button").addClass("invisible").removeClass("fade-in");
            }
        }
        lastScrollTop = st;  
    });

    $("#rollup_button").on('click', function(){
        $('html, body').animate({scrollTop: '0px'}, 500);
        $("#rollup_button").addClass("invisible");
    });

});



  
