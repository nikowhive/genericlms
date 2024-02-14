<div class="right-side--fullHeight  ">

    <div class="row w-100 ">

        <div class="<?php echo isset($course) ? 'course-content' : 'col-md-12' ?>">
            <div class="container container--sm">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa icon-assignment"></i> <?= $this->lang->line('panel_title') ?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <div class="row">
                            <?php $usertypeID = $this->session->userdata('usertypeID'); ?>
                            <?php if ($usertypeID == 3) { ?>
                                <div class="col-sm-12">
                                    <form  id="answerForm" class="form-horizontal <?php if (form_error('content')) {
                                                                        echo 'has-error';
                                                                    } ?>" enctype="multipart/form-data" role="form" method="POST">
                                        <?php if (form_error('content')) {
                                            echo '<div class="row">';
                                            echo '<div class="col-sm-12 control-label">';
                                            echo 'Please fill any Field';
                                            echo '</div>';
                                            echo '</div>';
                                        } ?>

                                        <!-- <div class="form-group" >
                                            <label for="file" class="col-sm-2 control-label">
                                                <?= $this->lang->line("assignment_file") ?>
                                            </label>
                                            <div class="col-sm-6">
                                                <div class="input-group image-preview">
                                                    <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                                    
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                                            <span class="fa fa-remove"></span>
                                                            <?= $this->lang->line('assignment_clear') ?>
                                                        </button>
                                                        <div class="btn btn-success image-preview-input">
                                                            <span class="fa fa-repeat"></span>
                                                            <span class="image-preview-input-title">
                                                            <?= $this->lang->line('assignment_file_browse') ?></span>
                                                            <input type="file" accept="image/png, image/jpeg, image/gif, application/pdf, application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf" name="file"/>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>

                                        </div> -->
                                        <?php
                                        if (form_error('photos[]'))
                                            echo "<div class='form-group has-error' >";
                                        else
                                            echo "<div class='form-group' >";
                                        ?>
                                        <span class="text-danger error">
                                            <?php echo form_error('photos[]'); ?>
                                        </span>
                                </div>
                                <div class="dvFile" id="dvFile">
                                    <div class="increment-block mb-0 row" id="director-uploads" data-number="0" data-changed="0">
                                        <div class="col-sm-4">
                                            <div class="md-form md-form--file">
                                                <div class="file-field">
                                                    <div class="btn btn-success btn-sm float-left">
                                                        <span>Choose file</span>
                                                        <input type="file" name="photos[]" />
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate form-control" type="text" name="attachment1[]" placeholder="Upload your file" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3 ">
                                            <div class="md-form">
                                                <input type="text" name="caption[]" class="form-control" data-item="0" id="file-name0" placeholder="Caption">
                                            </div>
                                        </div>


                                        <div class="col-lg-2 align-self-lg-center">
                                            <button type="button" class="btn btn-success" id="add-director"><i class='fa fa-plus'></i> </button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <?php if (customCompute($assignment_answer_medias)) : ?>
                                    <div class="content">
                                        <div class="row">
                                            <?php
                                            foreach ($assignment_answer_medias as $v) : ?>
                                                <div class="col-sm-4" id="pip-<?= $v->id; ?>"  style="margin-bottom: 1em">
                                                    <div class="card text-center">
                                                        <div class="card-body">
                                                            <div class="">
                                                                <div class="panned-icon">
                                                                    <i class='fa <?= checkFileExtension($v->attachment) ?>' aria-hidden='true'></i>
                                                                </div>
                                                                <p class="card-text"><small><?= substr($v->caption, 0, 20) ?>.<?= pathinfo($v->attachment, PATHINFO_EXTENSION); ?></small></p>
                                                            </div>
                                                            <?php
                                                            $fileType = checkFileExtension($v->attachment);

                                                            if ($fileType == 'fa-picture-o') { ?>
                                                                <a class="btn btn-danger myImg1" data-link="<?php echo base_url('uploads/images/') . $v->attachment; ?>" href="javascript:void(0)">View</a>
                                                            <?php } elseif ($fileType == 'fa-file-pdf-o') { ?>
                                                                <a class="btn btn-danger" target="_blank" href="<?php echo base_url('uploads/images/') . $v->attachment; ?>">Preview</a>
                                                            <?php } else {
                                                                echo btn_download('assignment/assignmentdownloadFiles/' . $v->id, $this->lang->line('download'));
                                                            } ?>
                                                            <!-- <a class="btn btn-danger" onclick="deleteImage(<?= $v->id; ?>)">Remove</a> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <br>
                                <?php endif; ?>

                                <div class="form-group">
                                    <label for="content" class="col-sm-2 control-label">
                                        <?= $this->lang->line("assignment_content") ?>
                                    </label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <textarea name="content" class="md-textarea form-control" rows="4" id="content"><?= set_value('content', $content) ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-8">
                                        <input type="submit" class="btn btn-success submitBtn" value="<?= $this->lang->line("add_assignment_ans") ?>" name="submit">
                                    </div>
                                </div>
                                </form>
                        </div>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>

        $(document).ready(function () {
            $("#answerForm").submit(function () {
                $(".submitBtn").attr("disabled", true);
                return true;
            });
        });

        // Get the modal
        var modal = document.getElementById("feedModal");

        var img = $('.myImg1');
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");

        img.click(function(){
            var link = $(this).data('link');
            modal.style.display = "block";
            modalImg.src = link;
        //   captionText.innerHTML = this.alt;
        });

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("closeBtn")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() { 
        modal.style.display = "none";
        }
    </script> 

