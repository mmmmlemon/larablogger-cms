//scripts for view_media

if($("#file-js-example").length != 0)
{
    const fileInput = document.querySelector('#file-js-example input[type=file]');
    fileInput.onchange = () => {
    if (fileInput.files.length > 0) {
        const fileName = document.querySelector('#file-js-example .file-name');
        fileName.textContent = fileInput.files[0].name;
    }
    }
}

if($("#thumbnail_uploader").length != 0)
{
    const fileInputt = document.querySelector('#thumbnail_uploader input[type=file]');
    fileInputt.onchange = () => {
        if (fileInputt.files.length > 0) {
            const fileName = document.querySelector('#thumbnail_uploader .file-name');
            fileName.textContent = fileInputt.files[0].name;
        }
    }
}

//Plyr, video player
const player = new Plyr('#player', {
    captions: {
        active: true
    }
});

//previous active tab and display div
var previous_tab = $("#tab_thumbnail");
var previous_display = $("#thumbnail");

//tab switch function
function switch_tab(current_tab, current_display) {
    if (current_tab != previous_tab) {
        $(current_tab).addClass("is-active");
        $(current_display).removeClass("invisible").addClass("fade-in");
        previous_tab.removeClass("is-active");
        previous_display.addClass("invisible");
        player.pause();

        previous_tab = $(current_tab);
        previous_display = $(current_display);
    }

}

$("#tab_thumbnail").on('click', function () {
    switch_tab("#tab_thumbnail", "#thumbnail");
    $("#footer").attr("style", `top: ${$(document).height()}px;`)
});

$("#tab_subtitles").on('click', function () {
    switch_tab("#tab_subtitles", "#subtitle_table");
    $("#footer").attr("style", `top: ${$(document).height()}px;`)
});

$("#tab_preview").on('click', function () {
    switch_tab("#tab_preview", "#preview");
    $("#footer").attr("style", `top: ${$(document).height()}px;`)
});


//if user adds subtitles
$("#subtitle_input").on('change', function (el) {
    //form a list of subtitles under the input
    var files = el.target.files;
    $("#subtitle_list").html("<b>Attached subtitle files</b>");
    for (var i = 0; i < files.length; i++) {
        $("#subtitle_list").append(`<h1>${files[i].name}</h1>`);
    }
});


$(document).on('click', '.hide_subs', function () {
    var sub = $(this).data("sub");
    var button = $(this);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //ajax-request
    $.ajax({
        type: 'POST',
        url: '/control/media/change_subs_status',
        data: {
            sub_id: sub,
            visibility: 0
        },
        success: function (response) {
            console.log("The subtitle file has been hidden.");
            button.removeClass("is-warning").addClass("is-primary").removeClass("hide_subs").addClass("show_subs");
            button.attr("data-tooltip", "Enable these subtitles");
            button.html(`<i class="fas fa-eye"></i>`);
        }
    });
});

$(document).on('click', '.show_subs', function () {
    var sub = $(this).data("sub");
    var button = $(this);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/control/media/change_subs_status',
        data: {
            sub_id: sub,
            visibility: 1
        },
        success: function (response) {
            console.log("The subtitle file has been shown.");
            button.removeClass("is-primary").addClass("is-warning").removeClass("show_subs").addClass("hide_subs");
            button.attr("data-tooltip", "Disable these subtitles");
            button.html(`<i class="fas fa-eye-slash"></i>`);
        }
    });
});

$(document).on('click', '.delete_subs', function () {
    var sub = $(this).data("sub");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: '/control/media/delete_subs',
        data: {
            sub_id: sub
        },
        success: function (response) {
            console.log("The subtitle file has been deleted.");
            $(`#sub${sub}`).remove();
            var count = $(`#subs_list`).children().length;
            if (count === 0) {
                $("#subs_table").html("").append("<tr><td colspan='3'>No subtitles attached</td></tr>")
            }
        }
    });
});



//EDIT SUBTITLES
var elem_children; //label and input which need to be shown
var edit = false; //is editing mode active
var curr_elem, prev_elem;
var id;

//when you click on the table cell
$(document).on('click', '.edit_subs', function (el) {
    //if editing was activated already on the other file
    if (edit == true) { //close it and open the new
        elem_children.eq(0).removeClass('invisible').addClass('fade-in'); //remove the label
        elem_children.eq(1).addClass('invisible').addClass("ignore"); //and show input field
    }
    id = $(this).data("sub");
    var elem = $(`#sub_file_${id}`); 
    elem_children = $(elem).children(); 
    elem_children.eq(0).addClass('invisible');
    elem_children.eq(1).removeClass('invisible').removeClass("ignore").addClass('fade-in'); 
    edit = true;
});

//ajax-request, edit subtitle file display name
$(document).on('click', '.edit_display_name', function (el) {

    var value = $(el.target).parent().children().first().val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/control/media/change_subs_display_name',
        data: {
            display_name: value,
            sub_id: id
        },
        success: function (response) {
     
            console.log("Display name has been changed for the subtitle file.");
            elem_children.eq(0).removeClass('invisible').addClass('fade-in').html(value); 
            elem_children.eq(1).addClass('invisible').addClass("ignore"); 
            edit = false; 
            $(`#sub_file_${id}`).find("input").val(value);
        }
    });

});

$("#remove_thumbnail").on('click', function(e){
    e.preventDefault();
    $(this).attr("disabled","disabled");
    $("#remove_thumbnail_form").submit();
});

$("#submit_form").on('click',function(e){
    e.preventDefault();
    $(this).attr("disabled","disabled");
    $("#form").submit();
});
