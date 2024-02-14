<div class="right-side--fullHeight  ">

    <div class="row w-100 ">

        <?php $this->load->view("components/course_menu"); ?>
        <div class="course-content">
            <div class="container container--sm">
                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                        <div>
                            <small>Assignment</small>
                        </div>
                        <?php echo $course->classes . ' ' . $course->subject; ?>
                    </h1>
                    <?php if ($siteinfos->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') != 3) { ?>
                        <?php if (permissionChecker('assignment_add')) { ?>
                            <a href="<?= base_url('assignment/add?course=') . $course->id . '&link=' . 'assignment' ?>" class="btn-sm btn btn-success" title="Create new assignment"><i class="fa fa-plus"></i> Create</a>
                        <?php } ?>
                    <?php } ?>
                </header>

                <div class="sortable-list">
                    <ul id="unit" class="course-wrapper">
                        <?php foreach ($assignments as $z => $assignment) { ?>
                            <li style="margin-bottom:20px;">
                                <div class="sortable-block sortable-blockunit">
                                    <div class="sortable-header">
                                        <!-- <div class="panned-icon">⋮⋮ </div> -->
                                        <div class="panned-icon"><i class="fa fa-book" aria-hidden="true"></i></div>
                                        <h3 class="sortable-title">

                                            <small>
                                                <?php if ($assignment->unit_id != null && $assignment->unit_id != 0) : ?>

                                                    <?php echo $assignment->unit_name;
                                                    echo ($assignment->chapter_id != null && $assignment->chapter_id != 0) ? '- ' . $assignment->chapter_name : '' ?>
                                                <?php endif ?>
                                                <?php if ($usertypeID == 3 || $usertypeID == 4) : ?>
                                                    <small><span class="label <?= $assignment->status_label; ?>"><?= $assignment->status_title ?></span></small>
                                                <?php endif; ?>
                                            </small>
                                            <a href="javascript:void(0)" class="viewAtt " data-toggle="modal" onclick="" data-id="<?php echo $assignment->assignmentID ?>" data-course="<?php echo $course->id; ?>" data-set="<?php echo $set; ?>">
                                                <?= $assignment->title; ?>
                                            </a>

                                            <?php
                                            if ($assignment->deadlinedate > date('Y-m-d')) {
                                                if ($assignment->deadlinedate < date('Y-m-d', strtotime('+3 days'))) {
                                                    $color = "text-warning";
                                                } else {
                                                    $color = "text-success";
                                                }
                                            } elseif ($assignment->deadlinedate < date('Y-m-d')) {
                                                $color = "text-danger";
                                            } elseif ($assignment->deadlinedate  == date('Y-m-d')) {
                                                $color = "text-warning";
                                            }
                                            ?>
                                            <div class="<?= $color ?> h5 mt-2"><b><?= '  (Deadline: ' . $assignment->deadlinedate . ')' ?></b></div>

                                        </h3>
                                    </div>
                                    <div class="sortable-actions">
                                        <?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
                                            <label class="switch" data-toggle="tooltip" data-placement="top" data-original-title="Publish/Unpublish">
                                                <input type="checkbox" class="switch__input" onclick="changeAssignmentStatus('<?= $assignment->assignmentID ?>')" class="onoffswitch-small-checkbox" id="switch-content<?= $assignment->assignmentID ?>" <?= $assignment->is_published ? "checked='checked'" : ''; ?>>
                                                <span class="switch--unchecked">
                                                    <i class="fa fa-ban"></i>
                                                </span>
                                                <span class="switch--checked">
                                                    <i class="fa fa-check-circle"></i>
                                                </span>
                                            </label>
                                        <?php endif; ?>

                                        <?php 
                                            if (($this->session->userdata('usertypeID') == 3) && $siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) {
                                                echo btn_upload('assignment/assignmentanswer/' . $assignment->assignmentID . '/' . $set . '?course=' . $course->id, $this->lang->line('upload'));
                                        ?>

                                            <?php  }
                                            ?>
                                       

                                        <!-- <?php if ($usertypeID == 4) : ?>
                                            <div class="dropdown">
                                                <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                    <li>    
                                                        <a href="<?= base_url('assignment/view/' . $assignment->assignmentID . '/' . $set . '?course=' . $course->id) ?>">View Submission</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php endif; ?> -->



                                        <?php if (($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) : ?>
                                            <?php if(
                                                        permissionChecker('assignment_edit')
                                                        || permissionChecker('assignment_delete') 
                                                        || permissionChecker('assignment_view') 
                                                ) : ?>
                                            <div class="dropdown">
                                                <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                    <?php if (permissionChecker('assignment_edit')) : ?>
                                                        <li>
                                                            <a href="<?= base_url('assignment/edit/' . $assignment->assignmentID . '/' . $set . '?course=' . $course->id . '&link=' . 'assignment') ?>">Edit Assignment</a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (permissionChecker('assignment_delete')) : ?>
                                                        <li>
                                                            <a onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" href="<?= base_url('assignment/delete/' . $assignment->assignmentID . '/' . $set . '?course=' . $course->id . '&link=' . 'assignment') ?>">Delete Assignment</a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (permissionChecker('assignment_view')) : ?>
                                                        <li>
                                                            <a href="<?= base_url('assignment/view/' . $assignment->assignmentID . '/' . $set . '?course=' . $course->id) ?>">View Submission</a>
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



<?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
    <script>
        let url = '<?= base_url("/courses/assignmentStatus/") ?>';

        function changeAssignmentStatus(assignmentId) {
            $.post(url + assignmentId).done(function() {
                $('#loading').hide();
                toastr["success"]("Status changed.")
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "500",
                    "hideDuration": "500",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            }).fail(function(error) {
                $('#loading').hide();
                toastr["error"](error.responseText)
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "500",
                    "hideDuration": "500",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            });
        }
    </script>
<?php endif; ?>