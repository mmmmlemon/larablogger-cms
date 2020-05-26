//функции которые могут использоваться во всем сайте

//ajax-функция для очистки папки temp
var clear_temp = function () {
    $.ajax('/clear_temp', {
        success: function (data, status, xhr) {
            console.log("%cTemp directory has been purged.", "color: red;");
        }
    });
}
