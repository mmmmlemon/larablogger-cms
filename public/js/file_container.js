$.fn.fileContainer = function () {
    var div = this;

    var input = $("input[type='file'");

    $(input).on('change', function(){
        var files = input[0].files;
        console.table(files);
        $(div).empty();

        var count = 0;
        $(div).append(`<h1 class='title is-5'>Files (${files.length})</h1>`);
        for(file of files){
            $(div).append(`<div class="column is-12"><h1 class="title is-6">${file.name}</h1></div>`);
          count++;
        }
    });

  };
  
  $("#clear_files").click(function(e){
    e.preventDefault();
    $("input[type='file']").val("");
    $("#file_container").empty().append("<h1 class='title is-5'>Files (0)</h1>");
  })