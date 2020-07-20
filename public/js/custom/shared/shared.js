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
var parapgraph_workaround = function () {
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