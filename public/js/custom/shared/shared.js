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

    //set 'view_type' cookie
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({type:'POST',url:'/setCookie'});
 
//on document fully loaded
$(document).ready(function(){
    
    function change_preferred_view(view)
    {
        //csrf-token for ajax request
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //ajax-request, change preferred view in cookies
        $.ajax({
            type: 'POST',
            url: `/setCookie`,
            data: {
                view_type: view,
                change_view: true
            },
            //redirect to current page
            success: function (response) {
                var url = window.location.toString();
                window.location.replace(url.slice(0,url.indexOf("?page=")+1)); //remove '?page' attribute, to avoid redirect to non existant page
            }
        });
    }

    //when 'List View' is selected
    $("#list_view").on("click", function(){
        change_preferred_view("list");
    });

    //when 'Grid View' is selected
    $("#grid_view").on("click", function(){
        change_preferred_view("grid");
    });


    //page scroll detection
    var lastScrollTop = 0;
    $(window).scroll(function (event) {
        var st = $(this).scrollTop();
        if (st > lastScrollTop){
            // if scrolled more than 1000 pixels
            if(st >= 1000)
            {
                $("#rollup_button").removeClass("invisible").addClass("fade-in"); //show "scroll to top" button
            }
        } else {
            // if upscrolled more than 1000 pixels

            if(st <= 1000)
            {
                $("#rollup_button").addClass("invisible").removeClass("fade-in"); //hide "scroll to top" button
            }
        }
        lastScrollTop = st;  
    });

    //scroll to top button, on click scroll to top
    $("#rollup_button").on('click', function(){
        $('html, body').animate({scrollTop: '0px'}, 500);
        $("#rollup_button").addClass("invisible");
    });

});

//Accept Cookies Message
$(document).ready(function(){
    //ajax-request, check if cookies were accepted already
    $.ajax({
        type: 'GET',
        url: `/check_first_visit`,
        //success
        success: function (response) {
            if(response === "1") //if response == 1, cookies were not acceped
            {   
                //send ajax-request on clicking "Got it" button
                $("#ok_cookie").click(function(){   
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    //ajax-request, set cookie 'visitedAlready', which means user accepted the cookies
                    $.ajax({
                        type: 'POST',
                        url: `/set_first_visit`,
                        //success
                        success: function (response) {    
                            //remove cookies notification 
                            $("#cookies_message").remove();
                        }
                    });
                })
            }
            else
            {   //if response != 1, remove cookies notification
                $("#cookies_message").remove();
            }
        }
    });

    //search bar, on typing
    $("#search_bar").on('keyup', function(){
        //if search bar is empty
        if($(this).val() === "")
        {   //remove all search results
            $("#search_results").html("");
        }
        else
        {   
            //ajax setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //ajax-request, search for posts
            $.ajax({
                type: 'POST',
                url: '/search_post',
                data: {
                    value: $(this).val()
                },
                //on success
                success: function(response)
                {   
                   //parse json from response with all found posts
                   var result = JSON.parse(response);
                    
                   //show loader
                   $("#search_results").html("");
                   $("#search_bar_div").toggleClass("is-loading");
                    
                   //for each element from json
                   for(var el of result)
                   {    
                        //show search results preview
                        $("#search_results").append(`<div class='white-bg search_results_block'>
                        <h1 class="subtitle"><a href="/post/${el.id}">${el.post_title}</a></h1>
                        <p>${el.post_content}</p><br>
                        <p><a href="/category/${el.category}">${el.category}</a> | ${el.date}</p></div>`);
                   }
                }
            });

            //remove loader
            $("#search_bar_div").toggleClass("is-loading");
        }
    });

});







  
