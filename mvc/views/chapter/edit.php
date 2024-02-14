
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-subject"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <?php if(!isset($course)) { ?>
                <li><a href="<?=base_url("chapter/index")?>"><?=$this->lang->line('menu_chapter')?></a></li>
            <?php } else { ?>
                <li><a href="<?=base_url("courses/show/".$course->id)?>">Course</a></li>
            <?php } ?>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_chapter')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post" id="edit_chapter">
                    <?php
                        if(form_error('classesID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="classesID" class="col-sm-2 control-label">
                            <?=$this->lang->line("chapter_class_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                           <?php
                                $array = array();
                                $array[0] = $this->lang->line("chapter_select_classes");
                                foreach ($classes as $classa) {
                                    $array[$classa->classesID] = $classa->classes;
                                }

                                $disabled = isset($course) ? 'disabled': '';

                                echo form_dropdown("classesID", $array, $set_class, "id='classesID' class='form-control select2' $disabled");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('subject_id'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subject_id" class="col-sm-2 control-label">
                            <?=$this->lang->line("chapter_subject_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6" id="ajax-get-subjects">
                           <?php
                                $array = array();
                                $array[0] = $this->lang->line("chapter_select_subject");
                                foreach ($subjects as $subject) {
                                    $array[$subject->subjectID] = $subject->subject;
                                }

                                $disabled = isset($course) ? 'disabled': '';

                                echo form_dropdown("subject_id", $array, $chapter->subject_id, "id='subject_id' class='form-control select2' $disabled");
                            ?>
                            
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subject_id'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('unit_id'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="unit_id" class="col-sm-2 control-label">
                            <?=$this->lang->line("chapter_unit")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6"  id="ajax-get-units">
                           <?php
                                $array = array();
                                $array[0] = $this->lang->line("chapter_select_unit");
                                foreach ($units as $unit) {
                                    $array[$unit->id] = $unit->unit_name;
                                }

                                $disabled = isset($course) ? 'disabled': '';

                                echo form_dropdown("unit_id", $array, $chapter->unit_id, "id='unit_id' class='form-control select2' $disabled");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('unit_id'); ?>
                        </span>
                    </div>

                    
                    <?php
                        if(form_error('chapter_name'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="chapter_name" class="col-sm-2 control-label">
                            <?=$this->lang->line("chapter_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="chapter_name" name="chapter_name" value="<?=$chapter->chapter_name?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('chapter_name'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('chapter_code'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="chapter_code" class="col-sm-2 control-label">
                            <?=$this->lang->line("chapter_code")?> 
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="chapter_code" name="chapter_code" value="<?=set_value('chapter_code',$chapter->chapter_code)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('chapter_code'); ?>
                        </span>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_chapter")?>" >
                        </div>
                    </div>

                </form>
                <input type="hidden" id="ajax-get-subjects-url" value="<?php echo base_url() ?>subject/ajaxGetSubjectsFromClassId">
                <input type="hidden" id="ajax-get-units-url" value="<?php echo base_url() ?>unit/ajaxGetUnitsFromSubjectId">

                <?php if ($siteinfos->note==1) { ?>
                    <div class="callout callout-danger">
                        <p><b>Note:</b> Create a class, a subject and a unit before adding chapter.</p>
                    </div>
                <?php } ?>
            </div> <!-- col-sm-8 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">
$('.select2').select2();

$(document).on('change', '#classesID', function() {
    let class_id = $(this).val();
    let url = $('#ajax-get-subjects-url').val()
    
    $.ajax({
      url: url + "?class_id=" + class_id,
    }).done(function( data ) {
        data = JSON.parse(data);
        $('#ajax-get-subjects').html(data.form);
    });
})


$(document).on('change', '#subject_id', function() {
    let subject_id = $(this).val();
    let url = $('#ajax-get-units-url').val()
    
    $.ajax({
      url: url + "?subject_id=" + subject_id,
    }).done(function( data ) {
        $('#ajax-get-units').html(data);
    });
})

$('#edit_chapter').submit(function(){
    $("#edit_chapter :disabled").removeAttr('disabled');
});
</script>
