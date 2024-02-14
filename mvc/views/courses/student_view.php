<div class="right-side--fullHeight  ">

    <div class="row w-100 ">

        <?php $this->load->view("components/course_menu"); ?>
        <div class="course-content">
            <div class="container container--sm">
                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                        <div>
                            <small>Course</small>
                        </div>
                        <?php echo $course->classes . ' ' . $course->subject; ?>
                    </h1>

                </header>


                <div class="sortable-list">
                    <ul id="unit" class="unit-wrapper">
                        <?php foreach ($units as $x => $unit) { ?>
                            <li>
                                <div class="sortable-block sortable-blockunit">
                                    <div class="sortable-header collapsed" role="button" data-toggle="collapse" <?= empty($unit->chapters) ? "disabled='disabled'" : ''; ?> href="#chapter<?= $unit->id ?>" onclick="storeSortableData('chapter<?= $unit->id ?>')" aria-expanded="true">
                                        <!-- <div class="panned-icon">⋮⋮</div> -->

                                        <a class="btn btn-sm btn-link collapsed" role="button" data-toggle="collapse" <?= empty($unit->chapters) ? "disabled='disabled'" : ''; ?> href="#chapter<?= $unit->id ?>" onclick="storeSortableData('chapter<?= $unit->id ?>')" aria-expanded="true">
                                            <i class="fa fa-angle-down"></i>
                                        </a>

                                        <h3 class="sortable-title unit">
                                            <small>Unit</small><?php echo $unit->unit_name; ?>
                                        </h3>
                                    </div>
                                </div>
                                <ul id="chapter<?= $unit->id ?>" class="collapse chapter-wrapper">

                                    <?php foreach ($unit->chapters as $y => $chapter) { ?>
                                        <?php
                                        if (
                                            $chapter->classworks || $chapter->homeworks || $chapter->assignments ||
                                            $chapter->quizzes || $chapter->links || $chapter->attachments ||
                                            $chapter->contents
                                        )
                                            $isDisable = false;
                                        else
                                            $isDisable = true;
                                        ?>

                                        <li>
                                            <div class="sortable-block chapter-wrapper sortable-block--parent sortable-block--studentView">
                                                <div class="sortable-header collapsed" role="button" data-toggle="collapse" <?= $isDisable ? "disabled='disabled'" : ''; ?> href="#content<?= $chapter->id ?>" onclick="storeSortableData('content<?= $chapter->id ?>')" aria-expanded="false">
                                                    <!-- <div class="panned-icon">⋮⋮</div> -->



                                                    <a class="btn btn-sm btn-link collapsed" role="button" data-toggle="collapse" <?= $isDisable ? "disabled='disabled'" : ''; ?> href="#content<?= $chapter->id ?>" onclick="storeSortableData('content<?= $chapter->id ?>')" aria-expanded="false">
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>

                                                    <h3 class="sortable-title chapter">
                                                        <small>Chapter</small>
                                                        <chapter><?= $chapter->chapter_name ?></chapter>
                                                    </h3>
                                                </div>
                                                <?php if ($chapter->contents) { ?>
                                                    <div class="sortable-actions">
                                                        <?php if ($chapter->content_exists) {
                                                            $coverage = calculatePercentage($chapter->covered, $chapter->total_coverage);
                                                            if ($coverage < 100 && !($coverage <= 0)) {
                                                        ?>

                                                                <div class="progress-wrap">
                                                                    <label>In Progress</label>
                                                                    <div class="progress">
                                                                        <div class="progress-bar" role="progressbar" aria-valuenow="<?= $coverage ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $coverage ?>%;">
                                                                            <?= $coverage ?>%
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } elseif ($coverage >= 100) { ?>
                                                                <div class="progress-wrap">
                                                                    <label>Completed</label>
                                                                    <span class="switch--checked">
                                                                        <i class="fa fa-check-circle"></i>
                                                                    </span>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if ($usertypeID == 4) { ?>
                                                                <input style="float: right; margin-right: 3px;" class="btn btn-success btn-sm displaycontent" style="margin-bottom: 10px" type="button" value="Contents" data-chapterid="<?php echo $chapter->id ?>" data-toggle="modal" data-target="#displaycontent">
                                                                <input style="float: right; margin-right: 3px;" class="btn btn-success btn-sm displayquiz" style="margin-bottom: 10px" type="button" value="Quizzes" data-chapterid="<?php echo $chapter->id ?>" data-toggle="modal" data-target="#displayquiz">
                                                            <?php } ?>
                                                            <?php if ($usertypeID != 4) { ?>
                                                                <a href="<?php echo base_url() . 'courses/content/' . $chapter->id . '?course_id=' . $course->id ?>" class="btn <?= $coverage <= 0 ? 'btn-success' : 'btn-default' ?> btn-sm"> <?= $coverage <= 0 ? 'Start' : ($coverage >= 100 ? 'View Content' : 'Resume') ?></a>
                                                            <?php } else { ?>
                                                                <a href="<?php echo base_url() . 'courses/content/' . $chapter->id . '?course_id=' . $course->id ?>" class="btn btn-default btn-sm">View</a>
                                                        <?php }
                                                        } ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <?php if ($chapter->lists) { ?>
                                                <ul id="content<?= $chapter->id ?>" class="collapse chapter-wrapper">
                                                    <?php
                                                    foreach ($chapter->lists as $z => $list) {
                                                        if (isset($list->attachment)) {
                                                            $title = "Attachment";
                                                            $faIcon = checkFileExtension($list->attachment);
                                                            $type = 'attachment';
                                                            $titleValue = $list->file_name;
                                                            $id = $list->id;
                                                            $published = $list->published;
                                                        } else if (isset($list->content_title)) {
                                                            $title = "Content";
                                                            $faIcon = "fa-book";
                                                            $type = 'content';
                                                            $titleValue = $list->content_title;
                                                            $id = $list->id;
                                                            $published = $list->published;
                                                        } else if (isset($list->quiz_name)) {
                                                            $title = "Quiz";
                                                            $faIcon = "fa-puzzle-piece";
                                                            $type = 'quiz';
                                                            $titleValue = $list->quiz_name;
                                                            $id = $list->id;
                                                            $published = $list->published;
                                                        } else if (isset($list->classworkID)) {
                                                            $title = "Classwork";
                                                            $faIcon = "fa-book";
                                                            $type = 'classwork';
                                                            $titleValue = $list->title;
                                                            $id = $list->classworkID;
                                                            $published = $list->is_published;
                                                        } else if (isset($list->homeworkID)) {
                                                            $title = "Homework";
                                                            $faIcon = "fa-book";
                                                            $type = 'homework';
                                                            $titleValue = $list->title;
                                                            $id = $list->homeworkID;
                                                            $published = $list->is_published;
                                                        } else if (isset($list->assignmentID)) {
                                                            $title = "Assignment";
                                                            $faIcon = "fa-book";
                                                            $type = 'assignment';
                                                            $titleValue = $list->title;
                                                            $id = $list->assignmentID;
                                                            $published = $list->is_published;
                                                        } else if (isset($list->courselink)) {
                                                            $title = "Link";
                                                            $faIcon = checkLinkType($list->type);
                                                            $type = 'link';
                                                            $titleValue = namesorting($list->courselink, 30);
                                                            $id = $list->id;
                                                            $published = $list->published;
                                                        }
                                                    ?>
                                                        <li class="sortable-content" data-index="<?= $z ?>" data-type="<?= $type ?>" data-row-id="<?= $id ?>" data-chapter-id="<?= $chapter->id ?>">
                                                            <div class="sortable-block" style="padding: 0px 16px 0px 16px;">
                                                                <div class="sortable-header">
                                                                    <!-- <div class="panned-icon">⋮⋮ </div> -->
                                                                    <div class="header-icon"><i class="fa <?= $faIcon ?>" aria-hidden="true"></i></div>
                                                                    <h3 class="<?php echo $title; ?> sortable-title" data-type="<?= $type ?>" data-id="<?= $id ?>">
                                                                        <small><?= $title ?></small>
                                                                        <?= $titleValue ?>
                                                                    </h3>
                                                                </div>
                                                                <div class="sortable-actions">
                                                                    <!-- <?php if ($type == 'content') { ?>
                                                    <div class="dropdown">
                                                        <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                        <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                            <li>
                                                                <a href="<?php echo base_url() . 'courses/content/' . $chapter->id . '?course_id=' . $course->id . '#headingOne' . $list->id ?>">View Content</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                <?php } ?> -->
                                                                    <?php if ($type == 'attachment') { ?>
                                                                        <!-- <div class="dropdown">
                                                        <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                        <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                            <li>
                                                                <a href="<?php echo base_url() . 'uploads/images/' . $list->attachment ?>" <?= checkIfPhoto($list->attachment) ? "data-lightbox=image-1" : '' ?>>View Attachment</a>
                                                            </li>
                                                        </ul>
                                                    </div> -->
                                                                    <?php } ?>

                                                                    <?php if ($type == 'link') { ?>
                                                                        <!-- <div class="dropdown">
                                                        <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                        <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                            <li>
                                                            <?php
                                                                        $url = $list->courselink;
                                                                        $url = (strncasecmp('http://', $url, 7) && strncasecmp('https://', $url, 8) ? 'http://' : '') . $url;
                                                            ?>
                                                                <a href="<?= $url ?>" target="_blank" <?= checkIfPhotoLink($list->type) ? "data-lightbox=image-1" : '' ?>>Open Link</a>
                                                            </li>
                                                        </ul>
                                                    </div> -->
                                                                    <?php } ?>
                                                                    <?php if ($type == 'quiz') { ?>
                                                                        <!-- <div class="dropdown">
                                                        <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                        <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                            <li>
                                                                <a href="<?php echo base_url() . 'courses/quiz/' . $id . '?course=' . $course->id ?>">View Quiz</a>
                                                            </li>
                                                        </ul>
                                                    </div> -->
                                                                    <?php } ?>
                                                                    <?php if ($type == 'assignment' && $usertypeID != 4) { ?>
                                                                        <?php if(
                                                                                permissionChecker('assignment_view')
                                                                               
                                                                        ) : ?>
                                                                        <div class="dropdown">
                                                                            <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                                            <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                                                <li>
                                                                                    <?php if (permissionChecker('assignment_view')) : ?>
                                                                                        <?php if (($this->session->userdata('usertypeID') == 3) && $siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) { ?>
                                                                                            <a href="<?= base_url('assignment/assignmentanswer/' . $id . '/' . $set . '?course=' . $course->id) ?>">Upload Assignment</a>
                                                                                        <?php } else { ?>
                                                                                            <a href="#" style="background: lightgray; cursor: not-allowed; color: #707478;">Upload Assignment</a>
                                                                                        <?php } ?>
                                                                                    <?php endif; ?>
                                                                                </li>
                                                                                <li>
                                                                                    <?php if (permissionChecker('assignment_view')) : ?>
                                                                                        <a href="<?= base_url('assignment/view/' . $id . '/' . $set . '?course=' . $course->id) ?>">View Submission</a>
                                                                                    <?php endif; ?>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                    <?php } ?>
                                                                    <?php if ($type == 'homework' && $usertypeID != 4) { ?>
                                                                        <?php if(
                                                                                permissionChecker('homework_view')
                                                                        ) : ?>
                                                                        <div class="dropdown">
                                                                            <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                                            <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                                                <li>
                                                                                    <?php if (permissionChecker('homework_view')) : ?>
                                                                                        <?php if (($this->session->userdata('usertypeID') == 3) && $siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) { ?>
                                                                                            <a href="<?= base_url('homework/homeworkanswer/' . $id . '/' . $set . '?course=' . $course->id) ?>">Upload Homework</a>
                                                                                        <?php } else { ?>
                                                                                            <a href="#" style="background: lightgray; cursor: not-allowed; color: #707478;">Upload Homework</a>
                                                                                        <?php } ?>
                                                                                    <?php endif; ?>
                                                                                </li>
                                                                                <li>
                                                                                    <?php if (permissionChecker('homework_view')) : ?>
                                                                                        <a href="<?= base_url('homework/view/' . $id . '/' . $set . '?course=' . $course->id) ?>">View Submission</a>
                                                                                    <?php endif; ?>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                    <?php } ?>
                                                                    <?php if ($type == 'classwork' && $usertypeID != 4) { ?>
                                                                        <?php if(
                                                                                permissionChecker('classwork_view')
                                                                        ) : ?>
                                                                        <div class="dropdown">
                                                                            <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                                            <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                                                <li>
                                                                                    <?php if (permissionChecker('classwork_view')) : ?>
                                                                                        <?php if (($this->session->userdata('usertypeID') == 3) && $siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) { ?>
                                                                                            <a href="<?= base_url('classwork/classworkanswer/' . $id . '/' . $set . '?course=' . $course->id) ?>">Upload Classwork</a>
                                                                                        <?php } else { ?>
                                                                                            <a href="#" style="background: lightgray; cursor: not-allowed; color: #707478;">Upload Classwork</a>
                                                                                        <?php } ?>
                                                                                    <?php endif; ?>
                                                                                </li>
                                                                                <li>
                                                                                    <?php if (permissionChecker('classwork_view')) : ?>
                                                                                        <a href="<?= base_url('classwork/view/' . $id . '/' . $set . '?course=' . $course->id) ?>">View Submission</a>
                                                                                    <?php endif; ?>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                    <?php } ?>
                                                                </div>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } ?>

                                        </li>
                                    <?php } ?>

                                </ul>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="displaycontent">
    <div class="modal-dialog">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">View Contents</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover dataTable no-footer">
                    <tbody id="content_id"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="displayquiz">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">View Quizzes</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover dataTable no-footer">
                    <tbody id="quiz_id"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- view all modal start -->
<div class="modal fade" tabindex="-1" role="dialog" id="viewAttachment">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content" id="view_ajax_attachment">

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--  view all modal end-->

<script type="text/javascript" src="<?php echo base_url('assets/lightbox2-2.11.3/dist/js/lightbox.js'); ?>"></script>

<script>
    $(document).on("click", ".displaycontent", function() {
        var chapter_id = $(this).data('chapterid');
        var student_id = "<?php echo htmlentities(escapeString($this->uri->segment(4))); ?>"
        $.ajax({
            type: 'POST',
            url: "<?= base_url('courses/getContent') ?>",
            data: {
                'chapter_id': chapter_id,
                'student_id': student_id
            },
            dataType: "html",
            success: function(data) {
                $('#content_id').html(data);
            }
        });

    });

    function storeSortableData(data) {
        var course_id = "<?= $course->id ?>";
        var originalArr = localStorage.getItem('sortable');
        if (originalArr == null) {
            var newArr = [];
            newArr.push(course_id + '/' + data);
        } else {
            originalArr = JSON.parse(originalArr);
            var exists = originalArr.includes(course_id + '/' + data);
            if (exists) {
                var filteredAry = originalArr.filter(function(e) {
                    return e !== course_id + '/' + data
                })
                var newArr = filteredAry;
            } else {
                originalArr.push(course_id + '/' + data);
                var newArr = originalArr;
            }
        }
        myJSON = JSON.stringify(newArr);
        localStorage.setItem('sortable', myJSON);

        if (data.includes('content')) {
            chapter_id = data.replace("content", "");
            if ($("#content" + chapter_id).parent().find('div:first-child').hasClass('sortable-block--shown')) {
                $("#content" + chapter_id).parent().find('div:first-child').removeClass('sortable-block--shown');
            } else {
                $("#content" + chapter_id).parent().find('div:first-child').addClass('sortable-block--shown');
            }
        }
    }

    $(document).ready(function() {
        $('.unit-wrapper >li >.chapter-wrapper').on('show.bs.collapse', function() {
            $(this).prev().addClass('in');
        })
        $('.unit-wrapper >li >.chapter-wrapper').on('hide.bs.collapse', function() {
            $(this).prev().removeClass('in');
        })
        $('.content-wrapper').on('show.bs.collapse', function() {
            $(this).prev().addClass('in');
        })
        $('.content-wrapper').on('hide.bs.collapse', function() {
            $(this).prev().removeClass('in');
        })
        var sortable = localStorage.getItem('sortable');
        var course_id = "<?= $course->id ?>";

        if (sortable != null) {
            var array = JSON.parse(sortable);
            for (i = 0; i < array.length; i++) {
                if (array[i].split('/')[0] == course_id) {
                    $('#' + array[i].split('/')[1]).addClass('in');

                    if (array[i].split('/')[1].includes('content')) {
                        chapter_id = array[i].split('/')[1].replace("content", "");
                        $('#content' + chapter_id).parent().find('div:first-child').addClass('sortable-block--shown');
                    }
                }
            }
        }
    });


    $(document).on("click", ".displayquiz", function() {
        var chapter_id = $(this).data('chapterid');
        var student_id = "<?php echo htmlentities(escapeString($this->uri->segment(4))); ?>"

        $.ajax({
            type: 'POST',
            url: "<?= base_url('courses/getQuiz') ?>",
            data: {
                'chapter_id': chapter_id,
                'student_id': student_id
            },
            dataType: "html",
            success: function(data) {
                $('#quiz_id').html(data);
            }
        });

    });
</script>

<script>
    $('.course-content .sortable-title').on('click', function() {

        type = $(this).data('type');
        id = $(this).data('id');

        $('#view_ajax_attachment').empty();
        if (type == 'assignment') {
            url = "<?= base_url('courses/getAssignmentByAjax') ?>";

        } else if (type == 'homework') {
            url = "<?= base_url('courses/getHomeworkByAjax') ?>";

        } else if (type == 'classwork') {
            url = "<?= base_url('courses/getClassworkByAjax') ?>";

        } else if (type == 'content') {
            url = "<?= base_url('courses/getContentByAjax') ?>";

        } else if (type == 'link') {
            url = "<?= base_url('courses/getLinkByAjax') ?>";
        } else if (type == 'attachment') {
            url = "<?= base_url('courses/getAttachmentByAjax') ?>";

        } else if (type == 'quiz') {
            url = "<?= base_url('courses/getQuizByAjax') ?>";
        } else if (type == 'course') {
            url = course_url
        }

        $.ajax({
            type: 'POST',
            url: url,
            dataType: "html",
            data: {
                id: id,
                course: <?php echo $course->id; ?>,
                set: <?php echo $set; ?>,
                type: type,
                is_student_view: 1
            },
            success: function(data) {
                console.log(data);
                $('#viewAttachment').modal('show');
                $('#view_ajax_attachment').append(data);

            }
        });
    });
</script>