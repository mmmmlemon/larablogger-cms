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
 
$(document).ready(function(){

        
        //list view press
        $("#list_view").on("click", function(){
            //csrf-token for ajax request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //ajax-request
            $.ajax({
                type: 'POST',
                url: `/setCookie`,
                data: {
                   view_type: "list",
                   change_view: true
                },
                //redirect to posts on success
                success: function (response) {
                    var url = window.location.toString();
                    window.location.replace(url.slice(0,url.indexOf("?page=")+1));;
                }
            });
        });

        //list grid press
        $("#grid_view").on("click", function(){
            //csrf-token for ajax request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //ajax-request
            $.ajax({
                type: 'POST',
                url: `/setCookie`,
                data: {
                   view_type: "grid",
                   change_view: true
                },
                //redirect to posts on success
                success: function (response) {
                    var url = window.location.toString();
                    window.location.replace(url.slice(0,url.indexOf("?page=")+1));
                }
            });
        });


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

//Cookie Message
$(document).ready(function(){
    //ajax-request
    $.ajax({
        type: 'GET',
        url: `/check_first_visit`,
        //success
        success: function (response) {
            if(response === "1")
            {
                // $("#cookies_message").removeClass("invisible").addClass("slideUp");
                $("#ok_cookie").click(function(){   
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    //ajax-request
                    $.ajax({
                        type: 'POST',
                        url: `/set_first_visit`,
                        //success
                        success: function (response) {     
                            $("#cookies_message").remove();
                        }
                    });
                })
        

            }
            else
            {
                $("#cookies_message").remove();
            }
        }
    });


    $("#search_bar").on('keyup', function(){
        if($(this).val() === "")
        {
            $("#search_results").html("");
        }
        else
        {   
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '/search_post',
                data: {
                    value: $(this).val()
                },
                success: function(response)
                {
                   var result = JSON.parse(response);
                   console.log(result);
                    
                   $("#search_results").html("");
    
                   for(var el of result)
                   {
                        $("#search_results").append(`<div class='white-bg search_results_block'>
                        <h1 class="subtitle"><a href="/post/${el.id}">${el.post_title}</a></h1>
                        <p>${el.post_content}</p><br>
                        <p><a href="/category/${el.category}">${el.category}</a> | ${el.date}</p></div>`);
                   }
                   
                }
            });
        }
        
   
    });

});







  
