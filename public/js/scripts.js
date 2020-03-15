    
    //вызов меню в мобильной версии сайта
    $("#nav-toggle").click(function() {
        var nav = $("#nav-menu");
        var className = $(nav).attr("class");
        $(nav).toggleClass("is-active");
    });

    //вызвать модальное окно Contacts
    $("#showModalContact").click(function() {
        $(".modal").addClass("is-active fade-in");  
      });
      
    $(".modal-close").click(function() {
        $(".modal").removeClass("is-active");
    });