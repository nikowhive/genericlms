<style>
   .bottomBorder {
      border-top: 1px solid #ccc;
      padding-top: 10px;
   }
</style>
<div class="container container--sm">

<header class="pg-header mt-4">
        <div>
            <h1 class="pg-title">
                <?=$this->lang->line('panel_title')?>
                </h1>
                <ol class="breadcrumb">
                <li><a href="<?=base_url("dashboard/index")?>"> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="active"><?=$this->lang->line('menu_marksetting')?></li>
            </ol>
        </div>
</header>
    <div class="card card--spaced">
 
        <!-- form start -->
        <div class="card-body">
    
            <div class="alert alert-danger print-error-msg" style="display:none">
            </div>
            <div class="alert alert-success print-success-msg" style="display:none">
            </div>
            <br>
            <fieldset class="   setting-fieldset">
                <legend class="mb-1 setting-legend"><?=$this->lang->line("marksetting_mark_type")?></legend>
                <div class="form-groupss">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="md-form md-form--select">
                            
                                <?php
                                    $marktypeArray[0] = $this->lang->line('marksetting_global');
                                    $marktypeArray[1] = $this->lang->line('marksetting_class_wise');
                                    $marktypeArray[2] = $this->lang->line('marksetting_exam_wise');
                                    $marktypeArray[4] = $this->lang->line('marksetting_subject_wise');
                                    $marktypeArray[5] = $this->lang->line('marksetting_class_exam_wise');
                                    $marktypeArray[6] = $this->lang->line('marksetting_class_exam_subject_wise');
                                    echo form_dropdown("marktypeID", $marktypeArray, set_value("marktypeID", $siteinfos->marktypeID), "id='marktypeID' class='mdb-select'");
                                ?>
                                <label class="mdb-main-label"><?=$this->lang->line("marksetting_mark_type")?>
                                </label>
                                <span class="text-danger error">
                                    <?=form_error('marktypeID'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
    
            <form class="mainmarktypeID" id="mainmarktypeID0" role="form" method="post">
                <input type="hidden" name="marktypeID" class="marktypeID" value="0">
                <fieldset class="mb-3 setting-fieldset">
                    <legend class="mb-1 setting-legend"><?=$this->lang->line("marksetting_exam")?></legend>
                    <div class="row">
                    <div class="col-md-12">
                    <?php
                            if(customCompute($exams)) { ?>
                                 <table class="table table-striped table-bordered table-hover">
                                   <thead>
                                      <tr>
                                        <th></th>
                                        <th>Exam Type</th>
                                      </tr>
                                   </thead>
                                   <tbody>
                                    <?php
                                      $checkexamArr = isset($examArr[0]) ? $examArr[0] : [];
                                      foreach ($exams as $exam) {
                                           $checkbox = (in_array($exam->examID, $checkexamArr)) ? true : false;  ?>
                                           <tr>
                                                <td>
                                                  <?php
                                                   echo '<input class="globalexam" type="checkbox" value="0_'.$exam->examID.'" '.set_checkbox('exams[]', '0_'.$exam->examID, $checkbox).' name="exams[]"> &nbsp;';
                                                  ?>
                                                  </td>
                                                <td><?php  echo $exam->exam ?></td>
                                           </tr>
                                    <?php } ?>
                                   </tbody>
                                 </table>
                           <?php } ?>
                    </div>
                        
                    </div>
                </fieldset>
                <fieldset class="mb-3 setting-fieldset">
                    <legend class="mb-1 setting-legend"><?=$this->lang->line("marksetting_mark_percentage")?></legend>
                    <div class="row">
                      <div class="col-md-12">
                        <?php
                            if(customCompute($markpercentages)) { ?>
                                 <table class="table table-striped table-bordered table-hover">
                                   <thead>
                                      <tr>
                                        <th></th>
                                        <th>Mark Distribution Type</th>
                                        <th>Mark Value</th>
                                      </tr>
                                   </thead>
                                   <tbody>
                                    <?php
                                      $checkmarkpercentageArr = isset($markpercentageArr[0]) ? $markpercentageArr[0] : [];
                                      foreach ($markpercentages as $markpercentage) {
                                          $checkbox = (in_array($markpercentage->markpercentageID, $checkmarkpercentageArr)) ? true : false; ?>
                                           <tr>
                                                <td><?php  echo '<input class="globalmarkpercentage" type="checkbox" '.set_checkbox('markpercentages[]', '0_'.$markpercentage->markpercentageID, $checkbox).' value="0_'.$markpercentage->markpercentageID.'" name="markpercentages[]"> &nbsp;'; ?></td>
                                                <td><?php  echo $markpercentage->markpercentagetype ?></td>
                                                <td><?php  echo $markpercentage->percentage ?></td>
                                           </tr>
                                    <?php } ?>
                                   </tbody>
                                 </table>
                           <?php } ?>
                      </div>
                    </div>
                </fieldset>
                <button type="submit" class="btn btn-success"><?=$this->lang->line("update_mark_setting")?></button>
            </form>
    
            <form class="  mainmarktypeID" id="mainmarktypeID1" role="form" method="post">
                <input type="hidden" name="marktypeID" class="marktypeID">
                <fieldset class="mb-3 setting-fieldset">
                    <legend class="mb-1 setting-legend"><?=$this->lang->line("marksetting_exam")?></legend>
                    <div class="row">
                    <div class="col-md-12">
                    <?php
                            if(customCompute($exams)) { ?>
                                 <table class="table table-striped table-bordered table-hover">
                                   <thead>
                                      <tr>
                                        <th></th>
                                        <th>Exam Type</th>
                                      </tr>
                                   </thead>
                                   <tbody>
                                    <?php
                                     $checkexamArr = isset($examArr[1]) ? $examArr[1] : [];
                                      foreach ($exams as $exam) {
                                        $checkbox = (in_array($exam->examID, $checkexamArr)) ? true : false; ?>
                                        <tr>
                                                <td>
                                                  <?php
                                                  echo '<input class="classwiseexam" type="checkbox" '.set_checkbox('exams[]', '1_'.$exam->examID, $checkbox).' value="1_'.$exam->examID.'" name="exams[]"> &nbsp;';
                                                  ?>
                                                  </td>
                                                <td><?php  echo $exam->exam ?></td>
                                           </tr>
                                    <?php } ?>
                                   </tbody>
                                 </table>
                           <?php } ?>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="md-form md-form--select">
                                <?php
                                $classesArray = [];
                                if(customCompute($classes)){
                                    $classesArray[''] = '- Select Class -';
                                    foreach($classes as $class){
                                        $classesArray[$class->classesID] = $class->classes;
                                    }
                                }
                                    echo form_dropdown("classesID", $classesArray, set_value("classesID",$currentSavedClass?$currentSavedClass->classesID:''), "id='marktype1classesID' class='mdb-select'");
                                ?>
                                <label class="mdb-main-label"><?=$this->lang->line("marksetting_classes")?> <span class="text-red">*</span>
                                </label>
                                <span class="text-danger error">
                                    <?=form_error('classesID'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </fieldset>
                 <fieldset class="mb-3 setting-fieldset">
                    <div class="row" id="markTypeWrapper1">
                        
                    </div>
                </fieldset>
                <div class="row">
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_mark_setting")?>" >
                    </div>
                </div>
            </form>
    
            <form class="form-horizontal mainmarktypeID" id="mainmarktypeID2" role="form" method="post">
                <input type="hidden" name="marktypeID" class="marktypeID">
                <div class="row">
                        <div class="col-md-6">
                            <div class="md-form md-form--select">
                                <?php
                                $examsArray = [];
                                if(customCompute($exams)){
                                    $examsArray[''] = '- Select Exam -';
                                    foreach($exams as $exam){
                                        $examsArray[$exam->examID] = $exam->exam;
                                    }
                                }
                                    echo form_dropdown("exams[]", $examsArray, set_value("exams[]",$currentSavedExam?$currentSavedExam->examID:''), "id='marktype2examID' class=' examwiseexam mdb-select'");
                                ?>
                                <label class="mdb-main-label"><?=$this->lang->line("marksetting_exam")?> <span class="text-red">*</span>
                                </label>
                                <span class="text-danger error">
                                    <?=form_error('exams[]'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                 <fieldset class="mb-3 setting-fieldset">
                    <!-- <legend class="mb-1 setting-legend"><?=$this->lang->line("marksetting_exam_wise")?></legend> -->
                    <div class="row" id="markTypeWrapper2">
                       
                    </div>
                </fieldset>
                <div class="row">
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_mark_setting")?>" >
                    </div>
                </div>
            </form>
    
            <form class="form-horizontal mainmarktypeID" id="mainmarktypeID4" role="form" method="post">
                <input type="hidden" name="marktypeID" class="marktypeID">
                 <fieldset class="mb-3 setting-fieldset">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="md-form md-form--select">
                                <?php
                                $classesArray = [];
                                if(customCompute($classes)){
                                    $classesArray[''] = '- Select Class -';
                                    foreach($classes as $class){
                                        $classesArray[$class->classesID] = $class->classes;
                                    }
                                }
                                    echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='marktype4classesID' class='mdb-select'");
                                ?>
                                <label class="mdb-main-label"><?=$this->lang->line("marksetting_classes")?> <span class="text-red">*</span>
                                </label>
                                <span class="text-danger error">
                                    <?=form_error('classesID'); ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="md-form md-form--select">
                                <?php
                                 $subjectsArray = [];
                                    echo form_dropdown("subjectID", $subjectsArray, set_value("subjectID"), "id='marktype4SubjectID' class='mdb-select'");
                                ?>
                                <label class="mdb-main-label">Subject <span class="text-red">*</span>
                                </label>
                                <span class="text-danger error">
                                    <?=form_error('subjectID'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="markpercentage4Wrapper">
                        
                    </div>
                </fieldset>
                <div class="row">
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_mark_setting")?>" >
                    </div>
                </div>
            </form>
    
            <form class="form-horizontal mainmarktypeID" id="mainmarktypeID5" role="form" method="post">
                <input type="hidden" name="marktypeID" class="marktypeID">
                 <fieldset class="mb-3 setting-fieldset">
                 <div class="row">
                        <div class="col-md-6">
                            <div class="md-form md-form--select">
                                <?php
                                $classesArray = [];
                                if(customCompute($classes)){
                                    $classesArray[''] = '- Select Class -';
                                    foreach($classes as $class){
                                        $classesArray[$class->classesID] = $class->classes;
                                    }
                                }
                                    echo form_dropdown("classesID", $classesArray, set_value("classesID",$currentSavedClassExamWise?$currentSavedClassExamWise->classesID:''), "id='marktype5classesID' class='mdb-select'");
                                ?>
                                <label class="mdb-main-label"><?=$this->lang->line("marksetting_classes")?> <span class="text-red">*</span>
                                </label>
                                <span class="text-danger error">
                                    <?=form_error('classesID'); ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="md-form md-form--select">
                                <?php
                                $examsArray = [];
                                if(customCompute($exams)){
                                    $examsArray[''] = '- Select Exam -';
                                    foreach($exams as $exam){
                                        $examsArray[$exam->examID] = $exam->exam;
                                    }
                                }
                                    echo form_dropdown("examID", $examsArray, set_value("examID",$currentSavedClassExamWise?$currentSavedClassExamWise->examID:''), "id='marktype5examID' class='classexamwise mdb-select'");
                                ?>
                                <label class="mdb-main-label"><?=$this->lang->line("marksetting_exam")?> <span class="text-red">*</span>
                                </label>
                                <span class="text-danger error">
                                    <?=form_error('examID'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- <legend class="mb-1 setting-legend"><?=$this->lang->line("marksetting_class_exam_wise")?></legend> -->
                    <div class="row" id="markTypeWrapper5">
                       
                    </div>
                </fieldset>
                <div class="row">
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_mark_setting")?>" >
                    </div>
                </div>
            </form>
    
            <form class="form-horizontal mainmarktypeID" id="mainmarktypeID6" role="form" method="post">
                <input type="hidden" name="marktypeID" class="marktypeID">
                 <fieldset class="mb-3 setting-fieldset">
                    <!-- <legend class="mb-1 setting-legend"><?=$this->lang->line("marksetting_class_exam_wise")?></legend> -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="md-form md-form--select">
                                <?php
                                $classesArray = [];
                                if(customCompute($classes)){
                                    $classesArray[''] = '- Select Class -';
                                    foreach($classes as $class){
                                        $classesArray[$class->classesID] = $class->classes;
                                    }
                                }
                                    echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='marktype6classesID' class='mdb-select'");
                                ?>
                                <label class="mdb-main-label"><?=$this->lang->line("marksetting_classes")?> <span class="text-red">*</span>
                                </label>
                                <span class="text-danger error">
                                    <?=form_error('classesID'); ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="md-form md-form--select">
                                <?php
                                $examsArray = [];
                                if(customCompute($exams)){
                                    $examsArray[''] = '- Select Exam -';
                                    foreach($exams as $exam){
                                        $examsArray[$exam->examID] = $exam->exam;
                                    }
                                }
                                    echo form_dropdown("examID", $examsArray, set_value("examID",$currentSavedClassExamSubjectWise?$currentSavedClassExamSubjectWise->examID:''), "id='marktype6examID' class=' examwiseexam mdb-select'");
                                ?>
                                <label class="mdb-main-label"><?=$this->lang->line("marksetting_exam")?> <span class="text-red">*</span>
                                </label>
                                <span class="text-danger error">
                                    <?=form_error('examID'); ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="md-form md-form--select">
                                <?php
                                 $subjectsArray = [];
                                    echo form_dropdown("subjectID", $subjectsArray, set_value("subjectID"), "id='marktype6SubjectID' class='mdb-select'");
                                ?>
                                <label class="mdb-main-label">Subject <span class="text-red">*</span>
                                </label>
                                <span class="text-danger error">
                                    <?=form_error('subjectID'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="markpercentage6Wrapper">
                       
                    </div>
                </fieldset>
                <div class="row">
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-success btn-md" value="<?=$this->lang->line("update_mark_setting")?>" >
                    </div>
                </div>
            </form>
    
        </div>
    </div>
</div>

<script>

$('#mainmarktypeID0,#mainmarktypeID1,#mainmarktypeID2,#mainmarktypeID4,#mainmarktypeID5,#mainmarktypeID6').submit(function(e){
        e.preventDefault();
        var formValues= $(this).serialize();
        $.ajax({
             url:"<?=base_url()?>marksetting1/saveMarkSetting",
             type:'POST',
             data : formValues,
             dataType: 'json',
             success: function (res) {
                        if(res.status){
                            $('.print-success-msg').html(res.message);
                            $('.print-success-msg').show();
                            $('.print-error-msg').hide();
                            $(window).scrollTop(0);
                        }else{
                            $('.print-error-msg').html(res.message);
                            $('.print-error-msg').show();
                            $('.print-success-msg').hide();
                            $(window).scrollTop(0);
                        }
                    },
        });
   });

   $('#marktype4classesID').change(function(e){
      var classesID = $(this).val();
      $('#markpercentage4Wrapper').html('');
      $('#marktype4SubjectID').html('');
      $("#marktype4SubjectID").material_select();
      if(classesID != ''){
        $.ajax({
             url:"<?=base_url()?>marksetting1/getSubject",
             type:'POST',
             data : {'classesID':classesID},
             success: function (response) {
                        $('#marktype4SubjectID').html(response);
                         $("#marktype4SubjectID").material_select();
                    },
        });

      }
   })

   $('#marktype6classesID').change(function(e){
      var classesID = $(this).val();
      $('#markpercentage6Wrapper').html('');
      $('#marktype6SubjectID').html('');
      $("#marktype6SubjectID").material_select();
      if(classesID != ''){
        $.ajax({
             url:"<?=base_url()?>marksetting1/getSubject",
             type:'POST',
             data : {'classesID':classesID},
             success: function (response) {
                        $('#marktype6SubjectID').html(response);
                         $("#marktype6SubjectID").material_select();
                    },
        });

      }
   })

   $('#marktype4SubjectID').change(function(e){

      var subjectID = $(this).val();
      var classesID = $('#marktype4classesID').val();
      $('#markpercentage4Wrapper').html('');

      if(classesID != '' && subjectID != ''){
        $.ajax({
             url:"<?=base_url()?>marksetting1/getMarkPercentageByClassSubjectwise",
             type:'POST',
             data : {'marktypeID':4,'classesID':classesID,'subjectID':subjectID},
             success: function (response) {
                        $('#markpercentage4Wrapper').html(response);
                    },
        });
      }
   })

   $('#marktype6SubjectID').change(function(e){

    var subjectID = $(this).val();
    var classesID = $('#marktype6classesID').val();
    var examID = $('#marktype6examID').val();
    $('#markpercentage6Wrapper').html('');

    if(classesID != '' && subjectID != '' && examID != ''){
        $.ajax({
            url:"<?=base_url()?>marksetting1/getMarkPercentageByClassExamSubjectwise",
            type:'POST',
            data : {'marktypeID':6,'classesID':classesID,'subjectID':subjectID,'examID':examID},
            success: function (response) {
                $('#markpercentage6Wrapper').html(response);
            },
        });
    }
})


$('#marktype6examID').change(function(e){

var examID = $(this).val();
var classesID = $('#marktype6classesID').val();
var subjectID = $('#marktype6SubjectID').val();
$('#markpercentage6Wrapper').html('');

if(classesID != '' && subjectID != '' && examID != ''){
    $.ajax({
        url:"<?=base_url()?>marksetting1/getMarkPercentageByClassExamSubjectwise",
        type:'POST',
        data : {'marktypeID':6,'classesID':classesID,'subjectID':subjectID,'examID':examID},
        success: function (response) {
            $('#markpercentage6Wrapper').html(response);
        },
    });
}
})


   $('#marktype1classesID').change(function(){
        var val = $(this).val();
        $('#markTypeWrapper1').html('');
        if(val != ''){
        $.ajax({
             url:"<?=base_url()?>marksetting1/getMarkPercentageByClasswise",
             type:'POST',
             data : {'classesID':val,'marktypeID':1},
             success: function (response) {
                        $('#markTypeWrapper1').html(response);
                    },
        });
    }
   });

    <?php if($siteinfos->marktypeID == 1){ ?>
         $('#marktype1classesID').trigger('change');
    <?php  } ?>

   $('#marktype2examID').change(function(){
        var val = $(this).val();
        $('#markTypeWrapper2').html('');
        if(val != ''){
        $.ajax({
             url:"<?=base_url()?>marksetting1/getMarkPercentageByExamwise",
             type:'POST',
             data : {'examID':val,'marktypeID':2},
             success: function (response) {
                        $('#markTypeWrapper2').html(response);
                    },
        });
    }
   });
   <?php if($siteinfos->marktypeID == 2){ ?>
   $('#marktype2examID').trigger('change');
   <?php  } ?>

   $('#marktype5classesID').change(function(){
    var classesID = $(this).val();
        var examID = $('#marktype5examID').val();
        $('#markTypeWrapper5').html('');
        if(classesID != '' && examID != ''){
        $.ajax({
             url:"<?=base_url()?>marksetting1/getMarkPercentageByClassExamwise",
             type:'POST',
             data : {'classesID':classesID,'marktypeID':5,'examID':examID},
             success: function (response) {
                        $('#markTypeWrapper5').html(response);
                    },
        });
    }
   });
   
   $('#marktype5examID').change(function(){
        var examID = $(this).val();
        var classesID = $('#marktype5classesID').val();
        $('#markTypeWrapper5').html('');
        if(classesID != '' && examID != ''){
        $.ajax({
             url:"<?=base_url()?>marksetting1/getMarkPercentageByClassExamwise",
             type:'POST',
             data : {'classesID':classesID,'marktypeID':5,'examID':examID},
             success: function (response) {
                        $('#markTypeWrapper5').html(response);
                    },
        });
    }
   });
   <?php if($siteinfos->marktypeID == 5){ ?>
   $('#marktype5examID').trigger('change');
   <?php  } ?>

</script>

<script type="text/javascript">
    $('.select2').select2();
    $('.mainmarktypeID').hide();
    
    <?php if(set_value("marktypeID") || (set_value("marktypeID")==0 && set_value("marktypeID") !=NULL)) { ?>
        $('#mainmarktypeID<?=set_value("marktypeID")?>').show('slow');
        $('.marktypeID').val(<?=set_value("marktypeID")?>);
    <?php } else { ?>
        $('#mainmarktypeID<?=$siteinfos->marktypeID?>').show('slow');
        $('.marktypeID').val(<?=$siteinfos->marktypeID?>);
    <?php } ?>
    
    $('#marktypeID').change(function() {
       
        $('.print-error-msg').hide();
        $('.print-success-msg').hide();
        $('.print-error-msg').html('');
        $('.print-success-msg').html('');
        var marktypeID = $(this).val();
        $('.marktypeID').val(marktypeID);
        
        $('.mainmarktypeID').hide('slow');
        marktypeID = parseInt(marktypeID);
        $('#mainmarktypeID'+marktypeID).show('slow');
    });

    $('.globalmarkpercentage').attr('disabled', true);
    <?php if((isset($markpercentageArr[0]) && customCompute($markpercentageArr[0])) || (set_value('exams[]'))) { ?>
        $('.globalmarkpercentage').attr('disabled', false);
    <?php } ?>
    $('.globalexam').click(function() {
        if($(this).prop("checked") == true) {
            $('.globalmarkpercentage').removeAttr('disabled');
        } else {
            if($('.globalexam').is(':checked') == false) {
                $('.globalmarkpercentage').attr('disabled', true);
            }
        }
    });
   
</script>