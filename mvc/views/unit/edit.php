
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-subject"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <?php if(!isset($course)) { ?>
                <li><a href="<?=base_url("unit/index")?>"><?=$this->lang->line('menu_unit')?></a></li>
            <?php } else { ?>
                <li><a href="<?=base_url("courses/show/".$course->id)?>">Course</a></li>
            <?php } ?>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_unit')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post" id="edit_unit">
                    <?php
                        if(form_error('classesID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="classesID" class="col-sm-2 control-label">
                            <?=$this->lang->line("unit_class_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                           <?php
                                $array = array();
                                $array[0] = $this->lang->line("unit_select_classes");
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
                            <?=$this->lang->line("unit_subject_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6" id="ajax-get-subjects">
                           <?php
                                $array = array();
                                $array[0] = $this->lang->line("unit_select_subject");
                                foreach ($subjects as $subject) {
                                    $array[$subject->subjectID] = $subject->subject;
                                }

                                $disabled = isset($course) ? 'disabled': '';

                                echo form_dropdown("subject_id", $array, $unit->subject_id, "id='subject_id' class='form-control select2' $disabled");
                            ?>
                            
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subject_id'); ?>
                        </span>
                    </div>

                    
                    <?php
                        if(form_error('unit_name'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="unit_name" class="col-sm-2 control-label">
                            <?=$this->lang->line("unit_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="unit_name" name="unit_name" value="<?=$unit->unit_name?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('unit_name'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('unit_code'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="unit_code" class="col-sm-2 control-label">
                            <?=$this->lang->line("unit_code")?> 
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="unit_code" name="unit_code" value="<?=set_value('unit_code',$unit->unit_code)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('unit_code'); ?>
                        </span>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_unit")?>" >
                        </div>
                    </div>

                </form>
                <input type="hidden" id="ajax-get-subjects-url" value="<?php echo base_url() ?>subject/ajaxGetSubjectsFromClassId">

                <?php if ($siteinfos->note==1) { ?>
                    <div class="callout callout-danger">
                        <p><b>Note:</b> Create a class and a subject before adding unit.</p>
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

$('#edit_unit').submit(function(){
    $("#edit_unit :disabled").removeAttr('disabled');
});

</script>
