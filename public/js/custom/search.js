$(document).ready(function(){
    var post_content = $(".post_content");

    for(var p of post_content){
        var str = $(p).text();
        var search_value = $("#search_bar").val();
        var new_str = highlight_substr(str, search_value);
        $(p).html(new_str);
    }
});