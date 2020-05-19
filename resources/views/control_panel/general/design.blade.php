<div id="design_content" class="invisible">

    <div class="has-text-centered">
        <span class="icon">
            <i class="fas fa-paint-brush"></i>
        </span>
        <h3 class="subtitle">Design and visuals</h3>
    </div>
    <br>
    <div >
        <form action="/control/update_design" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Background image</label>
                </div>
                <div class="field-body">
                    <div id="bg-img" class="file has-name">
                        <label class="file-label">
                          <input class="file-input" type="file" name="background_image">
                          <span class="file-cta">
                            <span class="file-icon">
                              <i class="fas fa-upload"></i>
                            </span>
                            <span class="file-label">
                              Choose a file…
                            </span>
                          </span>
                          <span class="file-name">
                            No file uploaded
                          </span>
                        </label>
                    </div>
                    <div>&nbsp;</div>
                    <div>
                        <input class="is-checkradio is-link" name="blur_img" id="blur_img" type="checkbox" checked>
                        <label class="label" for="blur_img">Blur image</label>
                    </div>
                    
                    <div>
                        <input class="is-checkradio is-link" name="dark_img" id="dark_img" type="checkbox" checked>
                        <label class="label" for="dark_img">Darken image</label>
                    </div>
              

                    </div>
                    
                    </div>
                    <div class="field is-horizontal">
                        <div class="field-label">
                        <!-- Left empty for spacing -->
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <button type="submit" class="button is-link">
                                        <span class="icon">
                                            <i class="fas fa-save"></i>
                                        </span>
                                        <span>
                                            Save
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
    </div>

</div>



@push('script')
<script>
    // const fileInput = document.querySelector('#file-js-example input[type=file]');
    // fileInput.onchange = () => {
    //     if (fileInput.files.length > 0) 
    //         {
    //             const fileName = document.querySelector('#file-js-example .file-name');
    //             fileName.textContent = fileInput.files[0].name;
    //         }
    //     }
</script>
@endpush