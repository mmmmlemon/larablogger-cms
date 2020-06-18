    
    //вызов меню в мобильной версии сайта
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

   
    //вызвать модальное окно Contacts
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
    //редактор текста для формы связи
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

    //отправка сообщения в форме обратной связи
    $("#contact_submit").on("click", function(e){

        e.preventDefault();

        //если текстовый редактор пустой
        if($("#contact_feedback").val() === "<div><br></div>")
        {   
            //то ничего не делаем, а фокусируемся на нем
            $("#contact_feedback").focus();
        }
        else //если там есть какой-то текст, то отправляем письмо
        {
            //выключаем кнопку отправки, чтобы пользователь случайно не нажал два раза
            $(this).attr("disabled","disabled");
            
            //прячем форму и показываем оверлей с анимацией загрузки
            $("#contact_content").addClass("invisible");
            $("#contact_overlay").removeClass("invisible");
            $("#contact_close").addClass("invisible");

            // установка заголовка с csrf-токеном
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //отправка запроса
            $.ajax({
                type: 'POST',
                url: `/send_feedback`,
                data: {
                    contact_email : $("#contact_email").val(),
                    contact_title: $("#contact_title").val(),
                    contact_feedback: $("#contact_feedback").val(),
                },
                //при успешном завершении запроса
                success: function (response) {
                    //прячем спиннер с анимацией
                    $("#contact_ring").addClass("invisible");
                    //показываем анимацию почтового конверта
                    $("#contact_envelope").removeClass("invisible").addClass("bounce-in");
                    //прячем одно сообщение и показываем другое
                    $("#contact_sending").addClass("invisible");
                    $("#contact_sent").removeClass("invisible").addClass("fade-in");

                    //делаем кнопку Окей рабочей
                    $("#contact_okay").removeAttr("disabled");
                }   
            });
        }
 
    });

    //по нажатию на кнопку Окей после отправки письма
    $("#contact_okay").on('click', function(){
        //прячем и\или возвращаем все как было до отправки и закрываем модальное окно
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