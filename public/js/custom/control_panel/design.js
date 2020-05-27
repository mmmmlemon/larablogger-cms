const fileInput = document.querySelector('#bg-img input[type=file]');
fileInput.onchange = () => {
    if (fileInput.files.length > 0) {
        const fileName = document.querySelector('#bg-img .file-name');
        fileName.textContent = fileInput.files[0].name;
    }
}

$('#footer_content').richText({
    imageUpload: false,
    videoEmbed: false,
    fileUpload: false
});


$("#submit_design").on('click', function (event) {
    $(".black_screen").removeClass("invisible").addClass("fade-in");
});
