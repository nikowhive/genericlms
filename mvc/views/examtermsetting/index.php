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
                <li class="active"><?=$this->lang->line('menu_examtermsetting')?></li>
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
           
    
            <form class="examtermsetting" id="examtermsetting" role="form" method="post">
               
            <fieldset class="   setting-fieldset">
                <legend class="mb-1 setting-legend"><?=$this->lang->line("marksetting_mark_type")?></legend>
                <div class="form-groupss">
                    <div class="row">
                    <div class="col-md-6">
                            <div class="md-form md-form--select">
                            <?php
                                $examArray = [];
                                if(customCompute($classes)){
                                    $examArray[''] = '- Select Exam -';
                                    foreach($finalexams as $exam){
                                        $examArray[$exam->examID] = $exam->exam;
                                    }
                                }
                                    echo form_dropdown("examID", $examArray, set_value("examID"), "id='examID' class='mdb-select'");
                                ?>
                                <label class="mdb-main-label"><?=$this->lang->line("examtermsetting_exam")?>
                                </label>
                                <span class="text-danger error">
                                    <?=form_error('examID'); ?>
                                </span>
                            </div>
                        </div>
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
                                    echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='mdb-select'");
                                ?>
                                <label class="mdb-main-label"><?=$this->lang->line("examtermsetting_classes")?>
                                </label>
                                <span class="text-danger error">
                                    <?=form_error('classesID'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

                <fieldset class="mb-3 setting-fieldset" id="examtermsettingWrapper">
                   
                </fieldset>
              
                <button type="submit" id="updateBtn" class="btn btn-success"><?=$this->lang->line("update_examterm_setting")?></button>
            </form>
    
        </div>
    </div>
</div>

<script>

$(function(){
       
    $("#updateBtn").prop('disabled', true);
    calculateTotalMarks(); 
});

$('#examtermsetting').submit(function(e){
        e.preventDefault();
        var formValues= $(this).serialize();
        $.ajax({
             url:"<?=base_url()?>examtermsetting/saveMarkSetting",
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
  

   $('#examID').change(function(e){

      var examID = $(this).val();
      var classesID = $('#classesID').val();
      $('#examtermsettingWrapper').html('');

      if(classesID != '' && examID != ''){
        $.ajax({
             url:"<?=base_url()?>examtermsetting/getTermWeightageSetting",
             type:'POST',
             data : {'classesID':classesID,'examID' : examID},
             success: function (response) {
                        $('#examtermsettingWrapper').html(response);
                        $('.marks').on('keyup',function(){
                                calculateTotalMarks(examID);
                            });
                            $('.examtermsettingCheckbox').click(function() {
                                var val  = $(this).val();
                                if($(this).prop("checked") == true) {
                                    $('#mark'+val).removeAttr('disabled');
                                } else {
                                    $('#mark'+val).attr('disabled', true);
                                    $('#mark'+val).val(0);
                                    calculateTotalMarks(examID);
                                }
                            });
                    },
        });

      }
   });

   $('#classesID').change(function(e){

            var classesID = $(this).val();
            var examID = $('#examID').val();
            $('#examtermsettingWrapper').html('');

            if(classesID != '' && examID != ''){
            $.ajax({
                url:"<?=base_url()?>examtermsetting/getTermWeightageSetting",
                type:'POST',
                data : {'classesID':classesID,'examID' : examID},
                success: function (response) {
                            $('#examtermsettingWrapper').html(response);
                            $('.marks').on('keyup',function(){
                                calculateTotalMarks(examID);
                            });
                            $('.examtermsettingCheckbox').click(function() {
                                var val  = $(this).val();
                                if($(this).prop("checked") == true) {
                                    $('#mark'+val).removeAttr('disabled');
                                } else {
                                    $('#mark'+val).attr('disabled', true);
                                    $('#mark'+val).val('');
                                    calculateTotalMarks(examID);
                                }
                            });
                        },
            });

            }
});

   

   function calculateTotalMarks(examID){
        var sum = 0;
        $('.marks').each(function() {
            if($(this).val() != ''){
                sum += Number($(this).val());
            }
        });
        
        var total = 100;
        var remainingmarks = parseInt(total) - parseInt(sum);

        $('#mark'+examID).val(remainingmarks);
       
        if(remainingmarks <= 0){
            sum = 0;
        }else{
            sum = sum + remainingmarks;
        }

        $('#total').html(sum);
        if(sum == 100){
            $("#updateBtn").prop('disabled', false); 
        }else{
            $("#updateBtn").prop('disabled', true); 
        }
   }


 

  







  


</script>

