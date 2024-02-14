<div class="right-side--fullHeight  ">
    <div class="row w-100 ">
        <?php $this->load->view("components/course_menu"); ?>
        <div class="course-content">
            <div class="container container--sm">
                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                        <div><small>Course</small></div>
                        <?= $this->lang->line('panel_title') ?>
                    </h1>
                </header>
                <div class="card card--spaced">
                    <!-- form start -->
                    <div class="card-body">

                        <form class="" role="form" method="post">

                            <?php
                            if (form_error('content_title'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                            ?>

                            <div class="md-form">
                                <label for="content_title">
                                    Title
                                </label>
                                <input type="text" class="form-control" id="content_title" name="content_title" value="<?= set_value('content_title', $content->content_title); ?>">
                                <span class="text-danger error">
                                    <?php echo form_error('content_title'); ?>
                                </span>
                            </div>


                    </div>

                    <?php
                    if (form_error('chapter_content'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>

                    <label class=" ">
                        Content
                    </label>

                    <textarea class="form-control" id="chapter_content" name="chapter_content"><?= set_value('chapter_content', $content->chapter_content); ?></textarea>

                    <span class="text-danger error">
                        <?php echo form_error('chapter_content'); ?>
                    </span>

                </div>

                <?php
                if (form_error('percentage_coverage'))
                    echo "<div class='form-group has-error' >";
                else
                    echo "<div class='form-group' >";
                ?>

                <div class="md-form">
                    <label for="percentage_coverage">
                        Percentage coverage
                    </label>
                    <input type="number" style="resize:none;" class="form-control" id="percentage_coverage" name="percentage_coverage" value="<?= set_value('percentage_coverage', $content->percentage_coverage); ?>">

                    <span class="text-danger error">
                        <?php echo form_error('percentage_coverage'); ?>
                    </span>
                </div>

            </div>


            <input id="updateclass" type="submit" class="btn btn-success" value="Update">
            <a href="<?= $this->agent->referrer(); ?>" class="btn btn-default">Cancel</a>


            </form>

        </div>
    </div>
</div>
</div>
</div>
</div>


<script>
    $(".select2").select2({
        placeholder: "",
        maximumSelectionSize: 6
    });
</script>

<script src="https://cdn.ckeditor.com/4.14.1/standard-all/ckeditor.js"></script>
<script>
    $(".select2").select2({
        placeholder: "",
        maximumSelectionSize: 6
    });
    //CKEDITOR.replace('chapter_content');
</script>
<script type="text/javascript">
    tinymce.init({
        selector: '#chapter_content',
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

        menubar: 'favs file edit view insert format tools table help',
        content_css: 'css/content.css'
    });
</script>