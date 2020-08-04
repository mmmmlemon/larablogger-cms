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