<script language="javascript">
    

    $(document).ready(function() {

        var i = 1;

        function clone() {
            var html = "<div class=\"increment-block mb-0 row\" data-changed=\"0\"><div class=\"col-sm-4\"><div class=\"md-form md-form--file\"><div class=\"file-field\"><div class=\"btn btn-success btn-sm float-left\"><span>Choose file</span><input type=\"file\" name=\"photos[]\"  /></div><div class=\"file-path-wrapper\"><input class=\"file-path validate form-control\" type=\"text\" name=\"attachment1[]\" placeholder=\"Upload your file\" readonly/></div></div></div></div><div class=\"col-sm-3\"><div class=\"md-form\"><input type=\"text\" name=\"caption[]\" id=\"file-name" + i + "\" class=\"form-control\" data-item=\"0\"  placeholder=\"Caption\" > <span class=\"text-danger error\"><p id=\"file-name-error" + i + "\"></p></span></div></div><div class=\"col-lg-2 align-self-lg-center\"><button type=\"button\" class=\"btn btn-danger\" onclick=\"remove_input(this)\" title=\"Remove\" id=\"remove-clone\"><i class='fa fa-minus'></i></button></div></div>";
            i++;
            $('#dvFile').append(html).find("*").each(function() {
                var name = $(this).attr('name');

            }).on('click', 'button.clone', clone);
        }

        $("button#add-director").on("click", clone);

        $("html").on('change', '.app-file', function() {
            var number = $(this).attr('data-item');
        });

    });

    function remove_input(e) {
        var thisId = e.closest('.increment-block').remove();
        console.log(thisId);
    }
</script>

<script>
    $(document).on('click', '#close-preview', function() {
        $('.image-preview').popover('hide');
        // Hover befor close the preview
        $('.image-preview').hover(
            function() {
                $('.image-preview').popover('show');
                $('.content').css('padding-bottom', '100px');
            },
            function() {
                $('.image-preview').popover('hide');
                $('.content').css('padding-bottom', '20px');
            }
        );
    });

    function deleteImage(id) {
        var result = confirm("Are you sure to delete?");
        if (result) {
            $.post("<?php echo base_url('assignment/deleteAnswerImage'); ?>", {
                id: id
            }, function(data) {
                var response = jQuery.parseJSON(data);
                if (response.status) {

                    console.log(response);
                    $('#pip-' + id).remove();
                    toastr["success"](response.message)
                }
            });
        }
    }

    $(function() {
        // Create the close button
        var closebtn = $('<button/>', {
            type: "button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class", "close pull-right");
        // Set the popover default content
        $('.image-preview').popover({
            trigger: 'manual',
            html: true,
            title: "<strong>Preview</strong>" + $(closebtn)[0].outerHTML,
            content: "There's no image",
            placement: 'bottom'
        });
        // Clear event
        $('.image-preview-clear').click(function() {
            $('.image-preview').attr("data-content", "").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("<?= $this->lang->line('assignment_file_browse') ?>");
        });
        // Create the preview image
        $(".image-preview-input input:file").change(function() {
            var img = $('<img/>', {
                id: 'dynamic',
                width: 250,
                height: 200,
                overflow: 'hidden'
            });
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function(e) {
                $(".image-preview-input-title").text("<?= $this->lang->line('assignment_file_browse') ?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
            }
            reader.readAsDataURL(file);
        });
    });

    tinymce.init({
        selector: '#content',
        width: 600,
        height: 300,
        plugins: [
            'advlist autolink link image lists charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
            'table emoticons template powerpaste help tiny_mce_wiris'
        ],
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | link image | print preview media fullpage | ' +
            'forecolor backcolor emoticons | help tiny_mce_wiris_formulaEditor | tiny_mce_wiris_formulaEditorChemistry',
        powerpaste_allow_local_images: true,
        powerpaste_word_import: 'prompt',
        powerpaste_html_import: 'prompt',
        menu: {
            favs: {
                title: 'My Favorites',
                items: 'code visualaid | searchreplace | emoticons'
            }
        },
        automatic_uploads: true,
        relative_urls: false,
        remove_script_host: false,
        /*
        URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
        images_upload_url: 'postAcceptor.php',
        here we add custom filepicker only to Image dialog
        */
        images_upload_url: '<?= base_url('courses/Uploadimages') ?>',
        file_picker_types: 'image',
        /* and here's our custom image picker*/
        file_picker_callback: function(cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            /*
              Note: In modern browsers input[type="file"] is functional without
              even adding it to the DOM, but that might not be the case in some older
              or quirky browsers like IE, so you might want to add it to the DOM
              just in case, and visually hide it. And do not forget do remove it
              once you do not need it anymore.
            */

            input.onchange = function() {
                var file = this.files[0];

                var reader = new FileReader();
                reader.onload = function() {
                    /*
                      Note: Now we need to register the blob in TinyMCEs image blob
                      registry. In the next release this part hopefully won't be
                      necessary, as we are looking to handle it internally.
                    */
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    /* call the callback and populate the Title field with the file name */
                    cb(blobInfo.blobUri(), {
                        title: file.name
                    });
                };
                reader.readAsDataURL(file);
            };

            input.click();
        },
        menubar: 'favs file edit view insert format tools table help',
        content_css: 'css/content.css'
    });
</script>