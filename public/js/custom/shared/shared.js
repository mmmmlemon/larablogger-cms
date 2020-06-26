//shared functions

//ajax-request for temp folder purge
var clear_temp = function () {
    $.ajax('/clear_temp', {
        success: function (data, status, xhr) {
            console.log("%cTemp directory has been purged.", "color: red;");
        }
    });
}