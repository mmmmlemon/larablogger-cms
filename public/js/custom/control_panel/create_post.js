//scripts for create_post

//turn off autodiscover for Dropzone
Dropzone.autoDiscover = false;

var canceled = false;

//list of uploaded files
var uploaded_files = [];

//ajax-request, clear temp folder
var clear_temp = function () {
    $.ajax('/clear_temp', {
        success: function (data, status, xhr) {
            console.log("%cTemp directory has been p u r g e d", "color: red;");
        }
    });
}

//initializing dropzone with options
var dropzone = $("#file_form").dropzone({
    autoProcessQueue: true, 
    chunking: true, 
    chunkSize: 20000000, //20 Mb
    retryChunks: false,
    addRemoveLinks: true,
    paramName: 'file',
    forceChunking: true,
    maxFiles: 20,
    maxFilesize: 4000, // 4 Gb
    parallelUploads: 20,
    acceptedFiles: '.jpg,.jpeg,.png,.mp4',

    //attach events to dropzone
    init: function () {
        clear_temp();

        var dropzone = this;
        //attach filename to request
        this.on('sending', function (file, xhr, data) {
            file.name = file.name + " pee and also poo";
            console.log(`%cSending file ${file.name}`, 'color:grey;');

            data.append("filename", file.name);
        });

        //on cancel, remove all files from dropzone and clear temp folder
        $("#cancel").click(function () {
            canceled = true;
            console.log(dropzone.removeAllFiles(true));
            clear_temp();
        });

        //when evert file is uploaded, the form will be sent
        this.on("complete", function (file) {
            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                if (canceled === false) //what the frick is going on down here???
                {
                    //$("#post_form").submit();
                } else 
                {
                    console.info("Uploads were canceled by user.");
                    canceled = false;
                }
            }
        });

        this.on("removedfile", function (file) {
            var index_of_el = uploaded_files.findIndex(x => x.uuid === file.upload.uuid);
            uploaded_files.splice(index_of_el, 1);
            console.log(uploaded_files);
        });

    },
    //when file or all chunks of file are uploaded
    chunksUploaded: function (file, done) {
        var response = JSON.parse(file.xhr.response);
        uploaded_files.push({
            filename: response.filename,
            uuid: file.upload.uuid
        });
        done();
        console.log(`%cFile ${file.name} has been uploaded`, 'color:green;');
    }
});

//submit post
$("#submit_post").click(function () {
    $(this).attr("disabled", "disabled");

    if($("#post_title").val() === "")
    {$("#post_title").focus();} 
    
    //csrf-token for ajax request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //ajax-request
    $.ajax({
        type: 'POST',
        url: `/control/create_new_post`,
        data: {
            file_list: JSON.stringify(uploaded_files),
            post_title: $("#post_title").val(),
            post_content: $(".textarea").val(),
            post_visibility: $("#publish_checkbox").is(":checked"),
            post_date: $("#publish_date").val(),
            post_category: $("#post_category").val(),
            tags: $("#tags").val()
        },
        //redirect to posts on success
        success: function (response) {
            window.location.replace("/control/posts");
        }
    });
});

//richText
//text editor
$('#post_content').richText({
    imageUpload: false,
    videoEmbed: false,
    fileUpload: false
});

//tagEditor
//bulma tag editor
BulmaTagsInput.attach('input[data-type="tags"], input[type="tags"], select[data-type="tags"], select[type="tags"]', {
    tagClass: 'is-rounded is-grey'
});

//character counter
$('#post_title').charCounter();
