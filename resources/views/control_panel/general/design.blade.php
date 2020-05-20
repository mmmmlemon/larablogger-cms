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
                          <input class="file-input" type="file" id="background_image" name="background_image">
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
                    <div class="is-divider"></div>
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                           
                        </div>
                        <div class="field-body">
                            <input class="is-checkradio is-link" name="show_about" id="show_about" type="checkbox" checked>
                            <label class="label" for="show_about">Show 'About' page</label>
                            <a href="/control/edit_about" class="button is-link">
                                <span class="icon is-small">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <span>Edit 'About' page</span>
                            </a>
                        </div>
                        
                    </div>
                    <div class="is-divider"></div>

                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Footer contents</label>   
                        </div>
                        <div class="field-body">
                            <textarea class="textarea" name="footer_content" id="footer_content"></textarea>
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



@push('scripts')
<script src="{{ asset('js/jquery.richtext.min.js') }}"></script>
<script>
    const fileInput = document.querySelector('#bg-img input[type=file]');
    fileInput.onchange = () => {
        if (fileInput.files.length > 0) 
            {
                const fileName = document.querySelector('#bg-img .file-name');
                fileName.textContent = fileInput.files[0].name;
            }
        }

    $('#footer_content').richText({
    imageUpload:false,
    videoEmbed:false,
    fileUpload:false
    });
    
</script>
@endpush