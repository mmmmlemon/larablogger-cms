//turn off autodiscover for Dropzone
Dropzone.autoDiscover = false;

//list of uploaded files
var uploaded_files = [];

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

        //when every file is uploaded, the form will be sent
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
            num: uploaded_files.length,
            display_filename: response.filename,
            actual_filename: response.filename,
            post_id: null,
            uuid: file.upload.uuid,
            appended_to_list: false
        });

        for(let file of uploaded_files)
        {   
            if($("#no_files_yet").length > 0)
            {
                $("#no_files_yet").remove();
            }
            if(file.appended_to_list === false)
            {
                $("#uploaded_list").append(`<div id="file_${file.num}" class="fade-in uploaded_file_div"><b>${file.actual_filename}</b><br>
                <div class="columns">
                    <div class="column is-4">
                        <label>Display name</label>
                        <input class="input is-link display_name_input" type="text" value="${file.display_filename}" data-num="${file.num}">
                    </div>
                    <div class="column">
                        <label>Attach to post: None</label>
                        <input class="input is-link" type="text" value="" placeholder="Type in the post name...">
                    </div>
                </div></div><hr>`);
                file.appended_to_list = true;
            }
   
        }

        done();
        console.log(`%cFile ${file.name} has been uploaded`, 'color:green;');
    }
});

//on display name change
$(document).on('keyup', ".display_name_input", function(){
    uploaded_files[$(this).data("num")].display_filename = $(this).val();
});


//submit post
$("#submit_files").click(function () {
    $(this).attr("disabled", "disabled");

    //csrf-token for ajax request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //ajax-request
    $.ajax({
        type: 'POST',
        url: `/control/save_uploaded_media`,
        data: {
            file_list: JSON.stringify(uploaded_files)
        },
        //redirect to posts on success
        success: function (response) {
            window.location.replace("/control/media");
            console.log(response)
        }
    });
});