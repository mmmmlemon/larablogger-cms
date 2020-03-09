    
    //вызов меню в мобильной версии сайта
    $("#nav-toggle").click(function() {
        var nav = $("#nav-menu");
        var className = $(nav).attr("class");
        $(nav).toggleClass("is-active");
    });