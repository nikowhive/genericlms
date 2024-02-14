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
                    <div class="card-body">
                    
                        <form class="" enctype="multipart/form-data" role="form" method="post">
                                
                                    <?php 
                                        if(form_error('title')) 
                                            echo "<div class='form-group has-error' >";
                                        else     
                                            echo "<div class='form-group' >";
                                    ?>
                                    
                                        <div class="md-form">
                                            <label for="title"> <?=$this->lang->line("enrollment_title")?> <span class="text-red">*</span></label>
                                            <input type="text" placeholder="Enter enrolment title here..." class="form-control" id="title" name="title" value="<?=set_value('title')?>" >
                                            
                                                
                                             <span class="text-danger error">
                                                <?php echo form_error('title'); ?> 
                                            </span>
                                        </div>
                                        
                                    </div>

                                    <?php 
                                        if(form_error('from_month')) 
                                            echo "<div class='form-group has-error' >";
                                        else     
                                            echo "<div class='form-group' >";
                                    ?>

                                        <div class="md-form">                                     
                                            <input type="text" autocomplete="off" class="form-control" id="from_month" name="from_month" value="<?=set_value('from_month') ? set_value('from_month') : date("m-Y") ?>" >
                                             <label for="from_month"> 
                                                  <?=$this->lang->line("enrollment_from_month")?> <span class="text-red">*</span>
                                            </label>
                                            <span class="text-danger error">
                                                 <?php echo form_error('from_month'); ?> 
                                             </span>
                                        </div>
                                    </div>

                                    <?php 
                                        if(form_error('to_month')) 
                                            echo "<div class='form-group has-error' >";
                                        else     
                                            echo "<div class='form-group' >";
                                    ?>
   
                                        <div class="md-form">                                     
                                            <input type="text" autocomplete="off" class="form-control" id="to_month" name="to_month" value="<?=set_value('to_month') ? set_value('to_month') : date("m-Y") ?>" >
                                             <label for="to_month"> 
                                                  <?=$this->lang->line("enrollment_to_month")?> <span class="text-red">*</span>
                                            </label>
                                            <span class="text-danger error">
                                            <?php echo form_error('to_month'); ?> 
                                            </span>
                                        </div>
                                    </div>

                                    <?php 
                                        if(form_error('start_date')) 
                                            echo "<div class='form-group has-error' >";
                                        else     
                                            echo "<div class='form-group' >";
                                    ?>
   
                                        <div class="md-form">                                     
                                            <input type="text" autocomplete="off" class="form-control" id="start_date" name="start_date" value="<?=set_value('start_date') ? set_value('start_date') : date("d-m-Y") ?>" >
                                             <label for="start_date"> 
                                                  <?=$this->lang->line("enrollment_start_date")?> <span class="text-red">*</span>
                                            </label>
                                            <span class="text-danger error">
                                            <?php echo form_error('start_date'); ?> 
                                            </span>
                                        </div>
                                    </div>

                                    <?php 
                                        if(form_error('classesID')) 
                                            echo "<div class='form-group has-error' >";
                                        else     
                                            echo "<div class='form-group' >";
                                    ?>
                                    
                                        <div class="col-sm">
                                            <label for="classesID" >
                                                <?=$this->lang->line("enrollment_class")?> <span class="text-red">*</span>
                                            </label>
                                            <?php
                                                $array = array();

                                                foreach ($classes as $classa) {
                                                    $array[$classa->classesID] = $classa->classes;
                                                }

                                                echo form_multiselect("classesID[]", $array, set_value("classesID", $classesID), "id='classesID' class='form-control select2'");
                        
                                            ?>
                                                
                                            <span class="text-danger error ">
                                                <?php echo form_error('classesID'); ?>
                                            </span>
                                        </div>
                                    </div>
                                  
                                   <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_enrollment")?>" >
                                
                        </form>
                            
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

<script>
$(".select2" ).select2();

$("#from_month").datepicker( {
        format: "mm-yyyy",
        startView: "months", 
        minViewMode: "months"
    });
    $("#to_month").datepicker( {
        format: "mm-yyyy",
        startView: "months", 
        minViewMode: "months"
    });
    $('#start_date').datepicker({
        dateFormat: 'dd-mm-yy',
        
    });

</script>
