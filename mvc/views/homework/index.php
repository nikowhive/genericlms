<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-assignment"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i><?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_homework')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if((($siteinfos->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) || ($this->session->userdata('usertypeID') != 3)) { ?>
                    <h5 class="page-header">
                        <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) { ?>
                            <?php if(permissionChecker('homework_add')) { ?>
                                <a href="<?php echo base_url('homework/add') ?>">
                                    <i class="fa fa-plus"></i> 
                                    <?=$this->lang->line('add_title')?>
                                </a>
                            <?php } ?>
                        <?php } ?>
                        <?php if($this->session->userdata('usertypeID') != 3) { ?>
                            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                                <?php
                                    $array = array("0" => $this->lang->line("homework_select_classes"));
                                    if(customCompute($classes)) {
                                        foreach ($classes as $classa) {
                                            $array[$classa->classesID] = $classa->classes;
                                        }
                                    }
                                    echo form_dropdown("classesID", $array, set_value("classesID", $set), "id='classesID' class='form-control select2'");
                                ?>
                            </div>
                        <?php } ?>
                    </h5>
                <?php } ?>

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('homework_title')?></th>
                                <th class="col-lg-3"><?=$this->lang->line('homework_description')?></th>
                                <th><?=$this->lang->line('homework_deadlinedate')?></th>
                                <th><?=$this->lang->line('homework_section')?></th>
                                <th><?=$this->lang->line('homework_uploder')?></th>
                                <th><?=$this->lang->line('homework_file')?></th>
                                <?php if(permissionChecker('homework_edit') || permissionChecker('homework_delete') || permissionChecker('homework_view')) { ?>
                                <th><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($homeworks)) {$i = 1; foreach($homeworks as $homework) {
                                if(($this->session->userdata('usertypeID') == 3) && customCompute($student) && in_array($homework->subjectID, $opsubjects) && ($student->sroptionalsubjectID != $homework->subjectID)) {
                                    continue;
                                } ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('homework_title')?>">
                                        <?php echo $homework->title; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('homework_description')?>">
                                        <?php echo namesorting($homework->description, 130); ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('homework_deadlinedate')?>">
                                        <?php echo date('d M Y', strtotime($homework->deadlinedate)); ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('homework_section')?>">
                                        <?php  
                                        if($homework->sectionID == 'false') {
                                            if(customCompute($sections)) foreach ($sections as $section) {
                                                echo $this->lang->line('homework_section').' '.$section.'<br>';
                                            }
                                        } else {
                                            $dbSections = json_decode($homework->sectionID);
                                            if(customCompute($dbSections)) foreach ($dbSections as $dbSectionID) {
                                                echo $this->lang->line('homework_section').' '. $sections[$dbSectionID].'<br>';
                                            } 
                                        }
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('homework_uploder')?>">
                                        <?php echo getNameByUsertypeIDAndUserID($homework->usertypeID, $homework->userID); ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('homework_file')?>">
                                        <?php 
                                            if($homework->originalfile) { 
                                                echo btn_download_file('homework/download/'.$homework->homeworkID, namesorting($homework->originalfile), $this->lang->line('download')); 
                                            }
                                        ?>
                                    </td>
                                    <?php if(permissionChecker('homework_edit') || permissionChecker('homework_delete') || permissionChecker('homework_view')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php if($this->session->userdata('usertypeID') == 3) {
                                            if($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) { 
                                                echo btn_upload('homework/homeworkanswer/'.$homework->homeworkID.'/'.$set, $this->lang->line('upload'));
                                            } }
                                            echo btn_view('homework/view/'.$homework->homeworkID.'/'.$set, $this->lang->line('view'));

                                            if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { 
                                                echo btn_edit('homework/edit/'.$homework->homeworkID.'/'.$set, $this->lang->line('edit'));
                                                echo btn_delete('homework/delete/'.$homework->homeworkID.'/'.$set, $this->lang->line('delete'));
                                            } ?>
                                    </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".select2").select2();
    $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#hide-table').hide();
            $('.nav-tabs-custom').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('homework/student_list')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>
