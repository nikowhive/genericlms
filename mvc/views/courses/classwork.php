<div class="right-side--fullHeight  ">
    <div class="row w-100 ">
        <?php $this->load->view("components/course_menu"); ?>
        <div class="course-content">
            <div class="container container--sm">
                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                        <div>
                            <small>Classwork</small>
                        </div>
                        <?php echo $course->classes . ' ' . $course->subject; ?>
                    </h1>
                    <?php if ($siteinfos->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') != 3) { ?>
                        <?php if (permissionChecker('classwork_add')) { ?>
                            <a href="<?= base_url('classwork/add?course=') . $course->id . '&link=' . 'classwork' ?>" class="btn-sm btn btn-success" title="Create new classwork"><i class="fa fa-plus"></i> Create</a>
                        <?php } ?>
                    <?php } ?>
                </header>

                <div class="sortable-list">
                    <ul id="unit" class="course-wrapper">
                        <?php foreach ($classworks as $z => $classwork) { ?>
                            <li style="margin-bottom:20px;">
                                <div class="sortable-block sortable-blockunit">
                                    <div class="sortable-header">
                                        <!-- <div class="panned-icon">⋮⋮ </div> -->
                                        <div class="panned-icon"><i class="fa fa-file-text-o" aria-hidden="true"></i></div>
                                        <h3 class="sortable-title">

                                            <small>
                                                <a href="javascript:void(0)" class="viewClasswork" onclick="" data-toggle="modal" data-course="<?php echo $course->id; ?>" data-set="<?php echo $set; ?>" data-id="<?php echo  $classwork->classworkID; ?>">
                                                    <?php echo $classwork->unit_name;
                                                    echo ($classwork->chapter_id != NULL && $classwork->chapter_id != 0) ? '- ' . $classwork->chapter_name : '' ?>
                                                    <?php if ($usertypeID == 3 || $usertypeID == 4) : ?>
                                                        <small><span class="label <?= $classwork->status_label; ?>"><?= $classwork->status_title ?></span></small>
                                                    <?php endif; ?>
                                            </small>
                                            <?= $classwork->title ?>

                                            <?php
                                            if ($classwork->deadlinedate > date('Y-m-d')) {
                                                if ($classwork->deadlinedate < date('Y-m-d', strtotime('+3 days'))) {
                                                    $color = "text-warning";
                                                } else {
                                                    $color = "text-success";
                                                }
                                            } elseif ($classwork->deadlinedate < date('Y-m-d')) {
                                                $color = "text-danger";
                                            } elseif ($classwork->deadlinedate  == date('Y-m-d')) {
                                                $color = "text-warning";
                                            }
                                            ?>
                                            <div class="<?= $color ?> h5 mt-2"><b><?= '  (Deadline: ' . $classwork->deadlinedate . ')' ?></b></div>

                                        </h3>
                                        </a>

                                    </div>
                                    <div class="sortable-actions">
                                        <?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
                                            <label class="switch" data-toggle="tooltip" data-placement="top" data-original-title="Publish/Unpublish">
                                                <input type="checkbox" class="switch__input" onclick="changeClassworkStatus('<?= $classwork->classworkID ?>')" class="onoffswitch-small-checkbox" id="switch-content<?= $classwork->classworkID ?>" <?= $classwork->is_published ? "checked='checked'" : ''; ?>>
                                                <span class="switch--unchecked">
                                                    <i class="fa fa-ban"></i>
                                                </span>
                                                <span class="switch--checked">
                                                    <i class="fa fa-check-circle"></i>
                                                </span>
                                            </label>
                                        <?php endif; ?>
                                        <?php if ($this->session->userdata('usertypeID') == 3) {
                                                if ($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) {
                                                    echo btn_upload('classwork/classworkanswer/' . $classwork->classworkID . '/' . $set . '?course=' . $course->id, $this->lang->line('upload')); ?>


                                            <?php  }
                                            }
                                            ?>

                                        <!-- <?php if ($usertypeID == 4) { ?>

                                            <div class="dropdown">
                                                <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                    <li>
                                                        <a href="<?= base_url('classwork/view/' . $classwork->classworkID . '/' . $set . '?course=' . $course->id) ?>">View Submission</a>
                                                    </li>
                                                </ul>
                                            </div>

                                        <?php } ?> -->





                                        <?php if (($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) : ?>

                                            <?php if(
                                                        permissionChecker('classwork_edit')
                                                        || permissionChecker('classwork_delete') 
                                                        || permissionChecker('classwork_view') 
                                                ) : ?>
                                            <div class="dropdown">
                                                <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                    <?php if (permissionChecker('classwork_edit')) : ?>
                                                        <li>
                                                            <a href="<?= base_url('classwork/edit/' . $classwork->classworkID . '/' . $set . '?course=' . $course->id . '&link=' . 'classwork') ?>">Edit Classwork</a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (permissionChecker('classwork_delete')) : ?>
                                                        <li>
                                                            <a onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" href="<?= base_url('classwork/delete/' . $classwork->classworkID . '/' . $set . '?course=' . $course->id . '&link=' . 'classwork') ?>">Delete Classwork</a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (permissionChecker('classwork_view')) : ?>
                                                        <li>
                                                            <a href="<?= base_url('classwork/view/' . $classwork->classworkID . '/' . $set . '?course=' . $course->id) ?>">View Submission</a>
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
        let url = '<?= base_url("/courses/classworkStatus/") ?>';

        function changeClassworkStatus(classworkID) {
            $.post(url + classworkID).done(function() {
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

        $('.viewAtt').on('click', function() {
            contentid = $(this).data("id");
            $('#view_ajax_classwork').empty();
            $.ajax({
                type: 'POST',
                url: "<?= base_url('courses/getClassworkByAjax') ?>",
                dataType: "html",
                data: {
                    id: contentid,
                    course: <?php echo $course->id ?>
                },
                success: function(data) {
                    console.log(data);
                    $('#view_ajax_classwork').append(data);

                }
            });
        });
    </script>
<?php endif; ?>