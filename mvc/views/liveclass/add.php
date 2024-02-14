<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-video-camera"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("liveclass/index")?>"><?=$this->lang->line('menu_liveclass')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_liveclass')?></li>
        </ol>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <?php
                        if(form_error('title'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="title" class="col-sm-2 control-label">
                            <?=$this->lang->line("liveclass_title")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="title" name="title" value="<?=set_value('title')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('title'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('date'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="date" class="col-sm-2 control-label">
                            <?=$this->lang->line("liveclass_date")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input autocomplete="false" type="text" class="form-control datetimepicker" id="date" name="date" value="<?=set_value('date')?>">
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('date'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('duration'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="duration" class="col-sm-2 control-label">
                            <?=$this->lang->line("liveclass_duration")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="duration" name="duration" value="<?=set_value('duration')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('duration'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('classesId'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="classesId" class="col-sm-2 control-label">
                            <?=$this->lang->line('liveclass_classes')?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $classArray[0] = $this->lang->line("liveclass_select_classes");
                                if(customCompute($classes)) {
                                    foreach ($classes as $classa) {
                                        $classArray[$classa->classesID] = $classa->classes;
                                    }
                                }
                                echo form_dropdown("classesId", $classArray, set_value("classesId"), "id='classesId' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classesId'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('sectionId'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="sectionId" class="col-sm-2 control-label">
                            <?=$this->lang->line("liveclass_section")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $sectionArray[0] = $this->lang->line("liveclass_select_section");
                                if(customCompute($sections)) {
                                    foreach ($sections as $section) {
                                        $sectionArray[$section->sectionID] = $section->section;
                                    }
                                }
                                echo form_dropdown("sectionId", $sectionArray, set_value("sectionId"), "id='sectionId' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('sectionId'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('subjectId'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subjectId" class="col-sm-2 control-label">
                            <?=$this->lang->line("liveclass_subject")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $subjectArray[0] = $this->lang->line("liveclass_select_subject");
                                if(customCompute($subjects)) {
                                    foreach ($subjects as $subject) {
                                        $subjectArray[$subject->subjectID] = $subject->subject;
                                    }
                                }
                                echo form_dropdown("subjectId", $subjectArray, set_value("subjectId"), "id='subjectId' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subjectId'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('teacher_join_url'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="title" class="col-sm-2 control-label">
                            <?=$this->lang->line("liveclass_teacher_join_url")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="teacher_join_url" name="teacher_join_url" value="<?=set_value('teacher_join_url')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('teacher_join_url'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('reminder'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="title" class="col-sm-2 control-label">
                            <?=$this->lang->line("liveclass_reminder")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="number" min="5" class="form-control" id="reminder" name="reminder" value="<?=set_value('reminder',5)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('reminder'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_liveclass")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

