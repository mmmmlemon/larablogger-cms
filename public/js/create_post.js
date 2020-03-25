//обработка нажатия кнопки Publish
$("#publish_checkbox").click(function(){
    if($("#publish_checkbox").is(":checked"))
    {
        $("#publish_date").prop("disabled", true)
    } else {
        $("#publish_date").prop("disabled", false)
    }
});