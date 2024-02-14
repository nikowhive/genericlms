<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-slideshare"></i> <?= $this->lang->line('panel_title') ?></h3>
        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li class="active"><?= $this->lang->line('panel_title') ?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if (($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                    <?php if (permissionChecker('online_exam_add')) { ?>
                        <h5 class="page-header">
                            <a href="<?php echo base_url('online_exam/add') ?>">
                                <i class="fa fa-plus"></i>
                                <?= $this->lang->line('add_title') ?>
                            </a>
                            <a href="<?php echo base_url('exam_setting/add') ?>" style="margin-left: 20px;">
                                <i class="fa fa-plus"></i>
                                <?= $this->lang->line('online_exam_add_setting') ?>
                            </a>
                        </h5>
                    <?php } ?>
                <?php } ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?= $this->lang->line('slno') ?></th>
                                <th class="col-sm-3"><?= $this->lang->line('online_exam_name') ?></th>
                                <th class="col-sm-1"><?= $this->lang->line('online_exam_published') ?></th>
                                <th class="col-sm-1"><?= $this->lang->line('online_exam_subject') ?></th>
                                <th class="col-sm-1"><?= $this->lang->line('online_exam_class') ?></th>
                                <th class="col-sm-1"><?= $this->lang->line('online_exam_startdatetime') ?></th>
                                <th class="col-sm-1"><?= $this->lang->line('online_exam_enddatetime') ?></th>
                                <th class="col-sm-1"><?= $this->lang->line('online_exam_time') ?></th>
                                <?php if (($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                                    <?php if (permissionChecker('online_exam_edit') || permissionChecker('online_exam_delete') || permissionChecker('online_exam_view')) { ?>
                                        <th class="col-sm-2"><?= $this->lang->line('action') ?></th>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (customCompute($online_exams)) {
                                $i = 0;
                                foreach ($online_exams as $__index => $online_exam) {
                                    $showStatus = FALSE;
                                    if ($usertypeID == '3') {
                                        if (customCompute($student)) {
                                            if ((($student->srclassesID == $online_exam->classID) || ($online_exam->classID == '0')) && (($student->srsectionID == $online_exam->sectionID) || ($online_exam->sectionID == '0')) && (($student->srstudentgroupID == $online_exam->studentGroupID) || ($online_exam->studentGroupID == '0')) && ($online_exam->published == 1)) {

                                                if (isset($opsubject[$online_exam->subjectID])) {
                                                    if ($online_exam->subjectID == $student->sroptionalsubjectID) {
                                                        $showStatus = TRUE;
                                                        $i++;
                                                    }
                                                } else {
                                                    $showStatus = TRUE;
                                                    $i++;
                                                }
                                            }
                                        }
                                    } else {
                                        $i++;
                                        $showStatus = TRUE;
                                    }

                                    if ($showStatus) { ?>
                                        <tr>
                                            <td data-title="<?= $this->lang->line('slno') ?>">
                                                <?php echo $i; ?>
                                            </td>
                                            <td data-title="<?= $this->lang->line('online_exam_name') ?>">
                                                <?php
                                                if (strlen($online_exam->name) > 25)
                                                    echo strip_tags(substr($online_exam->name, 0, 25) . "...");
                                                else
                                                    echo strip_tags(substr($online_exam->name, 0, 25));
                                                ?>
                                            </td>
                                            <td data-title="<?= $this->lang->line('online_exam_published') ?>">
                                                <?php if (permissionChecker('online_exam_edit')) { ?>
                                                    <form method="post" action="<?php echo base_url() ?>online_exam/postChangeExamStatus/<?php echo $online_exam->onlineExamID; ?>">
                                                        <div class="onoffswitch-small">
                                                            <input type="checkbox" class="onoffswitch-small-checkbox" name="published" <?php if ($online_exam->published == '1') { ?> checked='checked' <?php }
                                                                                                                                                                                                    if ($online_exam->published == '1')  echo "value='2'";
                                                                                                                                                                                                    else echo "value='1'"; ?>>
                                                            <label for="myonoffswitch" class="onoffswitch-small-label">
                                                                <span class="onoffswitch-small-inner"></span>
                                                                <span class="onoffswitch-small-switch"></span>
                                                            </label>
                                                        </div>
                                                    </form>

                                                <?php } else {
                                                    if ($online_exam->published == '1') {
                                                        echo "<span class='btn btn-success btn-xs'>" . $this->lang->line('online_exam_yes') . "</span>";
                                                    } else {
                                                        echo "<span class='btn btn-danger btn-xs'>" . $this->lang->line('online_exam_no') . "</span>";
                                                    }
                                                } ?>
                                            </td>
                                            <td data-title="Subject">
                                                <?= $online_exam->subject ? $online_exam->subject->subject : ''; ?>
                                            </td>
                                            <td data-title="Class">
                                                <?= $online_exam->class ? $online_exam->class->classes : ''; ?>
                                            </td>
                                            <td data-title="Start Date">
                                                <?php echo $online_exam->startDateTime; ?>
                                            </td>
                                            <td data-title="End Date">
                                                <?php echo $online_exam->endDateTime; ?>
                                            </td>
                                            <td data-title="Days Remaining">
                                                <?php echo $days_remaining[$__index]; ?>
                                            </td>
                                            <?php if (($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                                                <?php if (permissionChecker('online_exam_edit') || permissionChecker('online_exam_delete') || permissionChecker('online_exam_view')) { ?>
                                                    
                                                    <td data-title="<?= $this->lang->line('action') ?>">
                                                    <?php if(($online_exam->create_userID == $this->session->userdata('loginuserID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                                                        <?php echo btn_extra('online_exam/addquestion/' . $online_exam->onlineExamID, $this->lang->line('addquestion'), 'online_exam_add') ?>
                                                        <?php echo btn_edit('online_exam/edit/' . $online_exam->onlineExamID, $this->lang->line('edit')) ?>
                                                        <?php echo btn_delete('online_exam/delete/' . $online_exam->onlineExamID, $this->lang->line('delete')) ?>
                                                        <?php
                                                        $this->load->model('Online_exam_user_status_m');
                                                        $CI = &get_instance();
                                                        // $subchk =  $CI->Online_exam_user_status_m->checksubjective($online_exam->onlineExamID);
                                                        // if($subchk > 0)
                                                        // {<?php echo base_url('online_exam/togglepublish/' . $online_exam->onlineExamID) ?
                                                        ?>
                                                        <a href="<?php echo base_url('online_exam/togglepublish/' . $online_exam->onlineExamID) ?>" onclick=" return AutoPublish(<?= $online_exam->auto_published ?>); " class="btn btn-info btn-xs mrg waves-effect waves-light" data-placement="top" data-toggle="tooltip" data-original-title="<?= !$online_exam->auto_published ? 'Result Enable Auto publish' : 'Result Disable Auto publish' ?>"><i class="fa fa-eye"></i></a>
                                                        <a href="<?php echo base_url('online_exam/publishresult/' . $online_exam->onlineExamID) ?>" onclick=" return ResultPublish(<?= $online_exam->result_published ?>); " class="btn btn-warning btn-xs mrg waves-effect waves-light" data-placement="top" data-toggle="tooltip" data-original-title="<?= !$online_exam->result_published ? 'Publish Result' : 'Publish Again' ?>"><i class="fa fa-bolt"></i></a>
                                                        <!-- todo: hide if exam not expired -->
                                                        <!-- <a href="<?php echo base_url('online_exam/togglepublish/' . $online_exam->onlineExamID) ?>" onclick=" return AutoPublish(<?= $online_exam->auto_published ?>); " class="btn btn-success btn-xs mrg"></a>
                                                        <a href="<?php echo base_url('online_exam/publishresult/' . $online_exam->onlineExamID) ?>" onclick=" return ResultPublish(<?= $online_exam->result_published ?>); " class="btn btn-success btn-xs mrg"><?= !$online_exam->result_published ? 'Publish Result' : 'Publish Again' ?></a> -->
                                                        <?php //} 
                                                        ?>


                                                        <?php if (!isset($has_questions[$online_exam->onlineExamID])) { ?>
                                                            <form method="post" action="<?php echo base_url("online_exam/generateRandomQuestions") ?>">
                                                                <div>
                                                                     <div class="form-group">
                                                                        <br>
                                                                        <select name="exam_setting_id" class="form-control select2" required>
                                                                            <option value="">Select Settings</option>
                                                                            <?php if (isset($exam_settings[$online_exam->subjectID])) {
                                                                                foreach ($exam_settings[$online_exam->subjectID] as $s) { ?>
                                                                                    <option value="<?php echo $s['id'] ?>"><?php echo $s['setting_name'] ?></option>
                                                                                <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <input type="hidden" name="exam_id" value="<?php echo $online_exam->onlineExamID; ?>">
                                                                    <input type="submit" style="margin-top:10px;" value="Generate Question" class="btn btn-success btn-xs mrg">
                                                                <?php }  ?>
                                                                </div>
                                                            </form>

                                                        <?php } ?>
                                                    <?php } ?>

                                                        </td>
                                                <?php } ?>

                                            <?php } ?>
                                        </tr>
                            <?php }
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function AutoPublish(value) {
        if (value == 1) {
            return confirm('Are you sure you want to auto publish?');
        } else {
            return confirm('Are you sure you want to auto unpublish?');
        }
    }

    function ResultPublish(value) {

        if (value == 0) {
            return confirm('Are you sure you want to publish result?');
        } else {
            return confirm('Are you sure you want to publish result again?');
        }


    }


    $('.onoffswitch-small').click(function(e) {
        $(this).parent().submit();
    })
</script>