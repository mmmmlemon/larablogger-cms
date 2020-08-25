//scripts for edit_post page

//clear temp folder
clear_temp();

//richText

$('#post_content').trumbowyg({
    tagsToRemove:['script','img'],
    autogrow: true,
    imageWidthModalEdit: true,
    urlProtocol: true,
    btns: [
        ['viewHTML'],
        ['undo', 'redo'], // Only supported in Blink browsers
        ['formatting'],
        ['strong', 'em'],
        ['superscript', 'subscript'],
        ['link'],
        ['insertImage'],
        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
        ['unorderedList', 'orderedList'],
        ['horizontalRule'],
        ['removeformat'],
        ['fullscreen']
    ]
});

//tagEditor
BulmaTagsInput.attach('input[data-type="tags"], input[type="tags"], select[data-type="tags"], select[type="tags"]', {
    tagClass: 'is-rounded is-grey'
});

//character counter
$(document).ready(function () {
    $('#post_title').charCounter();
});

//the tr in list of files that will be removed, after the file is removed
var tr;

//list of uploaded files
var uploaded_files = [];

//Plyr, video player
const player = new Plyr('#player');

//get number of files in post
//if there are 0 files, the file table won't be shown
var num_of_files = $("#tbody").children().length;

//show delete file modal
$(document).on("click", ".delete_media", function () {
    //view modal
    $(".modalDelete").addClass("is-active fade-in");
    //if parameter data-id is undefined, tho modal will recieve file name instead of file id from the database
    //and the file will be deleted by the filename
    if ($(this).data("id") === undefined) {
        $("#submit_modal").data("id", $(this).data("filename"));
    } else //if data id is defined, the modal will recieve and id, and the file will by deleted by the id from the database
    {
        $("#submit_modal").data("id", $(this).data("id"));
    }

    //write tr row from the table to delete it later
    if($(this).data("ismobile") === true){
        tr = $(this).parent().parents()[2];
    }
    else{
        tr = $(this).parent().parents()[0];
    }
});

//close delete modal
$("#close_delete_modal").click(function () {
    $(".modalDelete").removeClass("is-active fade-in");
});


//delete file ajax request
function send_delete_media_request(media_id) {
    alert("D E L E T")
    //csrf-token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //ajax-request
    $.ajax({
        type: 'POST',
        url: '/delete_media',
        data: {
            id: media_id
        },
        success: function (response) {
            num_of_files--;
            $(tr).attr("style", "display: none;"); //remove tr row from the table
            //hide modal
            $(".modalDelete").removeClass("is-active fade-in");
            if (num_of_files === 0) {
                $("#appended_files").remove();
                $("#file_browser").addClass("invisible").removeClass("fade-in");
                $("#no_files").removeClass("invisible").addClass("fade-in");
            }
            var index_of_el = uploaded_files.findIndex(el => el.includes(media_id));
            if (index_of_el != -1) {
                uploaded_files.splice(index_of_el, 1);
                console.log("uploaded files", uploaded_files)
            }
            console.log("%cThe file has been succesfully deleted.", "color: red;");
        }
    });
}

//confirm delete file
$("#submit_modal").click(function () {
    //remove tr row in the table and send ajax request
    send_delete_media_request($(this).data("id"));
});


//show file preview
$(document).on('click', ".preview", function () {
    $("#preview-modal").addClass("is-active fade-in");
    //if image, show img tag
    if ($(this).data("type") === "image") {
        $("#content-in-modal").attr("style", "display: block");
        $("#content-in-modal").attr("src", $(this).data("url"));

        $("#content-in-modal").on('load', function () {
            $("#content-in-modal").attr("style", "display: block;");
            var screen_height = window.screen.height;
            var img_height = $("#content-in-modal").height();
            var img_width = $("#content-in-modal").width();
            if (img_height > screen_height) {
                var new_img_height = img_height / 1.368;
                var new_img_width = img_width / 1.368;
                $("#content-in-modal").width(new_img_width).height(new_img_height)
            }
        });
    }
    //if video, show Plyr player
    if ($(this).data("type") === "video") {
        $("#player_div").attr("style", "display: block;");
        $("#content-video").attr("src", $(this).data("url"));
        player.source = {
            type: 'video',
            title: 'Preview',
            sources: [{
                src: $(this).data("url"),
                type: 'video/mp4',
                size: 720,
            }, ],
        };
    }
});

//close preview modal
$("#modal-close").click(function () {
    $("#preview-modal").removeClass("is-active");
    player.stop();
    $("#content-in-modal").attr("style", "display: none");
    $("#player_div").attr("style", "display: none;");
});


//submit post
$("#submit_post").click(function () {
    $(this).attr("disabled", "disabled");
    //csrf-token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //ajax-request
    $.ajax({
        type: 'POST',
        url: `/post/${$("#post_id").val()}/edit`,
        data: {
            post_id: $("#post_id").val(),
            file_list: JSON.stringify(uploaded_files),
            post_title: $("#post_title").val(),
            post_content: $(".textarea").val(),
            post_pinned: $("#pinned_checkbox").is(":checked"),
            post_visibility: $("#publish_checkbox").is(":checked"),
            post_date: $("#publish_date").val(),
            post_category: $("#post_category").val(),
            tags: $("#tags").val()
        },
        success: function (response) {
            window.location.replace(document.referrer);
            //window.history.back();
        }
    });
});
