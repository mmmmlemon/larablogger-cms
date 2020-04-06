    
    //вызов меню в мобильной версии сайта
    $("#nav-toggle").click(function() {
        var nav = $("#nav-menu");
        var className = $(nav).attr("class");
        $(nav).toggleClass("is-active");
    });

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