    
    //navigation toggle on mobile
    $("#nav-toggle").click(function() {
        var nav = $("#nav-menu");
        var className = $(nav).attr("class");
        $(nav).toggleClass("is-active");
        $("#navigation").toggleClass("slideDown");
    });

    if($(window).width() <= 1080)
    {
        $("#home_button").removeClass("invisible").addClass("fade-in");
    }

    $(window).resize(function(){
        if($(window).width() <= 1080)
        {
            $("#home_button").removeClass("invisible").addClass("fade-in");
        }
        else
        {
            $("#home_button").addClass("invisible");
        }
    
    })

    //show Feedback Form modal
    $("#showModalContact").click(function() {
        $("#contact-modal").addClass("is-active fade-in");  
      });
      
    $(".modal-close").click(function() {
        $("#contact-modal").removeClass("is-active");
    });

    $(document).ready(function(){
        var height = $(document).height();
        $("#footer").css("top", height);
    })

    //richText
    //text editor for Feedback Form
    $('.contact_feedback').richText({
        imageUpload: false,
        videoEmbed: false,
        fileUpload: false,
        fonts: false,
        fontColor: false,
        fontSize: false,
        table: false,
        removeStyles: false,
        code: false,
        heading: false
    });

    //send an e-mail through the Feedback Form
    $("#contact_submit").on("click", function(e){

        e.preventDefault();

        //if text editor is empty
        if($("#contact_feedback").val() === "<div><br></div>")
        {   
            //do nothing and focus on it
            $("#contact_feedback").focus();
        }
        else //if it's not empty, send an e-mail
        {
            //disable the "Send" button
            $(this).attr("disabled","disabled");
            
            //hide form and show animation
            $("#contact_content").addClass("invisible");
            $("#contact_overlay").removeClass("invisible");
            $("#contact_close").addClass("invisible");

            //csrf-token for ajax request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //ajax request
            $.ajax({
                type: 'POST',
                url: `/send_feedback`,
                data: {
                    contact_email : $("#contact_email").val(),
                    contact_title: $("#contact_title").val(),
                    contact_feedback: $("#contact_feedback").val(),
                },
                //if success
                success: function (response) {
                    //hide animation
                    $("#contact_ring").addClass("invisible");
                    //show envelope animation
                    $("#contact_envelope").removeClass("invisible").addClass("bounce-in");
                    //hide one message and show another
                    $("#contact_sending").addClass("invisible");
                    $("#contact_sent").removeClass("invisible").addClass("fade-in");

                    //show OK button
                    $("#contact_okay").removeAttr("disabled");
                }   
            });
        }
 
    });

    //OK button clicked, after e-mail ajax request
    $("#contact_okay").on('click', function(){
        //bring every element back to the initial state and hide modal
        $("#contact_close").removeClass("invisible");
        $(this).attr("disabled","disabled");
        $("#contact_sent").addClass("invisible");
        $("#contact_sending").removeClass("invisible");
        $("#contact_envelope").addClass("invisible");
        $("#contact_ring").removeClass("invisible");

        $("#contact_overlay").addClass("invisible");
        $("#contact_content").removeClass("invisible");
        $("#contact_submit").removeAttr("disabled");
        $("#contact_email").val("");
        $("#contact_title").val("");
        $("#contact_feedback").val("<div><br></div>").trigger("change");
        $("#contact-modal").removeClass("is-active");
    });