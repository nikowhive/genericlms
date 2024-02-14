<div class="right-side--fullHeight  ">

    <div class="row w-100 ">

        <?php $this->load->view("components/course_menu"); ?>
        <div class="course-content">
            <div class="container container--sm">
                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                        <div>
                            <small>Homework</small>
                        </div>
                        <?php echo $course->classes . ' ' . $course->subject; ?>
                    </h1>
                    <?php if ($siteinfos->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') != 3) { ?>
                        <?php if (permissionChecker('homework_add')) { ?>
                            <a href="<?= base_url('homework/add?course=') . $course->id . '&link=' . 'homework' ?>" class="btn-sm btn btn-success" title="Create new homework"><i class="fa fa-plus"></i> Create</a>
                        <?php } ?>
                    <?php } ?>
                </header>


                <div class="sortable-list">
                    <ul id="unit" class="course-wrapper">
                        <?php foreach ($homeworks as $z => $homework) { ?>
                            <li style="margin-bottom:20px;">
                                <div class="sortable-block sortable-blockunit">
                                    <div class="sortable-header">
                                        <!-- <div class="panned-icon">⋮⋮ </div> -->
                                        <div class="panned-icon"><i class="fa fa-copy" aria-hidden="true"></i></div>
                                        <h3 class="sortable-title">

                                            <small>
                                                <a href="javascript:void(0)" class="viewHomework" data-toggle="modal" data-course="<?php echo $course->id; ?>" data-set="<?php echo $set; ?>" data-id="<?php echo $homework->homeworkID; ?>">
                                                    <?php echo $homework->unit_name;
                                                    echo ($homework->chapter_id != null && $homework->chapter_id != 0) ? '- ' . $homework->chapter_name : '' ?>
                                                    <?php if ($usertypeID == 3 || $usertypeID == 4) : ?>
                                                        <small><span class="label <?= $homework->status_label; ?>"><?= $homework->status_title ?></span></small>
                                                    <?php endif; ?>
                                            </small>
                                            <?= $homework->title  ?>

                                            <?php
                                            if ($homework->deadlinedate > date('Y-m-d')) {
                                                if ($homework->deadlinedate < date('Y-m-d', strtotime('+3 days'))) {
                                                    $color = "text-warning";
                                                } else {
                                                    $color = "text-success";
                                                }
                                            } elseif ($homework->deadlinedate < date('Y-m-d')) {
                                                $color = "text-danger";
                                            } elseif ($homework->deadlinedate  == date('Y-m-d')) {
                                                $color = "text-warning";
                                            }
                                            ?>
                                            <div class="<?= $color ?> h5 mt-2"><b><?= '  (Deadline: ' . $homework->deadlinedate . ')' ?></b></div>

                                        </h3>
                                        </a>

                                    </div>
                                    <div class="sortable-actions">
                                        <?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
                                            <label class="switch" data-toggle="tooltip" data-placement="top" data-original-title="Publish/Unpublish">
                                                <input type="checkbox" class="switch__input" onclick="changeHomeworkStatus('<?= $homework->homeworkID ?>')" class="onoffswitch-small-checkbox" id="switch-content<?= $homework->homeworkID ?>" <?= $homework->is_published ? "checked='checked'" : ''; ?>>
                                                <span class="switch--unchecked">
                                                    <i class="fa fa-ban"></i>
                                                </span>
                                                <span class="switch--checked">
                                                    <i class="fa fa-check-circle"></i>
                                                </span>
                                            </label>
                                        <?php endif; ?>
                                        <?php
                                        if ($this->session->userdata('usertypeID') == 3 && $siteinfos->school_year == $this->session->userdata('defaultschoolyearID') ) {
                                            echo btn_upload('homework/homeworkanswer/' . $homework->homeworkID . '/' . $set . '?course=' . $course->id, $this->lang->line('upload')); ?>

                                        <?php } ?>

                                        <!-- <?php if ($usertypeID == 4) { ?>

                                            <div class="dropdown">
                                                <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                    <li>
                                                        <a href="<?= base_url('homework/view/' . $homework->homeworkID . '/' . $set . '?course=' . $course->id) ?>">View Submission</a>
                                                    </li>
                                                </ul>
                                            </div>

                                        <?php } ?> -->

                                        <?php if ($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) : ?>
                                            <?php if(
                                                        permissionChecker('homework_edit')
                                                        || permissionChecker('homework_delete') 
                                                        || permissionChecker('homework_view') 
                                                ) : ?>
                                            <div class="dropdown">
                                                <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                    <?php if (permissionChecker('homework_edit')) : ?>
                                                        <li>
                                                            <a href="<?= base_url('homework/edit/' . $homework->homeworkID . '/' . $set . '?course=' . $course->id . '&link=' . 'homework') ?>">Edit Homework</a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (permissionChecker('homework_delete')) : ?>
                                                        <li>
                                                            <a onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" href="<?= base_url('homework/delete/' . $homework->homeworkID . '/' . $set . '?course=' . $course->id . '&link=' . 'homework') ?>">Delete Homework</a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (permissionChecker('homework_view')) : ?>
                                                        <li>
                                                            <a href="<?= base_url('homework/view/' . $homework->homeworkID . '/' . $set . '?course=' . $course->id) ?>">View Submission</a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                            <?php endif; ?>


                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                    </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- add course modal starts -->
<div class="modal fade" tabindex="-1" role="dialog" id="viewHomework">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="view_ajax_homework">

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- add course modal ends -->

<?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
    <script>
        let url = '<?= base_url("/courses/homeworkStatus/") ?>';

        function changeHomeworkStatus(homeworkID) {
            $.post(url + homeworkID).done(function() {
                $('#loading').hide();
                toastr["success"]("Status changed.")

            }).fail(function(error) {
                $('#loading').hide();
                toastr["error"](error.responseText)

            });
        }
    </script>
<?php endif; ?>