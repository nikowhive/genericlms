<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-subject"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("subject/index")?>"><?=$this->lang->line('menu_subject')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_subject')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" id="add_subject">
                    <?php
                        if(form_error('classesID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="classesID" class="col-sm-2 control-label">
                            <?=$this->lang->line("subject_class_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                        <?php
                                $classArray = [ 0 => $this->lang->line("subject_select_class")];
                                foreach ($classes as $classa) {
                                    $classArray[$classa->classesID] = $classa->classes;
                                }
                                echo form_dropdown("classesID", $classArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('teacherID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="teacherID" class="col-sm-2 control-label">
                            <?=$this->lang->line("subject_teacher_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array = [];
                                if(customCompute($teachers)) {
                                    foreach ($teachers as $teacher) {
                                        $array[$teacher->teacherID] = $teacher->name;
                                    }
                                }
                                echo form_multiselect("teacherID[]", $array, set_value("teacherID", $teachersID), "id='teacherID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('teacherID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('type'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="type" class="col-sm-2 control-label">
                            <?=$this->lang->line("subject_type")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $arraytype['select'] = $this->lang->line("subject_select_type");
                                $arraytype[0] = $this->lang->line("subject_optional");
                                $arraytype[1] = $this->lang->line("subject_mandatory");
                                echo form_dropdown("type", $arraytype, set_value("type"), "id='type' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('type'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('passmark'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="passmark" class="col-sm-2 control-label">
                            <?=$this->lang->line("subject_passmark")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="passmark" name="passmark" value="<?=set_value('passmark')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('passmark'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('finalmark'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="finalmark" class="col-sm-2 control-label">
                            <?=$this->lang->line("subject_finalmark")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="finalmark" name="finalmark" value="<?=set_value('finalmark')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('finalmark'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('credit'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="credit" class="col-sm-2 control-label">
                            <?=$this->lang->line("subject_credit")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="credit" name="credit" value="<?=set_value('credit')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('credit'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('subject'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subject" class="col-sm-2 control-label">
                            <?=$this->lang->line("subject_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="subject" name="subject" value="<?=set_value('subject')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subject'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('subject_author'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subject_author" class="col-sm-2 control-label">
                            <?=$this->lang->line("subject_author")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="subject_author" name="subject_author" value="<?=set_value('subject_author')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subject_author'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('subject_code'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subject_code" class="col-sm-2 control-label">
                            <?=$this->lang->line("subject_code")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="subject_code" name="subject_code" value="<?=set_value('subject_code')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subject_code'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('prerequisites'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="prerequisites" class="col-sm-2 control-label">
                            <?=$this->lang->line("prerequisites")?> 
                        </label>
                        <div class="col-sm-6">
                            <textarea type="text" class="form-control" id="prerequisites" name="prerequisites"><?=set_value('prerequisites')?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('prerequisites'); ?>
                        </span>
                    </div>



                    <?php
                    if(form_error('photo'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                    <label for="photo" class="col-sm-2 control-label">
                        <?=$this->lang->line("subject_photo")?>
                    </label>
                    <div class="col-sm-6">
                        <div class="input-group image-preview">
                            <input type="text" class="form-control image-preview-filename" disabled="disabled">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                    <span class="fa fa-remove"></span>
                                    <?=$this->lang->line('subject_file_clear')?>
                                </button>
                                <div class="btn btn-success image-preview-input">
                                    <span class="fa fa-repeat"></span>
                                    <span class="image-preview-input-title">
                                    <?=$this->lang->line('subject_file_browse')?></span>
                                    <input type="file" accept="image/png, image/jpeg, image/gif" name="photo"/>
                                </div>
                            </span>
                        </div>
                    </div>

                    <span class="col-sm-4">
                        <?php echo form_error('photo'); ?>
                    </span>
                    </div>
                    <div class='form-group'>
                        <label for="add-course" class="col-sm-2 control-label">
                            <?=$this->lang->line("subject_add_course")?>
                        </label>
                        <div class="col-sm-6">
                            <input id="add-course" type="checkbox" name="add_course"
                                   data-toggle="toggle" data-on="Yes" data-off="No">
                        </div>
                    </div>

                    <div class='form-group'>
                        <label for="coscholatics" class="col-sm-2 control-label">
                            Coscholatics
                        </label>
                        <div class="col-sm-6">
                            <input id="coscholatics" type="checkbox" name="coscholatics"
                                   data-toggle="toggle" data-on="Yes" data-off="No">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_subject")?>" >
                        </div>
                    </div>

                </form>
                <?php if ($siteinfos->note==1) { ?>
                    <div class="callout callout-danger">
                        <p><b>Note:</b> Create a teacher & class before create a new subject.</p>
                    </div>
                <?php } ?>
            </div> <!-- col-sm-8 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">


tinymce.init({
        selector: '#prerequisites',
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


$('#add_subject').submit(function(){
    $("#add_subject :disabled").removeAttr('disabled');
});


$('.select2').select2();
$(document).on('click', '#close-preview', function(){
    $('.image-preview').popover('hide');
    // Hover befor close the preview
    $('.image-preview').hover(
        function () {
            $('.image-preview').popover('show');
            $('.content').css('padding-bottom', '100px');
        },
        function () {
            $('.image-preview').popover('hide');
            $('.content').css('padding-bottom', '20px');
        }
    );
});

$(function() {
    // Create the close button
    var closebtn = $('<button/>', {
        type:"button",
        text: 'x',
        id: 'close-preview',
        style: 'font-size: initial;',
    });
    closebtn.attr("class","close pull-right");
    // Set the popover default content
    $('.image-preview').popover({
        trigger:'manual',
        html:true,
        title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
        content: "There's no image",
        placement:'bottom'
    });
    // Clear event
    $('.image-preview-clear').click(function(){
        $('.image-preview').attr("data-content","").popover('hide');
        $('.image-preview-filename').val("");
        $('.image-preview-clear').hide();
        $('.image-preview-input input:file').val("");
        $(".image-preview-input-title").text("<?=$this->lang->line('subject_file_browse')?>");
    });
    // Create the preview image
    $(".image-preview-input input:file").change(function (){
        var img = $('<img/>', {
            id: 'dynamic',
            width:250,
            height:200,
            overflow:'hidden'
        });
        var file = this.files[0];
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            $(".image-preview-input-title").text("<?=$this->lang->line('subject_file_browse')?>");
            $(".image-preview-clear").show();
            $(".image-preview-filename").val(file.name);
            img.attr('src', e.target.result);
            $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
            $('.content').css('padding-bottom', '100px');
        }
        reader.readAsDataURL(file);
    });
});
</script>
