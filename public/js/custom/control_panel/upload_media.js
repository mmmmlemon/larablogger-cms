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
            filename: response.filename,
            uuid: file.upload.uuid
        });
        done();
        console.log(`%cFile ${file.name} has been uploaded`, 'color:green;');
    }
});