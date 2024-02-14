
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-sitemap"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("classes/index")?>"> <?=$this->lang->line('menu_classes')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_classes')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">

                    <?php 
                        if(form_error('classes')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        
                        <label for="classes" class="col-sm-2 control-label">
                            <?=$this->lang->line("classes_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="classes" name="classes" value="<?=set_value('classes', $classes->classes);?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classes'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('classes_numeric')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="classes_numeric" class="col-sm-2 control-label">
                            <?=$this->lang->line("classes_numeric")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="classes_numeric" name="classes_numeric" value="<?=set_value('classes_numeric', $classes->classes_numeric);?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classes_numeric'); ?>
                        </span>

                    </div>

                    <?php
                        if(form_error('classgroupID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="classgroupID" class="col-sm-2 control-label">
                            <?=$this->lang->line("class_group")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">

                            <?php
                                $array = array();
                                $array[0] = $this->lang->line("classes_select_classgroup");

                                foreach ($classgroups as $classgroup) {
                                    $array[$classgroup->classgroupID] = $classgroup->group;
                                }
                                echo form_dropdown("classgroupID", $array, set_value("classgroupID",$classes->classgroupID), "id='classgroupID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classgroupID'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('teacherID')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="teacherID" class="col-sm-2 control-label">
                            <?=$this->lang->line("teacher_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            
                            <?php
                                $array = array();
                                $array[0] = $this->lang->line("classes_select_teacher");
                                foreach ($teachers as $teacher) {
                                    $array[$teacher->teacherID] = $teacher->name;
                                }
                                echo form_dropdown("teacherID", $array, set_value("teacherID", $classes->teacherID), "id='teacherID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('teacherID'); ?>
                        </span>
                    </div>


                    <?php 
                        if(form_error('note')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("classes_note")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea style="resize:none;" class="form-control" id="note" name="note"><?=set_value('note', $classes->note);?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('note'); ?>
                        </span>
                    </div>

                    <?php
                if(form_error('photo'))
                    echo "<div class='form-group has-error' >";
                else
                    echo "<div class='form-group' >";
                ?>
                <label for="photo" class="col-sm-2 control-label">
                    <?=$this->lang->line("class_photo")?>
                </label>
                <div class="col-sm-6">
                    <div class="input-group image-preview">
                        <input type="text" class="form-control image-preview-filename" disabled="disabled">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                <span class="fa fa-remove"></span>
                                <?=$this->lang->line('class_file_clear')?>
                            </button>
                            <div class="btn btn-success image-preview-input">
                                <span class="fa fa-repeat"></span>
                                <span class="image-preview-input-title">
                                <?=$this->lang->line('class_file_browse')?></span>
                                <input type="file" accept="image/png, image/jpeg, image/gif" name="photo"/>
                            </div>
                        </span>
                    </div>
                </div>
                <span class="col-sm-4 control-label">
                            <?php echo form_error('photo'); ?>
                        </span>
                </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input id="updateclass" type="submit" class="btn btn-success" value="<?=$this->lang->line("update_class")?>" >
                        </div>
                    </div>

                </form>
            </div>    
        </div>
    </div>
</div>


<script>
$( ".select2" ).select2( { placeholder: "", maximumSelectionSize: 6 } );


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

