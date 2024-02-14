<style>
    input[type="file"] {
        display: block;
    }

    .imageThumb {
        max-height: 75px;
        border: 2px solid;
        padding: 1px;
        cursor: pointer;
    }

    .pip {
        display: inline-block;
        margin: 10px 10px 0 0;
    }

    .remove {
        display: block;
        background: #444;
        border: 1px solid black;
        color: white;
        text-align: center;
        cursor: pointer;
    }

    .remove:hover {
        background: white;
        color: black;
    }
</style>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-holiday"></i> <?= $this->lang->line('panel_title') ?></h3>


        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li><a href="<?= base_url("holiday/index") ?>"><?= $this->lang->line('menu_holiday') ?></a></li>
            <li class="active"><?= $this->lang->line('menu_edit') ?> <?= $this->lang->line('menu_holiday') ?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">

                    <?php
                    if (form_error('title'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                    <label for="title" class="col-sm-2 control-label">
                        <?= $this->lang->line("holiday_title") ?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="title" name="title" value="<?= set_value('title', $holiday->title) ?>">
                    </div>
                    <span class="col-sm-4 control-label">
                        <?php echo form_error('title'); ?>
                    </span>
            </div>

            <?php
            if (form_error('fdate'))
                echo "<div class='form-group has-error' >";
            else
                echo "<div class='form-group' >";
            ?>
            <label for="fdate" class="col-sm-2 control-label">
                <?= $this->lang->line("holiday_fdate") ?> <span class="text-red">*</span>
            </label>
            <div class="col-sm-6">
                <input type="text" autocomplete="off" class="form-control" id="fdate" name="fdate" value="<?= set_value('fdate', date("d-m-Y", strtotime($holiday->fdate))) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('fdate'); ?>
            </span>
        </div>

        <?php
        if (form_error('tdate'))
            echo "<div class='form-group has-error' >";
        else
            echo "<div class='form-group' >";
        ?>
        <label for="tdate" class="col-sm-2 control-label">
            <?= $this->lang->line("holiday_tdate") ?> <span class="text-red">*</span>
        </label>
        <div class="col-sm-6">
            <input type="text" autocomplete="off" class="form-control" id="tdate" name="tdate" value="<?= set_value('tdate', date("d-m-Y", strtotime($holiday->tdate))) ?>">
        </div>
        <span class="col-sm-4 control-label">
            <?php echo form_error('tdate'); ?>
        </span>
    </div>

    <?php
    if (form_error('published_date'))
        echo "<div class='form-group has-error' >";
    else
        echo "<div class='form-group' >";
    ?>
    <label for="tdate" class="col-sm-2 control-label">
        <?= $this->lang->line("holiday_published_date") ?> <span class="text-red">*</span>
    </label>
    <div class="col-sm-6">
        <input type="text" autocomplete="off" class="form-control" id="published_date" name="published_date" value="<?= set_value('published_date', date("d-m-Y", strtotime($holiday->published_date))) ?>">
    </div>
    <span class="col-sm-4 control-label">
        <?php echo form_error('published_date'); ?>
    </span>
</div>

<?php
if (form_error('holiday_details'))
    echo "<div class='form-group has-error' >";
else
    echo "<div class='form-group' >";
?>
<label for="holiday_details" class="col-sm-2 control-label">
    <?= $this->lang->line("holiday_details") ?> <span class="text-red">*</span>
</label>
<div class="col-sm-8">
    <textarea class="form-control" id="holiday_details" name="holiday_details"><?= set_value('holiday_details', $holiday->details) ?></textarea>
</div>
<span class="col-sm-offset-2 col-sm-3 control-label">
    <?php echo form_error('holiday_details'); ?>
</span>
</div>


<?php
if (form_error('enable_comment'))
    echo "<div class='form-group has-error' >";
else
    echo "<div class='form-group' >";
?>
<label for="notice" class="col-sm-2 control-label">
    <?= $this->lang->line("holiday_enable_comment") ?>
</label>
<div class="col-sm-2">
    <?php
    $check = $holiday->enable_comment == 1 ? 'checked' : ''; ?>
    <input type="checkbox" class="" id="enable_comment" <?php echo $check; ?> name="enable_comment" value="1" />
</div>
<span class="col-sm-2 control-label">
    <?php echo form_error('enable_comment'); ?>
</span>
</div>

<?php
if (form_error('photos[]'))
    echo "<div class='form-group has-error' >";
else
    echo "<div class='form-group' >";
?>

<span class="col-sm-8">
    <?php echo form_error('photos[]'); ?>
</span>
</div>

<div class="dvFile" id="dvFile">
    <div class="increment-block mb-0 row" id="director-uploads" data-number="0" data-changed="0">
        <div class="col-lg-2 align-self-lg-center">
            <button type="button" class="btn btn-success" id="add-director"><i class='fa fa-plus'></i> </button>
        </div>

    </div>

</div>
<br>

<div class="box-footer clearfix">
    <div id="dvPreview">
        <?php foreach ($holiday_media as $media) : ?>

            <span class="pip pip-<?= $media->id; ?>">
                <img class="imageThumb" src="<?= base_url('uploads/holiday/' . $media->attachment); ?>" title="undefined"><br><span class="remove" onclick="deleteImage(<?= $media->id; ?>)">Remove image</span></span>
        <?php endforeach; ?>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
        <input type="submit" class="btn btn-success" value="<?= $this->lang->line("update_class") ?>">
    </div>
</div>

</form>

</div>
</div>
</div>
</div>

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

    function deleteImage(id) {
                var result = confirm("Are you sure to delete?");
                if (result) {
                    $.post("<?php echo base_url('holiday/deleteImage'); ?>", {
                        id: id
                    }, function(data) {
                        var response = jQuery.parseJSON(data);
                        if (response.status) {
                            console.log(response);
                            $('.pip-' + id).remove();
                            toastr["success"](response.message)
                        }
                    });
                }
            }
</script>

<script type="text/javascript">
    $('#fdate').datepicker({
        startDate: '<?= $schoolyearsessionobj->startingdate ?>',
        endDate: '<?= $schoolyearsessionobj->endingdate ?>',
    });

    $('#tdate').datepicker({
        startDate: '<?= $schoolyearsessionobj->startingdate ?>',
        endDate: '<?= $schoolyearsessionobj->endingdate ?>',
    });

    $('#published_date').datepicker({
        startDate: '<?= $schoolyearsessionobj->startingdate ?>',
        endDate: '<?= $schoolyearsessionobj->endingdate ?>',
    });
    
    tinymce.init({
        selector: '#holiday_details',
        width: 600,
        height: 300,
        plugins: [
        'advlist autolink link image lists charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
        'table emoticons template powerpaste help tiny_mce_wiris '
        ],
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | link image | print preview media fullpage | ' +
        'forecolor backcolor emoticons | help tiny_mce_wiris_formulaEditor | tiny_mce_wiris_formulaEditorChemistry',
        powerpaste_allow_local_images: true,
        powerpaste_word_import: 'prompt',
        powerpaste_html_import: 'prompt',
        menu: {
        favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons'}
        },
        automatic_uploads: true,
        relative_urls: false,
        remove_script_host: false,
          /*
            URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
            images_upload_url: 'postAcceptor.php',
            here we add custom filepicker only to Image dialog
          */
          file_picker_types: 'image',
          /* and here's our custom image picker*/
          file_picker_callback: function (cb, value, meta) {
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

            input.onchange = function () {
              var file = this.files[0];

              var reader = new FileReader();
              reader.onload = function () {
                /*
                  Note: Now we need to register the blob in TinyMCEs image blob
                  registry. In the next release this part hopefully won't be
                  necessary, as we are looking to handle it internally.
                */
                var id = 'blobid' + (new Date()).getTime();
                var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(',')[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                /* call the callback and populate the Title field with the file name */
                cb(blobInfo.blobUri(), { title: file.name });
              };
              reader.readAsDataURL(file);
            };

            input.click();
          },
        menubar: 'favs file edit view insert format tools table help',
        content_css: 'css/content.css'
    });

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
            $(".image-preview-input-title").text("<?= $this->lang->line('holiday_file_browse') ?>");
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
                $(".image-preview-input-title").text("<?= $this->lang->line('holiday_file_browse') ?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
                img.attr('src', e.target.result);
                $(".image-preview").attr("data-content", $(img)[0].outerHTML).popover("show");
                $('.content').css('padding-bottom', '100px');
            }
            reader.readAsDataURL(file);
        });
    });
</script>
</script>