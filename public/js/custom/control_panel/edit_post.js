//scripts for edit_post page

//clear temp folder
clear_temp();

//richText
$('#post_content').richText({
    imageUpload: false,
    videoEmbed: false,
    fileUpload: false,
    fileUpload: false,
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
    tr = $(this).parent().parents()[0];
});

//close delete modal
$("#close_delete_modal").click(function () {
    $(".modalDelete").removeClass("is-active fade-in");
});

//confirm delete file
$("#submit_modal").click(function () {
    //remove tr row in the table and send ajax request
    send_delete_media_request($(this).data("id"));
});

//delete file ajax request
function send_delete_media_request(media_id) {
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

//turn off autoDiscover for dropzone
Dropzone.autoDiscover = false;

//dropzone
var dropzone = $("#dropzone_form").dropzone({
    chunking: true, 
    chunkSize: 20000000, //20 Mb
    retryChunks: false,
    addRemoveLinks: true, 
    paramName: 'file',
    forceChunking: true,
    maxFiles: 20,
    maxFilesize: 4000, //4 Gb
    parallelUploads: 20,
    acceptedFiles: '.jpg,.jpeg,.png,.mp4',
    init: function () {
        var dz = this;
            this.on('sending', function (file, xhr, data) {
            console.log(`%cSending file ${file.name}`, 'color:grey;');
            data.append("filename", file.name);
        });

        this.on('success', function (file) {
            dz.removeFile(file); 

            var response = JSON.parse(file.xhr.response);
            uploaded_files.push(response.filename);

            if (num_of_files === 0) {
                $("#no_files").addClass("invisible");
                $("#file_browser").removeClass("invisible").addClass("fade-in");
            }

            num_of_files++;

            var tbody = $("#tbody");
            if (num_of_files === 1) 
            {
                files_appended = true;
                tbody.append("<tr class='fade-in' id='appended_files'><td colspan='3'><b>Appended files</b></td>");
            }

            //append file to the table
            tbody.append(`<tr class='fade-in'><td><a class='preview' data-type="${response.mime}" data-url="${response.file_url}">${response.filename}</a></td><td></td>
      <td>${response.mime}</td> <td><a class="button is-small is-danger delete_media" data-tooltip="Delete this media" data-filename="${response.filename}">
      <span class="icon">
        <i class="fas fa-trash"></i>
      </span>
    </a></td></tr>`);
        });

        this.on('canceled', function (file) {
            console.log(`%c${file.name} - upload has been canceled.`, 'color: red;');
        });

    },
    //when the entire file is appended
    chunksUploaded: function (xhr, done, file) {
        done();
        console.log(`The file ${xhr.name} has been uploaded.`);

        $("#footer").attr("style", `top: ${$(document).height()}px;`)
    }
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
            post_visibility: $("#publish_checkbox").is(":checked"),
            post_date: $("#publish_date").val(),
            post_category: $("#post_category").val(),
            tags: $("#tags").val()
        },
        success: function (response) {
            //window.location.replace("/control/posts");
            window.history.back();
        }
    });
});
