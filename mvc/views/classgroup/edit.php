<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-object-group"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("classgroup/index")?>"><?=$this->lang->line('menu_classgroup')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_classgroup')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <?php
                    if(form_error('group'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="group" class="col-sm-2 control-label">
                            <?=$this->lang->line("classgroup_group")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="group" name="group" value="<?=set_value('group', $classgroup->group)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('group'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('photo'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                    <label for="photo" class="col-sm-2 control-label">
                        <?=$this->lang->line("classgroup_photo")?>
                    </label>
                    <div class="col-sm-4">
                        <div class="input-group image-preview">
                            <input type="text" class="form-control image-preview-filename" disabled="disabled">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                    <span class="fa fa-remove"></span>
                                    <?=$this->lang->line('classgroup_file_clear')?>
                                </button>
                                <div class="btn btn-success image-preview-input">
                                    <span class="fa fa-repeat"></span>
                                    <span class="image-preview-input-title">
                                    <?=$this->lang->line('classgroup_file_browse')?></span>
                                    <input type="file" accept="image/png, image/jpeg, image/gif" name="photo"/>
                                </div>
                            </span>
                        </div>
                    </div>
                 </div>

                 <div class='form-group' >
                    <div class="col-sm-2">
                    <label for="course1" class="switch-label">Published</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="switch-wrapper">
                                    <div class="onoffswitch-small">
                                        <input type="checkbox" name="published" class="onoffswitch-small-checkbox" id="course1" value="1" <?php
                                       echo  $classgroup->published == 1?'checked':'' ?> >
                                        <label class="onoffswitch-small-label" for="course1">
                                        <span class="onoffswitch-small-inner"></span>
                                        <span class="onoffswitch-small-switch"></span>
                                        </label>
                                    </div>
                        </div>
                    </div>
                </div>

                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_classgroup")?>" >
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">


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

