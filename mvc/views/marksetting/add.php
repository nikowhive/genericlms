<div class="container container--sm">

<header class="pg-header mt-4">
        <div>
            
            <h1 class="pg-title">
            
                <?=$this->lang->line('panel_title')?>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>">  <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("marksettings/index")?>"></i> <?=$this->lang->line('menu_marksettings')?></a></li>
                    <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_marksettings')?></li>
                </ol>
        </div>
</header>
    <div class="card card--spaced">
 
        <!-- form start -->
        <div class="card-body">
  
                    <form class="" role="form" method="post">
    
                        <?php if(($siteinfos->mark==1) || ($siteinfos->exam==1)) { ?>
                            <div class='form-group <?=form_error('classesID') ? 'has-error' : ''?>'>
                                <label for="classesID" class="col-sm-2 control-label">
                                    <?=$this->lang->line("marksettings_classes")?> <span class="text-red">*</span>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $classesArray[0] = $this->lang->line("marksettings_select_class");
                                        if(customCompute($classes)) {
                                            foreach ($classes as $class) {
                                                $classesArray[$class->classesID] = $class->classes;
                                            }
                                        }
                                        echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                                    ?>
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('classesID'); ?>
                                </span>
                            </div>
                        <?php } ?>
    
                        <?php if($siteinfos->mark==2) { ?>
                            <div class='form-group <?=form_error('examID') ? 'has-error' : ''?>'>
                                <label for="examID" class="col-sm-2 control-label">
                                    <?=$this->lang->line("marksettings_exam")?> <span class="text-red">*</span>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $examArray[0] = $this->lang->line("marksettings_select_class");
                                        if(customCompute($exams)) {
                                            foreach ($exams as $exam) {
                                                $examArray[$exam->examID] = $exam->exam;
                                            }
                                        }
                                        echo form_dropdown("examID", $examArray, set_value("examID"), "id='examID' class='form-control select2'");
                                    ?>
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('examID'); ?>
                                </span>
                            </div>
                        <?php } ?>
    
                        <div class='form-group <?=form_error('markpercentages[]') ? 'has-error' : ''?>'>
                            <label for="markpercentages" class="col-sm-2 control-label">
                                <?=$this->lang->line("marksettings_markpercentages")?> <span class="text-red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <?php
                                    $markpercentagesArray = [];
                                    if(customCompute($markpercentages)) {
                                        foreach($markpercentages as $markpercentage) {
                                            $markpercentagesArray[$markpercentage->markpercentageID] = $markpercentage->markpercentagetype. ' ('.$markpercentage->percentage.')';
                                        }
                                    }
                                    echo form_multiselect("markpercentages[]", $markpercentagesArray, set_value("markpercentages"), "id='markpercentages' class='form-control select2'");
                                ?>
                            </div>
                            <span class="col-sm-4 control-label">
                                <?php echo form_error('markpercentages[]'); ?>
                            </span>
                        </div>
    
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_mark_settings")?>" >
                            </div>
                        </div>
    
                    </form>
                 
        </div>
    </div>
</div>


<script type="text/javascript">
    $('.select2').select2();
</script>