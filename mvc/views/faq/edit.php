<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<div class="right-side--fullHeight  ">

    <div class="row w-100 ">
        <?php $this->load->view("components/course_menu"); ?>

        <div class="<?php echo isset($course) ? 'course-content': 'col-md-12' ?>">        
            <div class="container container--sm">

            <header class="pg-header mt-4">
                <h1 class="pg-title">
                    <?= $this->lang->line('panel_title') ?>
                </h1>
           </header>
                <div class="card card--spaced"  >
                    
                    <!-- form start -->
                    <div class="card-body">
                    
                            <form class="" enctype="multipart/form-data" role="form" method="post" id="add_homework">

                                <?php 
                                        if(form_error('question')) 
                                            echo "<div class='form-group has-error' >";
                                        else     
                                            echo "<div class='form-group' >";
                                    ?>
                                         <label for="question" class="active"><?=$this->lang->line("faq_question")?> <span class="text-red">*</span></label>
                                            <div class="md-form">                                    
                                                <textarea class="md-textarea form-control"  id="question" name="question"><?=set_value('question',$faq->question)?></textarea>
                                               
                                                <span class="text-danger error">
                                                    <?php echo form_error('question'); ?> 
                                                </span>
                                                
                                            </div>          
                                    </div>

                                    <?php 
                                        if(form_error('answer')) 
                                            echo "<div class='form-group has-error' >";
                                        else     
                                            echo "<div class='form-group' >";
                                    ?>
                                         <label for="answer" class="active"><?=$this->lang->line("faq_answer")?> <span class="text-red">*</span></label>
                                            <div class="md-form">                                    
                                                <textarea class="md-textarea form-control"  id="description" name="answer"><?=set_value('answer', $faq->answer)?></textarea>
                                               
                                                <span class="text-danger error">
                                                    <?php echo form_error('answer'); ?> 
                                                </span>
                                                
                                            </div>          
                                    </div>

                                    <?php 
                                       if(form_error('classesID'))
                                           echo "<div class='form-group has-error' >";
                                       else
                                           echo "<div class='form-group' >";
                                   ?>
                                       <label for="classesID" class="active">
                                           <?=$this->lang->line("faq_class")?> <span class="text-red">*</span>
                                       </label>
                                       <div class="col-sm">
                                           <?php
                                               $array = [];
                                               if(customCompute($classes)) {
                                                   foreach ($classes as $class) {
                                                       $array[$class->classesID] = $class->classes;
                                                   }
                                               }
                                               echo form_multiselect("classesID[]", $array, set_value("classesID",$faq_class), "id='classesID' class='form-control select2'");
                                           ?>
                                       </div>
                                       <span class="text-danger-error">
                                           <?php echo form_error('classesID'); ?>
                                       </span>
                                   </div> 
                            
                                <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_faq")?>" >
                                  
                            </form>
                    </div>        
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(".select2" ).select2();
</script>
