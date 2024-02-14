
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-subject"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("exam_setting/index")?>"><?=$this->lang->line('menu_exam_setting')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_exam_setting')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <table style="display:none;">
        <tbody>
            <tr id="intial_setting_details">
                <td>
                <div id="ajax-get-units">
                        <?php 
                        $array = array();
                        $array[""] = "Select Unit";
                        foreach ($units as $index => $unit) {
                            $array[$index] = $unit;
                        }
                        ?>
                        <?php echo form_dropdown("details[unit][]", $array, set_value("unit")," class='form-control' changetorequired "); ?>
                    </div>
                </td>
                <td>
                    <?php echo form_dropdown("details[question_group][]", $question_group, set_value("question_type"), " class='form-control' changetorequired "); ?>
                </td>
                <td>
                    <?php echo form_dropdown("details[level][]", $level, set_value("level"), " class='form-control' changetorequired "); ?>
                </td>
                <td>
                    <?php echo form_dropdown("details[question_type][]", $question_type, set_value("question_type"), " class='form-control' changetorequired "); ?>
                </td>
                <td>
                    <input type="number" name="details[mark][]" step=1 class="form-control" changetorequired>
                </td>
                <td>
                    <input type="number" name="details[no_of_questions][]" step=1 class="form-control" changetorequired>
                </td>
                <td>
                    <a href="#" class="btn btn-info detail-add">Add</a>
                    <a href="#" class="btn btn-danger detail-remove">Remove</a>
                </td>
            </tr>
        </tbody>
        
    </table>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <?php
                        if(form_error('classesID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="classesID" class="col-sm-2 control-label">
                            <?=$this->lang->line("exam_setting_class_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                           <?php
                                $array = array();
                                $array[""] = $this->lang->line("exam_setting_select_classes");
                                foreach ($classes as $classa) {
                                    $array[$classa->classesID] = $classa->classes;
                                }
                                echo form_dropdown("classesID", $array, $set_class, "id='classesID' class='form-control select2' required");
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
                            <?=$this->lang->line("exam_setting_subject_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6" id="ajax-get-subjects">
                           <?php
                                $array = array();
                                $array[""] = $this->lang->line("exam_setting_select_subject");
                                foreach ($subjects as $subject) {
                                    $array[$subject->subjectID] = $subject->subject;
                                }

                                echo form_dropdown("subject_id", $array, $exam_setting->subject_id, "id='subject_id' class='form-control select2' required");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subject_id'); ?>
                        </span>
                    </div>

                    
                    <?php
                        if(form_error('setting_name'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="setting_name" class="col-sm-2 control-label">
                            <?=$this->lang->line("exam_setting_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="setting_name" name="setting_name" value="<?=$exam_setting->setting_name ?>" required>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('setting_name'); ?>
                        </span>
                    </div>
                    <?php $error = $this->session->error; ?>
                    <div class="form-group">
                        <label for="exam_setting_details" class="col-sm-2 control-label">
                            <?=$this->lang->line("exam_setting_details")?> <span class="text-red">*</span>
                        </label>
                        <?php if($error) { ?>
                        <span class="col-sm-4 control-label has-error">
                            <?php echo $error; ?>
                        </span>
                    <?php } ?>
                        <div class="col-sm-10">
                            <a href="#" class="btn btn-info detail-add">Add</a>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="col-sm-2">Unit</th>
                                        <th class="col-sm-3">Question Group</th>
                                        <th class="col-sm-2">Difficulty</th>
                                        <th class="col-sm-2">Type</th>
                                        <th class="col-sm-2">Mark</th>
                                        <th class="col-sm-2">No Of Questions</th>
                                        <th class="col-sm-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="add-setting">
                                    <?php foreach($exam_setting->details as $detail) { ?>
                                        <tr>
                                            <td>
                                                <?php 
                                                    $array = array();
                                                    $array[""] = "Select Unit";
                                                    foreach ($units as $index => $unit) {
                                                        $array[$index] = $unit;
                                                    }
                                                ?>
                                                <?php echo form_dropdown("details[unit][]", $array, $detail->unit, " class='form-control' required "); ?>
                                            </td>
                                            <td>
                                                <?php echo form_dropdown("details[question_group][]", $question_group, $detail->question_group, " class='form-control' required "); ?>
                                            </td>
                                            <td>
                                                <?php echo form_dropdown("details[level][]", $level, $detail->level, " class='form-control' required "); ?>
                                            </td>
                                            <td>
                                                <?php echo form_dropdown("details[question_type][]", $question_type, $detail->question_type, " class='form-control' required "); ?>
                                            </td>
                                            <td>
                                                <input type="number" name="details[mark][]" value="<?php echo $detail->mark ?>" step=1 class="form-control" required>
                                            </td>
                                            <td>
                                                <input type="number" name="details[no_of_questions][]" step=1 class="form-control" value="<?php  echo $detail->no_of_questions ?>" required>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-info detail-add">Add</a>
                                                <a href="#" class="btn btn-danger detail-remove">Remove</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br/>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_exam_setting")?>" >
                        </div>
                    </div>

                </form>
                <input type="hidden" id="ajax-get-subjects-url" value="<?php echo base_url() ?>subject/ajaxGetSubjectsFromClassId">
                <input type="hidden" id="ajax-get-units-url" value="<?php echo base_url() ?>unit/ajaxGetUnitsFromSubjectId">


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

$(document).on('click', '.detail-add', function(e) {
    e.preventDefault();
    var ddl = document.getElementById("subject_id");
    var selectedValue = ddl.options[ddl.selectedIndex].value;
    if (!selectedValue)
    {
        alert("Please select a subject");
    } else {
        let content = $('#intial_setting_details').html();
        content = content.split('changetorequired').join('required')
        //alert(content);
        $('#add-setting').append("<tr>" + content + "</tr>");
        $('#subject_id').prop('disabled', 'disabled');
        $('#classesID').prop('disabled', 'disabled');
    }
})

$(document).on('click', '.detail-remove', function(e) {
    e.preventDefault();
    $(this).parent().parent().remove();
    var tbody = $("#add-setting");
    if (tbody.children().length == 0) {
        $('#subject_id').prop('disabled', false);
        $('#classesID').prop('disabled', false);
    }
})

var ddl = document.getElementById("subject_id");
var selectedValue = ddl.options[ddl.selectedIndex].value;
var tbody = $("#add-setting");
if (selectedValue && tbody.children().length != 0){
    $('#subject_id').prop('disabled', 'disabled');
    $('#classesID').prop('disabled', 'disabled');
}

$("form").submit(function() {
    $("#subject_id").prop("disabled", false);
    $("#classesID").prop("disabled", false);
});
</script>
