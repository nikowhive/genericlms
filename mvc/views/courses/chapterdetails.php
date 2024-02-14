<style type="text/css">
    .fuelux .wizard .step-content {
        border: 0px;
    }
</style>
<button id="course_button">Content</button>
<button id="question_button">Quiz</button>

<div class="row">
    <div class="col-sm-7 fu-example section">
        <div id="course_content">
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
            

                <div class="box-body step-content">
                    <input style="display:none" type="text" name="studentfinishstatus">
                <?php
                if($content) {
                    foreach ($content as $cont) { ?>
                            <div class="clearfix step-pane sample-pane">
                                <div class="question-body">
                                    <label class="lb-title"><?=$cont->content_title?></label>
                                    <label class="lb-content"> <?=$cont->chapter_content ?></label>
                                    <label class="lb-mark">  </label>
                                </div>
                                <div class="question-answer">
                                </div>                                
                            </div>
                            <?php
                        }
                } else {
                    echo "<p class='text-center'>No content</p>";
                } 
                ?>
            </div>
        </div>
        </div>

        <!-- question section -->
        <div id="course_question">
        <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                               
                                    <th class="col-lg-2">Quiz</th>
                                    <th class="col-lg-2">Percentage Coverage</th>
                                    <th class="col-lg-3">Quiz Details</th>
                                    <th class="col-lg-2">Status</th>
                                    <th class="col-lg-2">Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                if($quizzes){
                    ?>
                    <?php foreach($quizzes as $quiz){?>
                    <tr> 
                    <td><?php echo $quiz->quiz_name;?></td>
                    <td><?php echo $quiz->percentage_coverage;?></td>
                    <td><table class="table-bordered">

                        <?php $questions = $this->coursequiz_m->get_bycoursequiz($quiz->id);

                            if($questions){
                                foreach($questions as $question){
                        ?>
                        <tr><td><?php echo $question->explanation;?></td><td><?php echo $question->question;?></td></tr>
                        <?php }} else {?>
                            <tr>There are no records</tr>
                        <?php }?>
                    </table></td>
                    <td><form method="post" action="<?php echo base_url() ?>courses/postChangeQuizStatus/<?php echo $quiz->id.'/'.$quiz->coursechapter_id; ?>">
                        <div class="onoffswitch-small">
                            <input type="checkbox"  class="onoffswitch-small-checkbox" name="published" <?php if($quiz->published == '1') { ?> checked='checked' <?php } if($quiz->published == '1')  echo "value='2'";  else echo "value='1'"; ?>>
                            <label for="myonoffswitch" class="onoffswitch-small-label">
                                <span class="onoffswitch-small-inner"></span>
                                <span class="onoffswitch-small-switch"></span>
                            </label>
                        </div>
                    </form>
                    </td><td><div style="display:inline-block;"><?php echo anchor('courses/deletequiz/'.$quiz->id.'/'.$quiz->coursechapter_id, "<i class='fa fa-trash-o'></i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'"); ?></div>
                    <div style="display:inline-block;"><?php echo anchor('courses/editquiz/'.$quiz->id.'/'.$quiz->coursechapter_id, "<i class='fa fa-edit'></i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Edit'"); ?></div>
                    <div style="display:inline-block;"><?php echo anchor('courses/addquestion/'.$quiz->id.'/'.$quiz->coursechapter_id, "<i class='fa fa-plus'></i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Add Question'"); ?></div><td></tr>
                <?php }
                }else{?><tr> <?php echo "There are no Quizzes";?></tr><?php }?>
                        </tbody>
                    </table>
        <!-- end question section -->
        </div>
    </div>

    <div class="col-sm-5">
        <div class="row">
        <div class="col-sm-12 counterDiv">

            <div class="col-sm-12">
                <div class="box outheBoxShadow">
                    <div class="box-body outheMargAndBox">
                        <div class="box-header bg-white">
                            <h3 class="box-title fontColor">
                                Attachments
                                <br>
                            </h3>
                        </div>

                        <div class="box-body margAndBox" style="">
                            <nav aria-label="Page navigation">
                                <ul class="examQuesBox questionColor">
                                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                               
                                                    <th class="col-lg-2">File Name</th>
                                                    <th class="col-lg-2">Status</th>
                                                    <th class="col-lg-3">Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php 
                                    if($attachment){
                                        foreach($attachment as $att){?> 
                                        <tr>    
                                        <td><a href="<?php echo base_url().'uploads/images/'.$att->attachment;?>" target="_blank"><?=$att->file_name?></a></td>
                                         <td><form method="post" action="<?php echo base_url() ?>courses/postChangeFileStatus/<?php echo $att->id.'/'.$att->coursechapter_id; ?>">
                                        <div class="onoffswitch-small">
                                            <input type="checkbox"  class="onoffswitch-small-checkbox" name="published" <?php if($att->published == '1') { ?> checked='checked' <?php } if($att->published == '1')  echo "value='2'";  else echo "value='1'"; ?>>
                                            <label for="myonoffswitch" class="onoffswitch-small-label">
                                                <span class="onoffswitch-small-inner"></span>
                                                <span class="onoffswitch-small-switch"></span>
                                            </label>
                                        </div>
                                    </form>
                                    </td>
                                    <td><?php echo anchor('courses/deletefile/'.$att->id.'/'.$att->coursechapter_id, "<i class='fa fa-trash-o'></i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'"); ?></td></tr>
                                    <?php }
                                    } else { ?><tr> <?php echo "There are no attachment";?></tr><?php }?>
                                </ul>
                            </nav>
                        </div>
                        <br/><br/>
                        <div class="row">
                        <div class="box-header bg-white">
                            <h3 class="box-title fontColor">
                                Links
                                <br>
                            </h3>
                        </div>

                        <div class="box-body margAndBox" style="">
                            <nav aria-label="Page navigation">
                                <ul class="examQuesBox questionColor">
                                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                               
                                                    <th class="col-lg-2">Link</th>
                                                    <th class="col-lg-2">Type</th>
                                                    
                                                    <th class="col-lg-3">Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php 
                                    if($links){
                                        foreach($links as $link){?> 
                                        <tr>    
                                        <td><a href="<?php echo $link->courselink;?>" target="_blank"><?=$link->courselink?></a></td>
                                        <td><?php echo $link->type?></td>
                                         
                                    <td><?php echo anchor('courses/deletelink/'.$link->id.'/'.$link->coursechapter_id, "<i class='fa fa-trash-o'></i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'"); ?></td></tr>
                                    <?php }
                                    } else { ?><tr> <?php echo "There are no link";?></tr><?php }?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
 <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
  <script id="MathJax-script" async
          src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js">
  </script>
<script type="text/javascript">


    $(document).ready(function(){

        $("#course_question").hide();

        $("#course_button").click(function(){
            $("#course_content").show();
            $("#course_question").hide();
        });

        $("#question_button").click(function(){
            $("#course_content").hide();
            $("#course_question").show();
        });
    });

    $('.onoffswitch-small').click(function(e) {
        $(this).parent().submit();  
    })
</script>
