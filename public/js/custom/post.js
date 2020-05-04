$('.textarea').richText({
    imageUpload:false,
    videoEmbed:false,
    table: false,
    fileUpload:false,
    heading: false,
    fonts: false,
    ul: false,
    leftAlign: false,
    centerAlign: false,
    rightAlign: false,
    justify: false,
    code: false
  });

  $(document).ready(function(){
    $('#username').charCounter();
  });

    const player = new Plyr.setup('.player');

      //вызвать модальное окно Contacts
      $(".imagee").click(function() {
        $("#img-modal").addClass("is-active fade-in"); 
        $("#img-in-modal").attr("src", $(this).attr("src"));
        $("#link-in-modal").attr("href", $(this).attr("src"));
      });
      
    $("#modal-close").click(function() {
        $("#img-modal").removeClass("is-active");
    });

