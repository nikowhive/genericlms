<style type="text/css">
    .fuelux .wizard .step-content {
        border: 0px;
    }
    .sidebar-toggle {
        display: none;
    }

    .right-side {
        margin-left: 0;
    }

    .question-body .lb-content {
        font-size: 20px;
        color: black;
    }

    .question-answer-button button {
        margin: 10px;
    }

    #finishedbutton {
        /* float: right; */
    }

    .disable-pointer {
        pointer-events: none; 
        cursor: default; 
    }

    .noselect {
    -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Safari */
        -khtml-user-select: none; /* Konqueror HTML */
        -moz-user-select: none; /* Old versions of Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
        user-select: none; /* Non-prefixed version, currently
        supported by Chrome, Edge, Opera and Firefox */
    }
</style>
<div class="col-sm-12 do-not-refresh">
    <div class="callout callout-danger">
        <h4><?=$this->lang->line('take_exam_warning')?></h4>
        <p><?=$this->lang->line('take_exam_page_refresh')?></p>
    </div>
</div>

<div class="row">
    <div class="<?php echo $typeNumber==5?'col-sm-12':'col-sm-7'?> fu-example section">
        <div class="box outheBoxShadow wizard" data-initialize="wizard" id="questionWizard">
            <div class="box-header bg-white">
                <div class="checkbox hints">
                    <label>
                    </label>
                    <span class="pull-right">
                        <label>
                        </label>
                    </span>
                </div>
            </div>
            <div class="steps-container">
                <ul class="steps hidden" style="margin-left: 0">
                    <?php
                        $countOnlineExamQuestions = customCompute($onlineExamQuestions);
                        foreach (range(1, $countOnlineExamQuestions) as $value) { ?>
                            <li data-step="<?=$value?>" class="<?=$value == 1 ? 'active' : ''?>"></li>
                    <?php } ?>
                </ul>
            </div>

            <form id="answerForm" method="post" enctype="multipart/form-data">
                <div class="box-body step-content">
                    <input style="display:none" type="text" name="studentfinishstatus">
                <?php
                if($countOnlineExamQuestions) {
                    $q = 1;
                    foreach ($onlineExamQuestions as $key => $onlineExamQuestion) {
                        $question        = isset($questions[$onlineExamQuestion->questionID]) ? $questions[$onlineExamQuestion->questionID] : '';
                        $questionOptions = isset($options[$onlineExamQuestion->questionID]) ? $options[$onlineExamQuestion->questionID] : [];
                        $questionAnswers = isset($answers[$onlineExamQuestion->questionID]) ? $answers[$onlineExamQuestion->questionID] : [];
                        
                        if($question != '') {
                            if($question->typeNumber == 1 || $question->typeNumber == 2) {
                                $questionAnswers = pluck($questionAnswers, 'optionID');
                            }
                            $optionCount = $question->totalOption; ?>
                            <div class="clearfix step-pane sample-pane <?=$key == 0 ? 'active' : '' ?>" data-questionID="<?=$question->questionBankID?>" data-step="<?=$key+1?>">
                                <div class="question-body" data-questionID="<?=$question->questionBankID ?>" data-questionTypeNumber="<?=$question->typeNumber ?>">
                                    <label class="lb-title"><?=$this->lang->line('take_exam_question')?> <?=$key+1?> <?=$this->lang->line('take_exam_of')?> <?=$countOnlineExamQuestions?></label>
                                    <label class="lb-content noselect"> <?=$question->question?></label>
                                    <label class="lb-mark"> <?= $question->mark != "" ? $question->mark.' '.$this->lang->line('take_exam_mark') : ''?> </label>
                                    <?php if($question->upload != '') { $imgarr =  explode(",",$question->upload);
                                    if(!empty($imgarr))
                                    {
                                       
                                        foreach($imgarr as $img)
                                        {
                                          $extension = @array_pop(explode('.', $img));
                                          if($extension=='pdf' || $extension=='docx' || $extension=='doc')
                                          { ?>
                                           <p><a href="<?=base_url('uploads/images/'.$img)?>" class="btn btn-success" target="_blank">View File</a></p>
                                          <?php 
                                          }
                                          else
                                          { 
                                    ?>
                                        <div>
                                            <p><img src="<?=base_url('uploads/images/'.$img)?>" alt=""></p>
                                        </div>
                                    <?php } 
                                          }
                                          } 
                                          } 
                                    ?>
                                </div>

                                <div class="question-answer" id="step<?=$key+1?>">
                                    <table class="table">
                                        <tr>
                                            <?php
                                            $tdCount = 0; $oc = 1;
                                            foreach ($questionOptions as $option) {
                                                if($optionCount >= $oc) { $oc++; ?>
                                                <td>
                                                    <input id="option<?=$option->optionID?>" value="<?=$option->optionID?>" name="answer[<?=$question->typeNumber?>][<?=$question->questionBankID?>][]" type="<?=$question->typeNumber == 1 ? 'radio' : 'checkbox'?>" class="js-chk" title="answer<?=$question->typeNumber.$question->questionBankID;?>" <?=$option->prefill_data != null ? 'checked': ''?>>
                                                    <label for="option<?=$option->optionID?>">
                                                        <span class="fa-stack <?=$question->typeNumber == 1 ? 'radio-button' : 'checkbox-button'?>">
                                                            <i class="active fa fa-check">
                                                            </i>
                                                        </span>
                                                        <span class="noselect"><?=$option->name?></span>
                                                        <?php
                                                        if(!is_null($option->img) && $option->img != "") { ?>
                                                            <div>
                                                                <img style="width: 100px;height: 80px" src="<?=base_url('uploads/images/'.$option->img)?>"/>
                                                            </div>
                                                         <?php } ?>
                                                    </label>
                                                </td>
                                                <?php
                                                }
                                                $tdCount++;
                                                if($tdCount == 2) {
                                                    $tdCount = 0;
                                                    echo "</tr><tr>";
                                                }
                                            }

                                            if($question->typeNumber == 3) {
                                                foreach ($questionAnswers as $answerKey => $answer) {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <input type="button" value="<?=$answerKey+1?>"> <input class="fillInTheBlank" id="answer<?=$answer->answerID?>" name="answer[<?=$question->typeNumber?>][<?=$question->questionBankID?>][<?=$answer->answerID?>]" value="<?=isset($answer->prefill_array[$answerKey]) ? $answer->prefill_array[$answerKey]: '' ?>" type="text">
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            
                                            if($question->typeNumber == 4) {
                                               ?>
                                                    <tr>
                                                        <td>
                                                       <textarea class="form-control" name="answer[<?=$question->typeNumber?>][<?=$question->questionBankID?>]" cols="30" rows="6" data-type="textarea"><?php echo $subjectiveAnswers?isset($subjectiveAnswers[$question->questionBankID])?$subjectiveAnswers[$question->questionBankID]:'':'' ?></textarea>
                                                       </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                        <?php 
                                                         if(isset($subjectiveFiles[$question->questionBankID])){
                                                            $val = 1;
                                                         }else{
                                                             $val = '';
                                                         }
                                                        ?>
                                                        <input type="hidden" value="<?php echo $val; ?>" id="files<?=$question->questionBankID?>"/>
                                                        <input type="file" class="image_file_input" data-issubjective="1" data-type=<?=$question->typeNumber?> data-question=<?=$question->questionBankID?>
                                                         name="image[<?=$question->typeNumber?>][<?=$question->questionBankID?>][]"
                                                         multiple="multiple">
                                                        </td>
                                                        
                                                    </tr>
                                                    <tr>
                                                     <td>
                                                      <p class="label label-success" id="successMsg<?=$question->questionBankID?>"></p>
                                                      <p class="label label-danger"id="errorMsg<?=$question->questionBankID?>"></p>
                                                      <table style="margin-top:10px;" class="table table-bordered" id="fileTable<?=$question->typeNumber?><?=$question->questionBankID?>">
                                                         <tbody>
                                                        <?php
                                                         if(isset($subjectiveFiles[$question->questionBankID])){
                                                         if(customCompute($subjectiveFiles[$question->questionBankID])){
                                                             foreach($subjectiveFiles[$question->questionBankID] as $subjectiveFile){
                                                                $link = base_url().'uploads/images/'.$subjectiveFile->link;
                                                                 echo '<tr class="messages"><td>'.$subjectiveFile->link.'</td> <td style="text-align:center;"><a href="javascript:void(0)" data-link="'.$link.'" data-toggle="modal" data-target="#imageModal"><span class="label label-primary">View</span></a>&nbsp;<a href="javascript:void(0)" class="deleteEditFile" data-id="'.$subjectiveFile->id.'"><span class="label label-danger">Delete</span></a></td></tr>';
                                                             }
                                                         }} ?>
                                                         
                                                         </tbody>
                                                      </table>
                                                     </td>
                                                    </tr>
                                                    <?php
                                                
                                            }

                                            if($question->typeNumber == 5) {
                                                ?>
                                                     <tr>
                                                         <td>
                                                         <textarea class="form-control" type="textarea" name="answer[<?=$question->typeNumber?>][<?=$question->questionBankID?>]" cols="30" rows="6"><?php echo $subjectiveAnswers?isset($subjectiveAnswers[$question->questionBankID])?$subjectiveAnswers[$question->questionBankID]:'':'' ?></textarea>
                                                        </td>
                                                     </tr>
                                                     <tr>
                                                        <td>
                                                        <?php 
                                                         if(isset($subjectiveFiles[$question->questionBankID])){
                                                            $val = 1;
                                                         }else{
                                                             $val = '';
                                                         }
                                                        ?>
                                                        <input type="hidden" value="<?php echo $val; ?>" id="files<?=$question->questionBankID?>"/>
                                                        <input type="file" class="image_file_input" data-issubjective="0" data-type=<?=$question->typeNumber?> data-question=<?=$question->questionBankID?>
                                                         name="image[<?=$question->typeNumber?>][<?=$question->questionBankID?>][]"
                                                         multiple="multiple">
                                                        </td>
                                                        
                                                    </tr>
                                                    <tr>
                                                     <td>
                                                      <p class="label label-success" id="successMsg<?=$question->questionBankID?>"></p>
                                                      <p class="label label-danger"id="errorMsg<?=$question->questionBankID?>"></p>
                                                      <table style="margin-top:10px;" class="table table-bordered" id="fileTable<?=$question->typeNumber?><?=$question->questionBankID?>">
                                                         <tbody>
                                                        <?php
                                                         if(isset($subjectiveFiles[$question->questionBankID])){
                                                         if(customCompute($subjectiveFiles[$question->questionBankID])){
                                                             foreach($subjectiveFiles[$question->questionBankID] as $subjectiveFile){
                                                                $link = base_url().'uploads/images/'.$subjectiveFile->link;
                                                                 echo '<tr class="messages"><td>'.$subjectiveFile->link.'</td> <td style="text-align:center;"><a href="javascript:void(0)" data-link="'.$link.'" data-toggle="modal" data-target="#imageModal"><span class="label label-primary">View</span></a>&nbsp;<a href="javascript:void(0)" class="deleteEditFile" data-id="'.$subjectiveFile->id.'"><span class="label label-danger">Delete</span></a></td></tr>';
                                                             }
                                                         }} ?>
                                                         
                                                         </tbody>
                                                      </table>
                                                     </td>
                                                    </tr> 
                                                     <?php
                                                 
                                             }
                                             ?>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <?php
                        }
                        $q++;
                    }
                    
                } else {
                    echo "<p class='text-center'>".$this->lang->line('take_exam_no_question')."</p>";
                } ?>
                <div class="question-answer-button">
                   <?php if($typeNumber!=5) { ?>
                    <button class="btn-lg btn-primary oe-btn-answered btn-prev" type="button" name="" examid="<?=$onlineExamID ?>" id="prevbutton" disabled>
                        <i class="fa fa-angle-left"></i> <?=$this->lang->line('take_exam_previous')?>
                    </button>

                    <button class="btn-lg btn-primary oe-btn-notvisited" type="button" name="" id="reviewbutton" style="display:none;">
                        <?=$this->lang->line('take_exam_mark_review')?>
                    </button>
                   <?php } ?>
                    <button class="btn-lg btn-primary oe-btn-answered btn-next" type="button" name="" examid="<?=$onlineExamID ?>" id="nextbutton" data-last="<?=$this->lang->line('take_exam_finish')?>">
                        <?=$this->lang->line('take_exam_next')?> <i class="fa fa-angle-right"></i>
                    </button>
                    <?php if($typeNumber!=5) { ?>
                    <button class="btn-lg btn-primary oe-btn-notvisited" type="button" name="" id="clearbutton" style="display:none;">
                        <?=$this->lang->line('take_exam_clear_answer')?>
                    </button>
                    <?php } ?>
                    <button class="btn-lg btn-primary oe-btn-notanswered" type="button" name="" id="finishedbuttonp" onclick="finished()">
                        <?=$this->lang->line('take_exam_finish')?>
                    </button>

                </div>
            </div>
            </form>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="row">
            <div class="col-sm-12 counterDiv">
                <div class="box outheBoxShadow">
                    <div class="box-body outheMargAndBox">
                        <div class="box outheBoxShadow">
                            <div class="box-header bg-white" style="padding: 15px">
                                <button class="btn-lg btn-danger oe-btn-notanswered" type="button" name="" id="finishedbutton" onclick="finished()">
                                    <?=$this->lang->line('take_exam_finish')?>
                                </button>
                                <?php 
                                $hours = floor($onlineExam->duration / 60);
                                $minutes = $onlineExam->duration % 60;
                                $minutes = strlen($minutes) == 1 ? '0'.$minutes : $minutes;
                                ?>
                                <div style="float: right; padding: 15px; font-weight: 600;font-size: larger;">Total Time: 0<?=$hours ?>:<?=$minutes ?>:00</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php if($typeNumber < 5) { ?>
    <div class="col-sm-5">
        <div class="row">
            <div class="col-sm-12 counterDiv">
                <div class="box outheBoxShadow">
                    <div class="box-body outheMargAndBox">
                        <div class="box outheBoxShadow">
                            <div class="box-header bg-white">
                                <h3 class="box-title fontColor"> <?=$this->lang->line('take_exam_time_status')?></h3>
                            </div>
                            <div class="box-body">
                                <div id="timerdiv" class="timer">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="col-sm-12 counterDiv">
                <div class="box outheBoxShadowColor">
                    <div class="box-body innerMargAndBox">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3 class="fontColor"><?=$this->lang->line('take_exam_total_time')?></h3>
                            </div>
                            <div class="col-sm-6">
                                <h3 class="fontColor duration">00:00:00</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="col-sm-12">
                <div class="box outheBoxShadow">
                    <div class="box-body outheMargAndBox">
                        <div class="box-header bg-white">
                            <h3 class="box-title fontColor">
                                <?=$onlineExam->name?>
                                <br>
                            </h3>
                        </div>

                        <div class="box-body margAndBox" style="">
                            <nav aria-label="Page navigation">
                                <ul class="examQuesBox questionColor">
                                    <?php
                                        foreach ($onlineExamQuestions as $key => $onlineExamQuestion) {
                                            ?>
                                            <li><a class="notvisited question-square" id="question<?=$key+1?>" href="javascript:void(0);" examid="<?=$onlineExamID ?>" onclick="jumpQuestion(<?=$key+1?>, <?=$onlineExamID ?>)"><?=$key+1?></a></li>
                                            <?php
                                        }
                                    ?>
                                </ul>
                            </nav>


                            <nav aria-label="Page navigation">
                                <h2><?=$this->lang->line('take_exam_summary')?></h2>
                                <ul class="examQuesBox text">
                                    <li><a class="answered" id="summaryAnswered" href="#">0</a> <?=$this->lang->line('take_exam_answered')?></li>
                                    <li><a class="marked" id="summaryMarked" href="#">0</a> <?=$this->lang->line('take_exam_marked')?></li>
                                    <li><a class="notanswered" id="summaryNotAnswered" href="#">0</a> <?=$this->lang->line('take_exam_not_answer')?></li>
                                    <li><a class="notvisited" id="summaryNotVisited" href="#">0</a><?=$this->lang->line('take_exam_not_visited')?></li>
                                </ul>
                            </nav>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php } ?>
</div>


<div class="modal fade" id="submitExam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Submitting Answers</h4>
            </div>
            <div class="modal-body">
                <p>We are submitting your answers. Please wait for a while.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <img src=""/>
      </div>
    </div>
  </div>
</div>


<?php
$now = date("Y-m-d H:i:s");
$convertedEndTime = date("Y-m-d H:i:s", strtotime($onlineExam->endDateTime));
$duration = round(abs(strtotime($convertedEndTime) - strtotime($now)) / 60,2);
?>

<script type="text/javascript">

$("#imageModal").on("shown.bs.modal", function (e) { 
    var link = $(e.relatedTarget).data('link');
    $(this).find('.modal-body img').attr('src',link);
});

setInterval(checkInternet, 30000);
setInterval(saveTextareatext, 60000);

function saveTextareatext(){

    questionid = $('.step-content div.active .question-body').attr('data-questionID');
    typenumber = $('.step-content div.active .question-body').attr('data-questionTypeNumber');

    if(typenumber == 4 || typenumber == 5 ){
        var textValue = $("textarea[name='answer["+typenumber+"]["+questionid+"]']").val();
        if(textValue != ''){
            $.ajax({url: "<?=base_url('take_exam/store_temp_subjective_answers')?>", type: 'post', data: { examid: examid, questionid: questionid, typenumber: typenumber, answer: textValue}, success: function(result){
            }});
        }    
    }

}

function checkInternet() {
    $.ajax({
        url: "<?php echo base_url() ?>",
        type:'get',
        cache:false,
        timeout: 10000,
        error: function(x, t, m) {
            $('#prevbutton').prop('disabled', true);
            $('#nextbutton').prop('disabled', true);
            $('#finishedbutton').prop('disabled', true);
            $("a[id^=question]").addClass('disable-pointer');
            alert("There was an error. Please check your internet.");
        },
        success:function(data) {
            $('#prevbutton').prop('disabled', false);
            $('#nextbutton').prop('disabled', false);
            $('#finishedbutton').prop('disabled', false);
            $("a[id^=question]").removeClass('disable-pointer');
        }
    });
}


$('#prevbutton').on('click', function () {
    examid = $(this).attr("examid");

    questionid = $('.step-content div.active .question-body').attr('data-questionID');
    typenumber = $('.step-content div.active .question-body').attr('data-questionTypeNumber');

    if(typenumber == 1 || typenumber == 2){
        if(typenumber == 1) {
            var options = $("input[name='answer["+typenumber+"]["+questionid+"][]']:checked").val();
        }
        if(typenumber == 2) {
            var options_array = [];
                $.each($("input[name='answer["+typenumber+"]["+questionid+"][]']:checked"), function(){
                    options_array.push($(this).val());
                });
            options = options_array.join(", ");
        }

        $.ajax({url: "<?=base_url('take_exam/store_temp_answers')?>", type: 'post', data: { examid: examid, questionid: questionid, typenumber: typenumber, options: options}, success: function(result){
        }});

    }

    if(typenumber == 4 || typenumber == 5) {
        var textValue = $("textarea[name='answer["+typenumber+"]["+questionid+"]']").val();
        $.ajax({
            url: "<?=base_url('take_exam/store_temp_subjective_answers')?>",
            type: 'post',
            data: { examid: examid, questionid: questionid, typenumber: typenumber, answer: textValue},
            success: function(result){
            }
        });
    }

    if(typenumber == 3) {
        var options_array = [];
        $("input[name^='answer["+typenumber+"]["+questionid+"][']").each( function() {
            options_array.push($(this).val());
       });
       options = options_array.join(", ");
       $.ajax({url: "<?=base_url('take_exam/store_temp_answers')?>", type: 'post', data: { examid: examid, questionid: questionid, typenumber: typenumber, options: options}, success: function(result){
        }});
    }

    // if(options) {
    //     $('.step-content div.active').find('input').prop('disabled', true);
    // }

   
})



$('#nextbutton').on('click', function () {
    examid = $(this).attr("examid");
    questionid = $('.step-content div.active .question-body').attr('data-questionID');
    typenumber = $('.step-content div.active .question-body').attr('data-questionTypeNumber');

    if(typenumber == 1 || typenumber == 2){
        if(typenumber == 1) {
            var options = $("input[name='answer["+typenumber+"]["+questionid+"][]']:checked").val();
        }
        if(typenumber == 2) {
            var options_array = [];
                $.each($("input[name='answer["+typenumber+"]["+questionid+"][]']:checked"), function(){
                    options_array.push($(this).val());
                });
            options = options_array.join(", ");
        }

        $.ajax({url: "<?=base_url('take_exam/store_temp_answers')?>", type: 'post', data: { examid: examid, questionid: questionid, typenumber: typenumber, options: options}, success: function(result){
        }});

    }

    if(typenumber == 4 || typenumber == 5) {
        var textValue = $("textarea[name='answer["+typenumber+"]["+questionid+"]']").val();
        $.ajax({
            url: "<?=base_url('take_exam/store_temp_subjective_answers')?>",
            type: 'post',
            data: { examid: examid, questionid: questionid, typenumber: typenumber, answer: textValue},
            success: function(result){
            }
        });
    }

    if(typenumber == 3) {
        var options_array = [];
        $("input[name^='answer["+typenumber+"]["+questionid+"][']").each( function() {
            options_array.push($(this).val());
       });
       options = options_array.join(", ");
       $.ajax({url: "<?=base_url('take_exam/store_temp_answers')?>", type: 'post', data: { examid: examid, questionid: questionid, typenumber: typenumber, options: options}, success: function(result){
        }});
    }
    

    // if(options) {
    //     $('.step-content div.active').find('input').prop('disabled', true);
    // }
   
})

$('#finishedbuttonp').hide();

$('#prevbutton').hide();

 $('#reviewbutton').on('click', function () {
        marked = 1;
        $('#questionWizard').wizard('next');
    });

    $('#clearbutton').on('click', function () {
        clearAnswer();
    });

    $('#questionWizard').on('actionclicked.fu.wizard', function (evt, data) {
        totalQuestions = parseInt(totalQuestions);
        var steps = 0;
        if(data.direction == "next") {
            steps = data.step+1;
        } else {
            steps = data.step-1;
        }
        if(steps != 1) {
            $('#prevbutton').show();
        } else {
            $('#prevbutton').hide();
        }

        if(steps == totalQuestions) {
            $('#nextbutton').removeClass('oe-btn-answered');
            $('#nextbutton').addClass('oe-btn-notanswered');
            $('#nextbutton i').remove();
            $('#nextbutton').hide();
        } else if(steps == totalQuestions+1) {
            finished();
        } else {
            $('#nextbutton').removeClass('oe-btn-notanswered');
            $('#nextbutton').addClass('oe-btn-answered');
            $('#nextbutton i').remove();
            $('#nextbutton').append(' <i class="fa fa-angle-right"></i>');
            $('#nextbutton').show();
        }
        NowStep = steps;

        changeColor(data.step);
        summaryUpdate();
    });

    function summaryUpdate() {
        var summaryNotVisited = $('.questionColor li .notvisited').length;
        var summaryAnswered = $('.questionColor li .answered').length;
        var summaryMarked = $('.questionColor li .marked').length;
        var summaryNotAnswered = $('.questionColor li .notanswered').length;
        $('#summaryNotVisited').html(summaryNotVisited);
        $('#summaryAnswered').html(summaryAnswered);
        $('#summaryMarked').html(summaryMarked);
        $('#summaryNotAnswered').html(summaryNotAnswered);
    }

    function changeColor(stepID) {
        list = $('#answerForm #step'+stepID+' input ');
        list1 = $('#answerForm #step'+stepID+' textarea ');
        var have = 0;
        var result = $.each( list, function() {
            elementType = $(this).attr('type');
            if(elementType == 'radio' || elementType == 'checkbox') {
                if($(this).prop('checked')) {
                    have = 1;
                    return have;
                }
            } else if(elementType == 'text') {
                if($(this).val() != '') {
                    have = 1;
                    return have;
                }
            }else if(elementType == 'hidden') {
                if($(this).val() != '') {
                    have = 1;
                    return have;
                }
            }
        });
        var result = $.each( list1, function() {
            elementType = $(this).attr('data-type');
            if(elementType == 'textarea') {
                if($(this).val() != '') {
                    have = 1;
                    return have;
                }
            }
        });
        if(have) {
            $('#question'+stepID).removeClass('notvisited');
            $('#question'+stepID).removeClass('notanswered');
            $('#question'+stepID).removeClass('marked');
            $('#question'+stepID).addClass('answered');
        } else {
            $('#question'+stepID).removeClass('notvisited');
            $('#question'+stepID).removeClass('answered');
            if($('#question'+stepID).attr('class') != 'marked') {
                $('#question'+stepID).addClass('notanswered');
            }
        }

        if(marked) {
            marked = 0;
            if($('#question'+stepID).attr('class') != 'answered') {
                $('#question'+stepID).removeClass('notvisited');
                $('#question'+stepID).removeClass('notanswered');
                $('#question'+stepID).addClass('marked');
            }
        }
    }

    function changeColorInitial(stepID) {
        list = $('#answerForm #step'+stepID+' input ');
        list1 = $('#answerForm #step'+stepID+' textarea ');
        var have = 0;
        var result = $.each( list, function() {
            elementType = $(this).attr('type');
            if(elementType == 'radio' || elementType == 'checkbox') {
                if($(this).prop('checked')) {
                    have = 1;
                    return have;
                }
            } else if(elementType == 'text') {
                if($(this).val() != '') {
                    have = 1;
                    return have;
                }
            }else if(elementType == 'hidden') {
                if($(this).val() != '') {
                    have = 1;
                    return have;
                }
            }
        });
        var result = $.each( list1, function() {
            elementType = $(this).attr('data-type');
            if(elementType == 'textarea') {
                if($(this).val() != '') {
                    have = 1;
                    return have;
                }
            }
        });
        if(have) {
            $('#question'+stepID).removeClass('notvisited');
            $('#question'+stepID).removeClass('notvisited');
            $('#question'+stepID).removeClass('marked');
            $('#question'+stepID).addClass('answered');
        } else {
            $('#question'+stepID).removeClass('notvisited');
            $('#question'+stepID).removeClass('answered');
            if($('#question'+stepID).attr('class') != 'marked') {
                $('#question'+stepID).addClass('notvisited');
            }
        }

        if(marked) {
            marked = 0;
            if($('#question'+stepID).attr('class') != 'answered') {
                $('#question'+stepID).removeClass('notvisited');
                $('#question'+stepID).removeClass('notvisited');
                $('#question'+stepID).addClass('marked');
            }
        }
    }

    function jumpQuestion(questionNumber, onlineexam) {
        examid = onlineexam;
        questionid = $('.step-content div.active .question-body').attr('data-questionID');
        typenumber = $('.step-content div.active .question-body').attr('data-questionTypeNumber');

        if(typenumber == 1 || typenumber == 2){
        if(typenumber == 1) {
            var options = $("input[name='answer["+typenumber+"]["+questionid+"][]']:checked").val();
        }
        if(typenumber == 2) {
            var options_array = [];
                $.each($("input[name='answer["+typenumber+"]["+questionid+"][]']:checked"), function(){
                    options_array.push($(this).val());
                });
            options = options_array.join(", ");
        }

        $.ajax({url: "<?=base_url('take_exam/store_temp_answers')?>", type: 'post', data: { examid: examid, questionid: questionid, typenumber: typenumber, options: options}, success: function(result){
        }});

    }

    if(typenumber == 4 || typenumber == 5) {
        var textValue = $("textarea[name='answer["+typenumber+"]["+questionid+"]']").val();
        $.ajax({
            url: "<?=base_url('take_exam/store_temp_subjective_answers')?>",
            type: 'post',
            data: { examid: examid, questionid: questionid, typenumber: typenumber, answer: textValue},
            success: function(result){
            }
        });
    }

    if(typenumber == 3) {
        var options_array = [];
        $("input[name^='answer["+typenumber+"]["+questionid+"][']").each( function() {
            options_array.push($(this).val());
       });
       options = options_array.join(", ");
       $.ajax({url: "<?=base_url('take_exam/store_temp_answers')?>", type: 'post', data: { examid: examid, questionid: questionid, typenumber: typenumber, options: options}, success: function(result){
        }});
    }

        // if(options) {
        //     $('.step-content div.active').find('input').prop('disabled', true);
        // }
       


        if(typenumber == 4 || typenumber == 5) {
            var textValue = $("textarea[name='answer["+typenumber+"]["+questionid+"]']").val();
            $.ajax({
                url: "<?=base_url('take_exam/store_temp_subjective_answers')?>",
                type: 'post',
                data: { examid: examid, questionid: questionid, typenumber: typenumber, answer: textValue},
                success: function(result){
                }
            });
        }

        changeColor(NowStep);
        NowStep = questionNumber;
        $('#questionWizard').wizard('selectedItem', {
            step: questionNumber
        });

        if(questionNumber != 1) {
            $('#prevbutton').show();
        } else {
            $('#prevbutton').hide();
        }
        changeColor(questionNumber);
        if(questionNumber == totalQuestions) {
            $('#nextbutton').removeClass('oe-btn-answered');
            $('#nextbutton').addClass('oe-btn-notanswered');
            $('#nextbutton i').remove();
            $('#nextbutton').hide();
        } else {
            $('#nextbutton').removeClass('oe-btn-notanswered');
            $('#nextbutton').addClass('oe-btn-answered');
            $('#nextbutton i').remove();
            $('#nextbutton').append(' <i class="fa fa-angle-right"></i>');
            $('#nextbutton').show();
        }
        summaryUpdate();
    }

    function clearAnswer() {
        list = $('#answerForm #step'+NowStep+' input ');
        list1 = $('#answerForm #step'+NowStep+' textarea ');
        $.each( list, function() {
            elementType = $(this).attr('type');
            switch(elementType) {
                case 'radio': $(this).prop('checked', false); break;
                case 'checkbox': $(this).attr('checked', false); break;
                case 'text': $(this).val(''); break;
                case 'textarea': $(this).val(''); break;
            }
        });
        $.each( list, function() {
            elementType = $(this).data('type');
            switch(elementType) {
                case 'textarea': $(this).val(''); break;
            }
        });
        if($('#question'+NowStep).attr('class') == 'marked') {
            $('#question'+NowStep).removeClass('marked');
            $('#question'+NowStep).addClass('notanswered');
        }
    }

    function finished() {

        var allquestion = $('.questionColor li').length;
        var summaryAnswered = $('.questionColor li .answered').length;
        var summaryNotAnswered = allquestion - summaryAnswered;

        if(summaryNotAnswered == allquestion){
            var msg = 'Are you sure you want to finish this exam?';
        }else{
            var msg = summaryNotAnswered+' questions remaining to answer. Would you like to finish this exam?'
        }

        $('#finishedbutton').prop('disabled', true);
        $.ajax({
            url: "<?php echo base_url() ?>",
            type:'get',
            cache:false,
            timeout: 10000,
            error: function(x, t, m) {
                $('#prevbutton').prop('disabled', true);
                $('#nextbutton').prop('disabled', true);
                $('#finishedbutton').prop('disabled', true);
                $("a[id^=question]").addClass('disable-pointer');
                alert("There was an error. Please check your internet.");
            },
            success:function(data) {
                if(confirm(msg))
                {
                    $(this).attr('disabled',true);
                    $('#answerForm').submit(function(){
                        $("#answerForm :disabled").removeAttr('disabled');
                    });
                    $('#answerForm').submit();
                    $('#finishedbutton').prop('disabled', true);
                    $('#submitExam').modal('show');
                }else{
                    $('#finishedbutton').prop('disabled', false);
                }
            }
        });
        
    }

    function counter() {
        first_attempt = true;
        $cont = 0;
        setInterval(function() {
            durationUpdate();
             $cont++; 
             var cd = "<?php echo $currentDate ?>";
             var current_date  = new Date(cd);
                 current_date.setSeconds(current_date.getSeconds() + $cont);
            var enddate = new Date("<?=strtotime($onlineExam->endDateTime) ?>" * 1000);

            var t = enddate - current_date;
            var hours = Math.floor((t % (1000 * 60 * 60 * 24))/(1000 * 60 * 60));
            var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor(( t % (1000 * 60)) / 1000);
           
            if(t < 0) {
                $.ajax({
                    url: "<?php echo base_url(); ?>",
                    type:'get',
                    cache:false,
                    timeout: 10000,
                    error: function(x, t, m) {
                            alert("There was an error. Please check your internet.");
                    },
                    success:function(data) {
                        if(first_attempt == true) {
                            hours = 00;
                            minutes = 00;
                            seconds = 00;
                            $('#answerForm').submit(function(){
                                $("#answerForm :disabled").removeAttr('disabled');
                            });
                            $('#answerForm').submit();
                            $('#finishedbutton').prop('disabled', true);
                            $('#submitExam').modal('show');
                            first_attempt = false;
                        }
                    }
                });
                $('#timerdiv').html( '00' + ':' + '00' + ':' + '00');
                duration = (hours*60)+minutes;
            } else {
                $('#timerdiv').html( ((hours < 10) ? '0' + hours : hours) + ':' + ((minutes < 10) ? '0' + minutes : minutes) + ':' + ((seconds < 10) ? '0' + seconds : seconds ));
                duration = (hours*60)+minutes;
               
            }            
        }, 1000);
    }

    function durationUpdate() {
        hours = 0;
        minutes = duration;
        if(minutes > 60) {
            hours = parseInt(duration/60, 10);
            minutes = duration % 60;
        }
        --seconds;
        minutes = (seconds < 0) ? --minutes : minutes;
        if(minutes < 0 && hours != 0) {
            --hours;
            minutes = 59;
        }

        if(hours < 0) {
            hours = 0;
        }

        seconds = (seconds < 0) ? 59 : seconds;
        if (minutes < 0 && hours == 0) {
            minutes = 0;
            seconds = 0;
        }
    }

    function timeString() {
        return ((hours < 10) ? '0' + hours : hours) + ':' + ((minutes < 10) ? '0' + minutes : minutes) + ':' + ((seconds < 10) ? '0' + seconds : seconds );
    }

    var  startTimeDate = "<?=$onlineExam->startDateTime;?>";
    if(startTimeDate!='')
    {
       var duration = parseInt("<?=$duration?>");
    }
    else
    {
        var duration = parseInt("<?=$onlineExam->duration?>");
    }
    var totalQuestions = parseInt("<?=$countOnlineExamQuestions?>");
    var seconds = 1;
    var hours = 0;
    var minutes = -1;
    var NowStep = 1;
    var marked = 0;

    for (i = 1; i <= totalQuestions; i++) {
        changeColorInitial(i)
    }

    durationUpdate();
    $('.duration').html(timeString());
        counter();
    summaryUpdate();

    $('.sidebar-menu li a').css('pointer-events', 'none');

    function disableF5(e) {
        if ( ( (e.which || e.keyCode) == 116 ) || ( e.keyCode == 82 && e.ctrlKey ) ) {
            e.preventDefault();
        }
    }

    $(document).bind("keydown", disableF5);

    function Disable(event) {
        if (event.button == 2)
        {
            window.oncontextmenu = function () {
                return false;
            }
        }
    }

    document.onmousedown = Disable;


    $(document).keydown(function(event){
    if(event.keyCode==123){
        return false;
    }
    else if (event.ctrlKey && event.shiftKey && event.keyCode==73){        
             return false;
        }
    });

    $(document).on("contextmenu",function(e){        
        e.preventDefault();
    });

    if(totalQuestions == 1) {
        $('#nextbutton').removeClass('oe-btn-answered');
        $('#nextbutton').addClass('oe-btn-notanswered');
        $('#nextbutton i').remove();
        $('#finishedbutton').hide();
    }

    $('.js-chk').click(function(){
        var name1 = $(this).attr("name");
        var getclass = $(this).attr("title");
        var legh = $('input[name="'+name1+'"]:checked').length;
        if( legh > 0) {
            $("."+getclass).val('0');
         }
        else {
            $("."+getclass).val('0'); 
        } 
    });

    $(document).ready(function(){
        $('textarea').on("cut copy paste",function(e) {
            e.preventDefault();
        });
        $('input:text').bind('cut copy paste', function(e) {
            e.preventDefault();
       });
    });

    
</script>

<script>
$(document).ready(function(){

 $('.deleteEditFile').click(function(){
    deleteFile($(this));
 });
 
  $('input[type="file"]').change(function(){
    var obj = $(this);
    var typenumber = obj.data('type');
    var question = obj.data('question');
    var issubjective = obj.data('issubjective');
    var tableID = '#fileTable'+typenumber+question;

    var files = $(this)[0].files;
    var error = '';
    var form_data = new FormData();
   for(var count = 0; count<files.length; count++)
   {
        var name = files[count].name;
        var extension = name.split('.').pop().toLowerCase();
        if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
        {
            error += "Invalid " + count + " Image File"
        }
        else
        {
            form_data.append("files[]", files[count]);
        }
  }
  form_data.append('question',question);
  form_data.append('exam_id',<?=$onlineExamID ?>);
  form_data.append('is_subjective',issubjective);


  
  if(error == '')
  {
   $.ajax({
        url:"<?php echo base_url(); ?>take_exam/uploadImages", //base_url() return http://localhost/tutorial/codeigniter/
        method:"POST",
        data:form_data,
        contentType:false,
        cache:false,
        processData:false,
        beforeSend:function()
        {
            //  $('#uploaded_images').html("<label class='text-success'>Uploading...</label>");
        },
        success:function(jsonResult)
        {
            if(jsonResult){
                $.each(JSON.parse(jsonResult), function(i, item) {
                    var newTr = "";    
                    newTr += '<tr class="messages"><td>' + item.link + '</td> <td style="text-align:center;"><a href="javascript:void(0)" data-link="'+item.url+'" data-toggle="modal" data-target="#imageModal"<span class="label label-primary">View</span></a>&nbsp;<a href="javascript:void(0)" class="deleteFile" data-id="'+item.id+'"><span class="label label-danger">Delete</span></a></td>';
                    newTr += "</tr>";
                    $(tableID+' > tbody:last-child').append(newTr);
                });
                $('#files'+question).val(1);
                showToast('success');
                $('.deleteFile').click(function(){
                    deleteFile($(this));
                });
            }else{
                alert('Fail');
                // showToastError('Fail.');
            }  
        }
   });
  }
  else
  {
   alert(error);
   $(this).val('');
  }
 });



  function deleteFile(obj){
    var id = obj.data('id');
        if(confirm("Are you sure?")){
            $.ajax({
                url: "<?=base_url('take_exam/deleteSubjectiveAnswerFile')?>",
                type: 'get',
                data: { id: id},
                success: function(result){
                   if(result){
                     obj.parent().parent().remove();
                   }
                }
            });
        }
  }

});
</script>



