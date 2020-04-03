//счетчик символов для jQuery
$.fn.charCounter = function () {
    var input = this;
 
    var maxlength = $(input).attr("maxlength");
    $("<p class='help is-dark' id='counter_for_subtitle'>0/"+maxlength+"</p>").insertAfter(input);
    var counter = $(input).next();

    function count_chars(){
    var txt = $(input).val();
    $(counter).text(txt.length+"/"+maxlength);
    } 

    count_chars();

    $(input).keyup(function(){
    count_chars();
    });

    $(input).keydown(function(){
    count_chars();
    });

    $(input).change(function(){
    count_chars();
    });

    return this;
  };
  
  