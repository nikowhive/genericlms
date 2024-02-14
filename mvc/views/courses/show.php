<style>
    .disabled{
    pointer-events:none;
    opacity:0.4;
}
</style>
<div class="right-side--fullHeight  ">
    <div class="row w-100 ">
        <div class="course-content">
            <div class="container container--sm">
                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                        <div>
                            <small>Course</small>
                        </div>
                        <?php echo $course->classes . ' | ' . $course->subject; ?>
                    </h1>
                    <div class="switch-wrapper">
                        <?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
                            <label class="switch" data-toggle="tooltip" data-placement="top" data-original-title="Publish/Unpublish">
                                <input type="checkbox" class="switch__input" onclick="changeStatus('<?= $course->id ?>', 'course')" class="onoffswitch-small-checkbox" id="switch-course<?= $course->id ?>" <?= $course->published == '1' ? "checked='checked'" : ''; ?>>
                                <span class="switch--unchecked">
                                    <i class="fa fa-ban"></i>
                                </span>
                                <span class="switch--checked">
                                    <i class="fa fa-check-circle"></i>
                                </span>
                            </label>
                        <?php endif; ?>
                    </div>
                    <div class="list-inline">
                        <span class="virtual-class" id='<?php echo $course->id ?>'>
                            <a href='#' class='btn btn-success btn-xs mrg start-virtual-class start-virtual-class-<?php echo $course->id ?>'>Start Virtual Class</a>
                            <a href='#' class='btn btn-danger btn-xs mrg end-virtual-class end-virtual-class-<?php echo $course->id ?>'>End Class</a>
                            <?php if (permissionChecker('annual_plan_add') || permissionChecker('annual_plan_edit')) : ?>
                            <?php if (isset($annual_id) && !empty($annual_id)) : ?>
                                <!-- <a href="<?php //echo base_url('annual_plan/edit/' . $annual_id->id . '?course=' . $course->id) ?>" class='btn btn-danger btn-xs'><i class="fa fa-plus"></i> Edit Annual Plan</a> -->
                            <?php else : ?>

                                <!-- <a href="<?php //echo base_url('annual_plan/add?course=' . $course->id) ?>" class='btn btn-danger btn-xs' class="fa fa-plus"></i> Add Annual Plan</a> -->
                            <?php endif; ?>
                            <?php endif; ?>

                        </span>

                    <?php if(
                        permissionChecker('unit_add') || permissionChecker('chapter_add') || permissionChecker('lesson_plan_add')
                        || permissionChecker('daily_plan_add') || permissionChecker('content_add') || permissionChecker('quiz_add')
                        || permissionChecker('attachment_add') || permissionChecker('link_add') || permissionChecker('homework_add')
                        || permissionChecker('classwork_add') || permissionChecker('assignment_add') 
                    ) : ?>
                        <div class="dropdown">
                            <a data-toggle="dropdown" class="btn-sm btn btn-success"><i class="fa fa-plus"></i> Create</a>
                            <!-- <a href="#" class=" " data-toggle="dropdown"> ⋮</a> -->
                            <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                <li>
                                    <!-- <a href="<?php echo base_url() . 'unit/add?course=' . $course->id ?>">Unit</a> -->
                                </li>
                                <?php if (permissionChecker('unit_add')): ?>
                                <li>
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#addUnit" id="add_unit">Unit</a>
                                </li>
                                    <?php endif; ?>
                                <?php if (permissionChecker('chapter_add')): ?>
                                <li>
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#addChapter" id="add_chapter">Chapter</a>
                                    <!-- <a href="<?php echo base_url() . 'chapter/add?course=' . $course->id ?>">Chapter</a> -->
                                </li>
                                <?php endif; ?>
                                <li role="separator" class="divider"></li>
                                <?php if (permissionChecker('lesson_plan_add')): ?>
                                <!-- <li>
                                    <a href="#" data-toggle="modal" data-target="#addContent" id="add_lesson">Lesson Plan</a>
                                </li> -->
                                <?php endif; ?>
                                <?php if(permissionChecker('daily_plan_add')) { ?>
                                <!-- <li>
                                    <a href="#" data-toggle="modal" data-target="#addContent" id="add_daily">Daily Plan</a>
                                </li> -->
                                <?php } ?>
                                <?php if (permissionChecker('content_add')): ?>
                                <li>
                                    <a href="#" data-toggle="modal" data-target="#addContent" id="add_content">Study Content</a>
                                </li>
                                <?php endif; ?>
                                <?php if (permissionChecker('quiz_add')): ?>
                                <li>
                                    <a href="#" data-toggle="modal" data-target="#addQuiz1" id="add_quiz1">Quiz</a>
                                </li>
                                <?php endif; ?>
                                <?php if (permissionChecker('attachment_add')): ?>
                                <li>
                                    <a href="#" data-toggle="modal" data-target="#addContent" id="add_attachment">Attachment</a>
                                </li>
                                <?php endif; ?>
                                <?php if (permissionChecker('link_add')): ?>
                                <li>
                                    <a href="#" data-toggle="modal" data-target="#addContent" id="add_link">Link</a>
                                </li>
                                <?php endif; ?>
                                <?php if (permissionChecker('homework_add')): ?>
                                <li>
                                    <a href="<?php echo base_url('homework/add?course=') . $course->id ?>">Homework</a>
                                </li>
                                <?php endif; ?>
                                 <?php if (permissionChecker('classwork_add')): ?>
                                <li>
                                    <a href="<?php echo base_url('classwork/add?course=') . $course->id ?>">Classwork</a>
                                </li>
                                <?php endif; ?>
                                <?php if (permissionChecker('assignment_add')): ?>
                                <li>
                                    <a href="<?php echo base_url('assignment/add?course=') . $course->id ?>">Assignment</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </header>

                <div class="sortable-list">
                    <ul id="unit" class="unit-wrapper">
                        <?php foreach ($units as $x => $unit) {
                        ?>
                            <li>
                                <div class="sortable-block sortable-blockunit">
                                    <div class="sortable-header collapsed" role="button" data-toggle="collapse" <?= empty($unit->chapters) ? "disabled='disabled'" : ''; ?> href="#chapter<?= $unit->id ?>" onclick="storeSortableData('chapter<?= $unit->id ?>')" aria-expanded="true">
                                        <!-- <div class="panned-icon">⋮⋮</div> -->
                                        <a class="btn btn-sm btn-link collapsed" role="button" data-toggle="collapse" <?= empty($unit->chapters) ? "disabled='disabled'" : ''; ?> href="#chapter<?= $unit->id ?>" aria-expanded="true">
                                            <i class="fa fa-angle-down"></i>
                                        </a>
                                        <h3 class="sortable-title">
                                            <small>Unit</small>
                                            <a class="" role="button" data-toggle="collapse" <?= empty($unit->chapters) ? "disabled='disabled'" : ''; ?> href="#chapter<?= $unit->id ?>" aria-expanded="true">
                                                <?php echo $unit->unit_name; ?>
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="sortable-actions">
                                        <?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
                                            <label class="switch" data-toggle="tooltip" data-placement="top" data-original-title="Publish/Unpublish">
                                                <input type="checkbox" class="switch__input" onclick="changeStatus('<?= $unit->id ?>', 'unit')" class="onoffswitch-small-checkbox" id="switch-unit<?= $unit->id ?>" <?= $unit->published == '1' ? "checked='checked'" : ''; ?>>
                                                <span class="switch--unchecked">
                                                    <i class="fa fa-ban"></i>
                                                </span>
                                                <span class="switch--checked">
                                                    <i class="fa fa-check-circle"></i>
                                                </span>
                                            </label>
                                        <?php endif; ?>
                                        <div class="dropdown">
                                            <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                            <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                <li>
                                                    <a href="javascript:void(0)" data-target="#viewAttachment" class="edit_unit" data-id="<?php echo $unit->id; ?>" data-course="<?php echo  $course->id; ?>">Edit Unit</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" data-target="#viewAttachment" class="add_chapter" data-unit-id="<?php echo $unit->id; ?>" data-course="<?php echo  $course->id; ?>">Add Chapter</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <ul id="chapter<?= $unit->id ?>" class="collapse chapter-wrapper">

                                    <?php foreach ($unit->chapters as $y => $chapter) {
                                    ?>
                                        <?php
                                        if (
                                            $chapter->classworks || $chapter->homeworks || $chapter->assignments ||
                                            $chapter->quizzes || $chapter->links || $chapter->attachments ||
                                            $chapter->contents
                                        ) {
                                            $isDisable = false;
                                        } else {
                                            $isDisable = true;
                                        }

                                        ?>

                                        <li>
                                            <div class="sortable-block chapter-wrapper sortable-block--parent">
                                                <div class="sortable-header collapsed" role="button" data-toggle="collapse" <?= $isDisable ? "disabled='disabled'" : ''; ?> href="#content<?= $chapter->id ?>" onclick="storeSortableData('content<?= $chapter->id ?>')" aria-expanded="false">
                                                    <!-- <div class="panned-icon">⋮⋮</div> -->
                                                    <a class="btn btn-sm btn-link collapsed" role="button" data-toggle="collapse" <?= $isDisable ? "disabled='disabled'" : ''; ?> href="#content<?= $chapter->id ?>" aria-expanded="false">
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <h3 class="sortable-title">
                                                        <small>Chapter</small>
                                                        <a class="chapter" role="button" data-toggle="collapse" <?= $isDisable ? "disabled='disabled'" : ''; ?> href="#content<?= $chapter->id ?>" aria-expanded="false">
                                                            <?= $chapter->chapter_name ?>
                                                        </a>
                                                    </h3>
                                                </div>
                                                <div class="sortable-actions">
                                                    <?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
                                                        <label class="switch" data-toggle="tooltip" data-placement="top" data-original-title="Publish/Unpublish">
                                                            <input type="checkbox" class="switch__input" onclick="changeStatus('<?= $chapter->id ?>', 'chapter')" class="onoffswitch-small-checkbox" id="switch-chapter<?= $chapter->id ?>" <?= $chapter->published == '1' ? "checked='checked'" : ''; ?>>
                                                            <span class="switch--unchecked">
                                                                <i class="fa fa-ban"></i>
                                                            </span>
                                                            <span class="switch--checked">
                                                                <i class="fa fa-check-circle"></i>
                                                            </span>
                                                        </label>
                                                    <?php endif; ?>

                                                    <?php if(
                                                        permissionChecker('chapter_edit')
                                                        || permissionChecker('content_add') 
                                                        || permissionChecker('quiz_add')
                                                        || permissionChecker('attachment_add') 
                                                        || permissionChecker('link_add') 
                                                        || permissionChecker('homework_add')
                                                        || permissionChecker('classwork_add') 
                                                        || permissionChecker('assignment_add') 
                                                    ) : ?>
                                                    <div class="dropdown">
                                                        <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                        <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                        <?php if (permissionChecker('chapter_edit')): ?>
                                                            <li>
                                                                <a href="javascript:void(0)" data-target="#viewAttachment" class="edit_chapter" data-id="<?php echo $chapter->id; ?>" data-course="<?php echo  $course->id; ?>">Edit Chapter</a>
                                                                <!-- <a href="<?php echo base_url() . 'chapter/edit/' . $chapter->id . '?course=' . $course->id . '&unit=' . $unit->id ?>">Edit Chapter</a> -->
                                                            </li>
                                                            <?php endif; ?>
                                                            <?php if (permissionChecker('content_add')): ?>
                                                            <li>
                                                                <a href="<?php echo base_url() . 'courses/addcontent/' . $chapter->id . '?course=' . $course->id ?>">Add Content</a>
                                                            </li>
                                                               <?php endif; ?>
                                                            <?php if (permissionChecker('attachment_add')): ?>
                                                            <li>
                                                                <a href="<?php echo base_url() . 'courses/addfiles/' . $chapter->id . '?course=' . $course->id ?>">Add Attachment</a>
                                                            </li>
                                                            <?php endif; ?>
                                                                <?php if (permissionChecker('link_add')): ?>
                                                            <li>
                                                                <a href="<?php echo base_url() . 'courses/addlinks/' . $chapter->id . '?course=' . $course->id ?>">Add Link</a>
                                                            </li>
                                                            <?php endif; ?>
                                                                <?php if (permissionChecker('quiz_add')): ?>
                                                            <li>
                                                                <a href="<?php echo base_url() . 'courses/addquizzes/' . $chapter->id . '?course=' . $course->id ?>">Add Quiz</a>
                                                            </li>
                                                            <?php endif; ?>
                                                                <?php if (permissionChecker('assignment_add')): ?>
                                                            <li>
                                                                <a href="<?php echo base_url() . 'assignment/add?course=' . $course->id . '&unit=' . $unit->id . '&chapter=' . $chapter->id  ?>">Add Assignment</a>
                                                            </li>
                                                            <?php endif; ?>
                                                                <?php if (permissionChecker('homework_add')): ?>
                                                            <li>
                                                                <a href="<?php echo base_url() . 'homework/add?course=' . $course->id . '&unit=' . $unit->id . '&chapter=' . $chapter->id  ?>">Add Homework</a>
                                                            </li>
                                                            <?php endif; ?>
                                                                <?php if (permissionChecker('classwork_add')): ?>
                                                            <li>
                                                                <a href="<?php echo base_url() . 'classwork/add?course=' . $course->id . '&unit=' . $unit->id . '&chapter=' . $chapter->id  ?>">Add Classwork</a>
                                                            </li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php if ($chapter->lists) {
                                            ?>
                                                <ul id="content<?= $chapter->id ?>" class="collapse   content-wrapper ">
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
                                                            <div class="sortable-block" style="padding:0px 16px 0px 16px;">
                                                                <div class="sortable-header">
                                                                    <div class="panned-icon">⋮⋮ </div>
                                                                    <div class="header-icon"><i class="fa <?= $faIcon ?>" aria-hidden="true"></i></div>
                                                                    <h3 class="sortable-title sort-type" id="disable" data-type="<?= $type ?>" data-id="<?= $id ?>">
                                                                        <small><?= $title ?></small>
                                                                        <?= $titleValue ?>
                                                                    </h3>
                                                                </div>
                                                                <div class="sortable-actions">
                                                                    <?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
                                                                        <label class="switch" data-toggle="tooltip" data-placement="top" data-original-title="Publish/Unpublish">
                                                                            <input type="checkbox" class="switch__input" onclick="changeStatus('<?= $id ?>', '<?= $type ?>')" class="onoffswitch-small-checkbox" id="switch-<?= $type . $id ?>" <?= $published == '1' ? "checked='checked'" : ''; ?>>
                                                                            <span class="switch--unchecked">
                                                                                <i class="fa fa-ban"></i>
                                                                            </span>
                                                                            <span class="switch--checked">
                                                                                <i class="fa fa-check-circle"></i>
                                                                            </span>
                                                                        </label>
                                                                    <?php endif; ?>
                                                                    <?php if ($type == 'attachment') { ?>

                                                                        <?php if(
                                                                              permissionChecker('attachment_edit')
                                                                            || permissionChecker('attachment_delete') 
                                                                            
                                                                        ) : ?>
                                                                       
                                                                        <div class="dropdown">
                                                                            <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                                            <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                                                <li>
                                                                                    <!-- <a href="<?php echo base_url() . 'uploads/images/' . $list->attachment ?>" <?= checkIfPhoto($list->attachment) ? "data-lightbox=image-1" : '' ?>>View Attachment</a> -->
                                                                                </li>
                                                                                <?php if (permissionChecker('attachment_edit')) : ?>
                                                                                <li>
                                                                                    <a href="<?php echo base_url('courses/edit_attachment/') . $id . '?course=' . $course->id; ?>">Edit Attachment</a>
                                                                                </li>
                                                                                <?php endif; ?>
                                                                                <?php if (permissionChecker('attachment_delete')) : ?>
                                                                                <li>
                                                                                    <a onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" href="<?php echo base_url('courses/deletefile/') . $id . '?course=' . $course->id; ?>">Delete Attachment</a>
                                                                                </li>
                                                                                <?php endif; ?>
                                                                            </ul>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                       
                                                                    <?php } ?>

                                                                    <?php if ($type == 'link') { ?>

                                                                        <?php if(
                                                                              permissionChecker('link_delete')
                                                                            || permissionChecker('link_edit') 
                                                                            
                                                                        ) : ?>
                                                                       
                                                                        <div class="dropdown">
                                                                            <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                                            <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                                                <li>
                                                                                    <?php
                                                                                    $url = $list->courselink;
                                                                                    $url = (strncasecmp('http://', $url, 7) && strncasecmp('https://', $url, 8) ? 'http://' : '') . $url;
                                                                                    ?>
                                                                                    <!-- <a href="<?= $url ?>" target="_blank" <?= checkIfPhotoLink($list->type) ? "data-lightbox=image-1" : '' ?>>Open Link</a> -->
                                                                                </li>
                                                                                <?php if (permissionChecker('link_delete')) : ?>
                                                                                <li>
                                                                                    <a onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" href="<?php echo base_url('courses/deletelink/') . $id . '?course=' . $course->id; ?>">Delete Link</a>
                                                                                </li>
                                                                                <?php endif; ?>     
                                                                                <?php if (permissionChecker('link_edit')) : ?>
                                                                                <li>
                                                                                    <a href="<?php echo base_url('courses/edit_link/') . $id . '?course=' . $course->id; ?>">Edit Link</a>
                                                                                </li>
                                                                                <?php endif; ?>
                                                                            </ul>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                       
                                                                    <?php } ?>
                                                                    <?php if ($type == 'quiz') { ?>

                                                                        <?php if(
                                                                              permissionChecker('quiz_edit')
                                                                            || permissionChecker('quiz_delete') 
                                                                            
                                                                        ) : ?>
                                                                       
                                                                        <div class="dropdown">
                                                                            <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                                            <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                                                <li>
                                                                                    <a href="<?php echo base_url() . 'courses/addquestion/' . $id . '/' . $chapter->id . '?course=' . $course->id ?>">Add Question</a>
                                                                                </li>
                                                                                <?php if (permissionChecker('quiz_edit')) : ?>
                                                                                <li>
                                                                                    <a href="<?php echo base_url() . 'courses/new_quiz_ui/' . $id . '/' . $chapter->id . '?course=' . $course->id ?>">Edit Quiz</a>
                                                                                </li>
                                                                                <?php endif; ?>

                                                                                <?php if (permissionChecker('quiz_delete')) : ?>
                                                                                <li>
                                                                                    <a onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" href="<?php echo base_url('courses/deletequiz/') . $id . '?course=' . $course->id; ?>">Delete Quiz</a>
                                                                                </li>
                                                                                <?php endif; ?>
                                                                            </ul>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                       
                                                                    <?php } ?>
                                                                    <?php if ($type == 'assignment') { ?>

                                                                        <?php if(
                                                                              permissionChecker('assignment_view')
                                                                            || permissionChecker('assignment_edit') 
                                                                            || permissionChecker('assignment_delete') 
                                                                            
                                                                        ) : ?>
                                                                        <div class="dropdown">
                                                                            <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                                            <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                                                <li>
                                                                                    <?php if (permissionChecker('assignment_view')) : ?>
                                                                                        <a href="<?= base_url('assignment/view/' . $id . '/' . $set . '?course=' . $course->id) ?>">View Submission</a>
                                                                                    <?php endif; ?>
                                                                                </li>
                                                                                <li>
                                                                                    <?php if (permissionChecker('assignment_edit')) : ?>
                                                                                        <?php if (($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($usertypeID == 1)) : ?>
                                                                                            <a href="<?= base_url('assignment/edit/' . $id . '/' . $set . '?course=' . $course->id) ?>">Edit</a>
                                                                                        <?php endif; ?>
                                                                                    <?php endif; ?>
                                                                                </li>
                                                                                <li>
                                                                                    <?php if (permissionChecker('assignment_delete')) : ?>
                                                                                        <?php if (($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($usertypeID == 1)) : ?>
                                                                                            <a href="<?= base_url('assignment/delete/' . $id . '/' . $set . '?course=' . $course->id) ?>" onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')">Delete</a>

                                                                                        <?php endif; ?>
                                                                                    <?php endif; ?>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                    <?php } ?>
                                                                    <?php if ($type == 'homework') { ?>
                                                                        <?php if(
                                                                              permissionChecker('homework_view')
                                                                            || permissionChecker('homework_edit') 
                                                                            || permissionChecker('homework_delete') 
                                                                            
                                                                        ) : ?>
                                                                        <div class="dropdown">
                                                                            <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                                            <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                                                <li>
                                                                                    <?php if (permissionChecker('homework_view')) : ?>
                                                                                        <a href="<?= base_url('homework/view/' . $id . '/' . $set . '?course=' . $course->id) ?>">View Submission</a>
                                                                                    <?php endif; ?>
                                                                                </li>
                                                                                <li>
                                                                                    <?php if (permissionChecker('homework_edit')) : ?>
                                                                                        <?php if (($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($usertypeID == 1)) : ?>
                                                                                            <a href="<?= base_url('homework/edit/' . $id . '/' . $set . '?course=' . $course->id) ?>">Edit</a>
                                                                                        <?php endif; ?>
                                                                                    <?php endif; ?>
                                                                                </li>
                                                                                <li>
                                                                                    <?php if (permissionChecker('homework_delete')) : ?>
                                                                                        <?php if (($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($usertypeID == 1)) : ?>
                                                                                            <a href="<?= base_url('homework/delete/' . $id . '/' . $set . '?course=' . $course->id) ?>" onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')">Delete</a>

                                                                                        <?php endif; ?>
                                                                                    <?php endif; ?>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                    <?php } ?>
                                                                    <?php if ($type == 'classwork') { ?>
                                                                        <?php if(
                                                                              permissionChecker('classwork_view')
                                                                            || permissionChecker('classwork_edit') 
                                                                            || permissionChecker('classwork_delete') 
                                                                            
                                                                        ) : ?>
                                                                        <div class="dropdown">
                                                                            <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                                            <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                                                <li>
                                                                                    <?php if (permissionChecker('classwork_view')) : ?>
                                                                                        <a href="<?= base_url('classwork/view/' . $id . '/' . $set . '?course=' . $course->id) ?>">View Submission</a>
                                                                                    <?php endif; ?>
                                                                                </li>
                                                                                <li>
                                                                                    <?php if (permissionChecker('classwork_edit')) : ?>
                                                                                        <?php if (($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($usertypeID == 1)) : ?>
                                                                                            <a href="<?= base_url('classwork/edit/' . $id . '/' . $set . '?course=' . $course->id) ?>">Edit</a>
                                                                                        <?php endif; ?>
                                                                                    <?php endif; ?>
                                                                                </li>
                                                                                <li>
                                                                                    <?php if (permissionChecker('classwork_delete')) : ?>
                                                                                        <?php if (($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($usertypeID == 1)) : ?>
                                                                                            <a href="<?= base_url('classwork/delete/' . $id . '/' . $set . '?course=' . $course->id) ?>" onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')">Delete</a>

                                                                                        <?php endif; ?>
                                                                                    <?php endif; ?>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                    <?php } ?>
                                                                    <?php if ($type == 'content') { ?>
                                                                        <?php if(
                                                                              permissionChecker('content_edit')
                                                                            || permissionChecker('content_delete') 
                                                                            
                                                                        ) : ?>
                                                                        <div class="dropdown">
                                                                            <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                                                            <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                                            <?php if (permissionChecker('content_edit')) : ?>
                                                                                <li>
                                                                                    <a href="<?php echo base_url() . 'courses/editcontent/' . $id . '?course=' . $course->id ?>">Edit Content</a>
                                                                                </li>
                                                                                <?php endif; ?>
                                                                                <?php if (permissionChecker('content_delete')) : ?>
                                                                                <li>
                                                                                    <a onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" href="<?php echo base_url('courses/deletecontent/') . $id . '?course=' . $course->id; ?>">Delete Content</a>
                                                                                </li>
                                                                                <?php endif; ?>
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


<!-- add course modal starts -->
<div class="modal fade" tabindex="-1" role="dialog" id="addContent">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Add new content</h3>
            </div>
            <div class="modal-body">
                <form method="post">

                    <div class="form-group">
                        <div class="md-form md-form--select">
                            <?php
                            $array = array();
                            $array[0] = $this->lang->line("select_unit");
                            foreach ($units as $unit) {
                                $array[$unit->id] = $unit->unit_name;
                            }
                            echo form_dropdown("unit_id", $array, set_value("unit_id"), "id='unit_id' class='mdb-select'");
                            ?>
                            <label for="" class="mdb-main-label">Select Unit</label>
                            <span class="text-danger error">
                                <p id="unit-error"></p>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="md-form md-form--select">
                            <?php
                            $array = array();
                            $array[0] = $this->lang->line("select_chapter");
                            foreach ($chapters as $chapter) {
                                $array[$chapter->id] = $chapter->chapter_name;
                            }
                            echo form_dropdown("chapter_id", $array, set_value("chapter_id"), "id='chapter_id' class='mdb-select'");
                            ?>
                            <label for="" class="mdb-main-label">Select Chapter</label>
                            <span class="text-danger error">
                                <p id="chapter-error"></p>
                            </span>
                        </div>
                    </div>
                </form>
                <input type="hidden" id="ajax-get-chapter-url" value="<?php echo base_url() ?>chapter/ajaxGetChaptersFromUnitId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="add-content" class="btn btn-primary">Add Content</button>
                <!-- <button type="button" id="add-lesson" class="btn btn-primary">Add Lesson Plan</button> -->
                <!-- <button type="button" id="add-daily" class="btn btn-primary">Add Daily Plan</button> -->
                <button type="button" id="add-quiz" class="btn btn-primary">Add Quiz</button>
                <button type="button" id="add-attachment" class="btn btn-primary">Add Attachment</button>
                <button type="button" id="add-link" class="btn btn-primary">Add Link</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- add course modal ends -->



<!-- add unit modal starts -->
<div class="modal fade" tabindex="-1" role="dialog" id="addUnit">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Add new content</h3>
            </div>
            <form class="" role="form" method="post" id="add-unit">
                <div class="modal-body">


                    <div class="form-group ">
                        <div class="md-form md-form--select">
                            <input type="text" class="form-control" id="class_name" name="class_name" value="<?php echo $classes->classes; ?>" readOnly>
                            <input type="hidden" name="classesID" value="<?php echo $classes->classesID; ?>">
                            <label for="" class="mdb-main-label">Select Class</label>
                            <span class="text-danger error">
                                <p id="class-error"></p>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="md-form md-form--select">
                            <input type="text" id="" name="subject_name" value="<?php echo $subjects->subject; ?>" readOnly class="form-control">
                            <input type="hidden" name="subject_id" id="subject_id" value="<?php echo $subjects->subjectID; ?>">
                            <label for="" class="mdb-main-label">Select Subject</label>
                            <span class="text-danger error">
                                <p id="chapter-error"></p>
                            </span>
                        </div>
                    </div>

                    <div class='form-group'>
                        <div class="md-form">
                            <label for="unit_name">Unit name</label>
                            <input type="text" class="form-control" id="unit_name" name="unit_name" value="">
                            <span class="text-danger error">
                                <p id="unit-error1"></p>
                            </span>
                        </div>
                    </div>

                    <div class='form-group'>
                        <div class="md-form">
                            <label for="unit_code">Unit code</label>
                            <input type="text" class="form-control" id="unit_code" name="unit_code" value="">
                            <span class="text-danger error">
                                <p id="unit-code-error"></p>
                            </span>
                        </div>
                    </div>



                    <input type="hidden" id="ajax-get-chapter-url" value="<?php echo base_url() ?>chapter/ajaxGetChaptersFromUnitId">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Unit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- add course modal ends -->

<!-- add course modal starts -->
<div class="modal fade" tabindex="-1" role="dialog" id="addChapter">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Add new Chapter</h3>
            </div>
            <div class="modal-body">
                <form class="" role="form" method="post" id="">
                    <!-- <span class="text-danger error">
                        <p id="chapter-error2"></p>
                    </span> -->
                    <div class="form-group ">
                        <div class="md-form">
                            <input type="text" class="form-control" id="class_name" name="class_name" value="<?php echo $classes->classes; ?>" readOnly>
                            <input type="hidden" name="classesID1" id="classesID1" value="<?php echo $classes->classesID; ?>">
                            <label for="" class="mdb-main-label">Select Class</label>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="md-form">
                            <input type="text" id="" name="subject_name" value="<?php echo $subjects->subject; ?>" readOnly class="form-control">
                            <input type="hidden" name="subject_id1" id="subject_id1" value="<?php echo $subjects->subjectID; ?>">
                            <label for="" class="mdb-main-label">Select Subject</label>

                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="md-form md-form--select">
                            <?php
                            $array = array();
                            $array[''] = $this->lang->line("select_unit");
                            foreach ($units as $unit) {
                                $array[$unit->id] = $unit->unit_name;
                            }
                            echo form_dropdown("unit_id", $array, set_value("unit_id"), "id='unit_id1' class='mdb-select'");
                            ?>
                            <label for="unit_id" class="mdb-main-label">Select Unit</label>
                            <span class="text-danger error">
                                <p id="unit-error2"></p>
                            </span>
                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="md-form ">
                            <label for="chapter_name">Chapter Name</label>
                            <input type="text" class="form-control" id="chapter_name" name="chapter_name" value="">
                            <span class="text-danger error">
                                <p id="chapterName-error2"></p>
                            </span>
                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="md-form ">
                            <label for="chapter_code">Chapter code</label>
                            <input type="text" class="form-control" id="chapter_code" name="chapter_code" value="">
                            <span class="text-danger error">
                                <p id="chapter-code-error2"></p>
                            </span>
                        </div>
                    </div>


                </form>

                <input type="hidden" id="ajax-get-chapter-url" value="<?php echo base_url() ?>chapter/ajaxGetChaptersFromUnitId">
            </div>
            <div class="modal-footer">

                <button type="button" id="add-chapter" class="btn btn-primary">Add Chapter</button>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- add course modal ends -->

<!-- add quiz modal starts -->
<div class="modal fade" tabindex="-1" role="dialog" id="addQuiz1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Add new quiz</h3>
            </div>
            <div class="modal-body">
                <form method="post">
                    <span class="text-danger error">
                        <p id="form-error"></p>
                    </span>
                    <div class="form-group ">
                        <div class="md-form md-form--select">
                            <?php
                            $array    = array();
                            $array[0] = $this->lang->line("select_unit");
                            foreach ($units as $unit) {
                                $array[$unit->id] = $unit->unit_name;
                            }
                            echo form_dropdown("unit_id", $array, set_value("unit_id"), "id='unit_id11' class='mdb-select'");
                            ?>
                            <label for="" class="mdb-main-label">Select Unit</label>
                            <span class="text-danger error">
                                <p id="unit-error11"></p>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="md-form md-form--select">
                            <?php
                            $array    = array();
                            $array[0] = $this->lang->line("select_chapter");
                            foreach ($chapters as $chapter) {
                                $array[$chapter->id] = $chapter->chapter_name;
                            }
                            echo form_dropdown("chapter_id", $array, set_value("chapter_id"), "id='chapter_id11' class='mdb-select'");
                            ?>
                            <label for="" class="mdb-main-label">Select Chapter</label>
                            <span class="text-danger error">
                                <p id="chapter-error11"></p>
                            </span>
                        </div>
                    </div>

                    <div class='form-group'>
                        <div class="md-form">
                            <label for="quiz_name">Quiz Title</label>
                            <input type="text" class="form-control" id="quiz_name11" name="quiz_name">
                            <span class="text-danger error">
                                <p id="title-error11"></p>
                            </span>
                        </div>
                    </div>

                    <div class='form-group'>
                        <div class="md-form">
                            <label for="percentage_coverage">Percentage</label>
                            <input type="text" class="form-control" id="percentage_coverage11" name="percentage_coverage">
                            <span class="text-danger error">
                                <p id="percentage-error11"></p>
                            </span>
                        </div>
                    </div>
                </form>
                <input type="hidden" id="ajax-get-chapter-url" value="<?php echo base_url() ?>chapter/ajaxGetChaptersFromUnitId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="add-quiz1" class="btn btn-primary">Add Quiz</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- add quiz modal ends -->


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
    $('#add_unit').click(function(e) {
        $('.modal-title').text('Add new Unit');
        $('#addUnit').show();

    });

    $('#add_chapter').click(function(e) {
        $('.modal-title').text('Add new Chapter');
        $('#addChapter').show();

    });

    $('#add_lesson').click(function(e) {
        $('.modal-title').text('Add new Lesson Plan');
        $('#add-lesson').show();
        $('#add-daily').hide();
        $('#add-content').hide();
        $('#add-quiz').hide();
        $('#add-attachment').hide();
        $('#add-link').hide();
    });

    $('#add_daily').click(function(e) {
        $('.modal-title').text('Add new Daily Plan');
        $('#add-daily').show();
        $('#add-lesson').hide()
        $('#add-content').hide();
        $('#add-quiz').hide();
        $('#add-attachment').hide();
        $('#add-link').hide();
    });

    $('#add_content').click(function(e) {
        $('.modal-title').text('Add new Content');
        $('#add-content').show();
        $('#add-lesson').hide();
        $('#add-daily').hide();
        $('#add-quiz').hide();
        $('#add-attachment').hide();
        $('#add-link').hide();
    });

    $('#add_quiz').click(function(e) {
        $('.modal-title').text('Add new Quiz');
        $('#add-content').hide();
        $('#add-lesson').hide();
        $('#add-daily').hide();
        $('#add-quiz').show();
        $('#add-attachment').hide();
        $('#add-link').hide();
    });

    $('#add_attachment').click(function(e) {
        $('.modal-title').text('Add new Attachment');
        $('#add-content').hide();
        $('#add-lesson').hide();
        $('#add-daily').hide();
        $('#add-quiz').hide();
        $('#add-attachment').show();
        $('#add-link').hide();
    });

    $('#add_link').click(function(e) {
        $('.modal-title').text('Add new Link');
        $('#add-content').hide();
        $('#add-lesson').hide();
        $('#add-daily').hide();
        $('#add-quiz').hide();
        $('#add-attachment').hide();
        $('#add-link').show();
    });

    $(document).on('change', '#unit_id', function() {
        let unit_id = $(this).val();
        let url = $('#ajax-get-chapter-url').val()

        $.ajax({
            url: url + "?unit_id=" + unit_id,
        }).done(function(data) {
            $('#chapter_id').html(data);
            $('.mdb-select').material_select('destroy');
            $('.mdb-select').material_select();
        });
    })

    $(document).on('change', '#unit_id11', function() {
        let unit_id = $(this).val();
        let url = $('#ajax-get-chapter-url').val()

        $.ajax({
            url: url + "?unit_id=" + unit_id,
        }).done(function(data) {
            $('#chapter_id11').html(data);
            $('.mdb-select').material_select('destroy');
            $('.mdb-select').material_select();
        });
    })

    $('#add-content').click(function(e) {
        unit_id = $('#unit_id').val();
        chapter_id = $('#chapter_id').val();

        // $('#unit-error').text('');
        // $('#chapter-error').text('');
        // if (unit_id == 0) {
        //     $('#unit-error').text('Unit is empty.');
        // }
        // if (chapter_id == 0) {
        //     $('#chapter-error').text('Chapter is empty.');
        // }
        // if (unit_id != 0 && chapter_id != 0) {
        window.location.href = "<?php echo base_url('courses/addcontent/'); ?>" + chapter_id + "?course=" + <?php echo $course->id ?>
        // }
    })

    $('#add-lesson').click(function(e) {
        unit_id = $('#unit_id').val();
        chapter_id = $('#chapter_id').val();
        window.location.href = "<?php echo base_url('lesson_plan/add/'); ?>" + <?php echo $course->id ?> + "?course=" + chapter_id + "&unit=" + unit_id;

    })

    $('#add-daily').click(function(e) {
        unit_id = $('#unit_id').val();
        chapter_id = $('#chapter_id').val();
        window.location.href = "<?php echo base_url('daily_plan/add/'); ?>" + <?php echo $course->id ?> + "?course=" + chapter_id + "&unit=" + unit_id;

    })



    $('#add-attachment').click(function(e) {
        unit_id = $('#unit_id').val();
        chapter_id = $('#chapter_id').val();

        // $('#unit-error').text('');
        // $('#chapter-error').text('');
        // if (unit_id == 0) {
        //     $('#unit-error').text('Unit is empty.');
        // }
        // if (chapter_id == 0) {
        //     $('#chapter-error').text('Chapter is empty.');
        // }
        // if (unit_id != 0 && chapter_id != 0) {
        window.location.href = "<?php echo base_url('courses/addfiles/'); ?>" + chapter_id + "?course=" + <?php echo $course->id ?>
        // }
    })

    $('#add-link').click(function(e) {
        unit_id = $('#unit_id').val();
        chapter_id = $('#chapter_id').val();

        // $('#unit-error').text('');
        // $('#chapter-error').text('');
        // if (unit_id == 0) {
        //     $('#unit-error').text('Unit is empty.');
        // }
        // if (chapter_id == 0) {
        //     $('#chapter-error').text('Chapter is empty.');
        // }
        // if (unit_id != 0 && chapter_id != 0) {
        window.location.href = "<?php echo base_url('courses/addlinks/'); ?>" + chapter_id + "?course=" + <?php echo $course->id ?>
        // }
    })

    $('#add-quiz').click(function(e) {
        unit_id = $('#unit_id').val();
        chapter_id = $('#chapter_id').val();

        $('#unit-error').text('');
        $('#chapter-error').text('');
        // if (unit_id == 0) {
        //     $('#unit-error').text('Unit is empty.');
        // }
        // if (chapter_id == 0) {
        //     $('#chapter-error').text('Chapter is empty.');
        // }
        // if (unit_id != 0 && chapter_id != 0) {
        window.location.href = "<?php echo base_url('courses/addquizzes/'); ?>" + chapter_id + "?course=" + <?php echo $course->id ?>
        // }
    });

    $('#add-quiz1').click(function(e) {
        unit_id = $('#unit_id11').val();
        chapter_id = $('#chapter_id11').val();
        quiz_name = $('#quiz_name11').val();
        percentage_coverage = $('#percentage_coverage11').val();
        course_id = "<?php echo $course->id; ?>"

        $('#unit-error11').text('');
        $('#chapter-error11').text('');
        $('#title-error11').text('');
        $('#percentage-error11').text('');
        $('#form-error11').text('');

        if (quiz_name == 0) {
            $('#title-error11').text('Title is empty.');
        }
        if (percentage_coverage == 0) {
            $('#percentage-error11').text('Percentage Coverage is empty.');
        }

        // if (unit_id != 0 && chapter_id != 0) {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('courses/ajax_addquizzes') ?>",
            data: 'chapter_id=' + chapter_id + '&course_id=' + course_id + '&quiz_name=' + quiz_name + '&percentage_coverage=' + percentage_coverage + '&course_id=' + course_id,
            dataType: "html",
            success: function(data) {

                var response = jQuery.parseJSON(data);

                if (response.status) {
                    if (response.render) {
                        window.location.href = "<?php echo base_url('courses/new_quiz_ui/'); ?>" + response.id + "/" + chapter_id + "?course=" + course_id
                    }
                    if (response.error) {
                        // console.log(response.error.percentage_coverage);
                        if (response.error.percentage_coverage) {
                            $('#percentage-error11').html(response.error.percentage_coverage);
                        }
                        if (response.error.quiz_name) {
                            $('#title-error11').html(response.error.quiz_name);
                        }
                    }
                }
            }
        });
        // }
    });


    $('body').on('submit', '#add-unit', function(e) {
        e.preventDefault();
        var frm = $('#add-unit');
        $('#unit-error1').text('');
        $('#unit-code-error').text('');

        $.ajax({
            url: '<?php echo base_url('unit/ajaxInsertUnit'); ?>',
            method: 'post',
            data: frm.serialize(),
            dataType: 'html',
            success: function(res) {
                var response = jQuery.parseJSON(res);
                console.log(response);
                if (response.status) {
                    if (response.render) {
                        toastr["success"](response.message)
                        location.reload();

                    } else {
                        $('#unit-error1').html(response.unit_error);
                        $('#unit-code-error').html(response.unit_code_error);
                    }
                   
                }

            }
        });


    });

    

    $('#add-chapter').click(function(e) {
        classesID = $('#classesID1').val();
        subject_id = $('#subject_id1').val();
        unit_id = $('#unit_id1').val();
        chapter_name = $('#chapter_name').val();
        chapter_code = $('#chapter_code').val();

        $('#chapterName-error2').text('');
        $('#unit-error2').text('');
        $('#chapter-code-error2').text('');

        $.ajax({
            url: '<?php echo base_url('chapter/ajaxInsertChapter'); ?>',
            method: 'post',
            data: {
                classesID: classesID,
                subject_id: subject_id,
                unit_id: unit_id,
                chapter_name: chapter_name,
                chapter_code: chapter_code
            },
            dataType: 'html',
            success: function(res) {
                var response = jQuery.parseJSON(res);

                if (response.status) {
                    if (response.render) {
                        toastr["success"](response.message)
                        location.reload();

                    } else {
                        console.log(response);
                        $('#unit-error2').html(response.unit_error);
                        $('#chapterName-error2').html(response.chapter_name_error);
                        $('#chapter-code-error2').html(response.chapter_code_error);

                    }
                }

            }
        });
    })




    $(document).ready(function() {
        $(".end-virtual-class").hide();
        $(".start-virtual-class").hide();
        $(".join-virtual-class").hide();

        $(".virtual-class").each(function() {
            // Test if the div element is empty
            id = $(this).attr("id");
            checkIfMeetingRunning(id);
        });
    });

    setInterval(function() {
        $(".virtual-class").each(function() {
            // Test if the div element is empty
            id = $(this).attr("id");
            checkIfMeetingRunning(id);
        });
    }, 20000);

    $('.onoffswitch-small').click(function(e) {
        $(this).parent().submit();
    })

    $('.start-virtual-class').click(function(e) {
        var start = $(this).attr("class").match(/start-virtual-class-[\w-]*\b/)
        var id = start[0].replace('start-virtual-class-', '')
        $.ajax({
            url: "<?= base_url('courses/start') ?>",
            type: 'post',
            data: {
                id: id
            },
            success: function(result) {
                if (result) {
                    console.log(result);
                    window.open(result, '_blank');
                    checkIfMeetingRunning(id);
                }
            }
        });
    });

    $('.end-virtual-class').click(function(e) {
        var end = $(this).attr("class").match(/end-virtual-class-[\w-]*\b/)
        var id = end[0].replace('end-virtual-class-', '')
        $.ajax({
            url: "<?= base_url('courses/end') ?>",
            type: 'post',
            data: {
                id: id
            },
            success: function(result) {
                if (result) {
                    console.log(result);
                    checkIfMeetingRunning(id);
                }
            }
        });
    });


    function checkIfMeetingRunning(id) {
        $.ajax({
            url: "<?= base_url('courses/ajaxCheckIfMeetingRunning') ?>",
            type: 'post',
            data: {
                id: id
            },
            success: function(result) {
                if (result == 1) {
                    $(".end-virtual-class-" + id).show();
                    $(".join-virtual-class-" + id).show();
                    $(".start-virtual-class-" + id).hide();
                } else if (result == 2) {
                    $(".end-virtual-class-" + id).hide();
                    $(".join-virtual-class-" + id).hide();
                    $(".start-virtual-class-" + id).hide();
                } else {
                    $(".end-virtual-class-" + id).hide();
                    $(".join-virtual-class-" + id).hide();
                    $(".start-virtual-class-" + id).show();
                }
            }
        });
    }

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
        if (data.includes('chapter')) {
            unit_id = data.replace("chapter", "");
            if ($("#chapter" + unit_id).parent().find('div:first-child').hasClass('collapsed')) {
                console.log('has collapsed');
                $("#chapter" + unit_id).parent().find('div:first-child').removeClass('collapsed');
                $("#chapter" + unit_id).parent().find('div:first-child').addClass('in');
            } else {
                console.log('true');
                $("#chapter" + unit_id).parent().find('div:first-child').addClass('collapsed');
                $("#chapter" + unit_id).parent().find('div:first-child').removeClass('in');
            }
        }
    }

    $(document).ready(function() {
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
                    if (array[i].split('/')[1].includes('chapter')) {
                        unit_id = array[i].split('/')[1].replace("chapter", "");
                        $("#chapter" + unit_id).parent().find('div:first-child').addClass('in');
                        $("#chapter" + unit_id).parent().find('div:first-child').removeClass('collapsed');
                    }
                }
            }
        }
    });


    $(document).ready(function() {
        <?php if (permissionChecker('courses_view')) { ?>
            chapter_ids = <?php echo json_encode($chapter_ids) ?>;
            chapter_ids.forEach(function(chapterId) {
                var element = document.getElementById('content' + chapterId);
                var options = {
                    group: 'share' + chapterId,
                    animation: 100,
                    handle: ".panned-icon"

                };
                if (element != null) {
                    events = [
                        'onChange'
                    ].forEach(function(name) {
                        options[name] = function(evt) {
                            dataset = evt.clone.dataset;
                            var chapterId = dataset.chapterId;
                            var positions = [];
                            $('#content' + chapterId).children('li').each(function(li) {
                                var listIndexValue = $(this).data('index');
                                var index;
                                if (listIndexValue == evt.newIndex) {
                                    index = evt.oldIndex;
                                    $(this).attr('data-index', evt.oldIndex);
                                } else if (listIndexValue == evt.oldIndex) {
                                    index = evt.newIndex;
                                    $(this).attr('data-index', evt.newIndex);
                                } else {
                                    index = listIndexValue;
                                }

                                positions.push({
                                    position: index,
                                    type: $(this).data('type'),
                                    rowId: $(this).data('row-id')
                                });
                            });

                            $.ajax({
                                url: "<?= base_url('courses/changeOrder/') ?>",
                                type: "post",
                                data: {
                                    positions: JSON.stringify(positions)
                                },
                                success: function(response) {

                                }
                            });

                        };
                    });
                    Sortable.create(element, options);
                }
            });
        <?php } ?>
    });
</script>

<?php if (permissionChecker('courses_view')) : ?>
    <script>
        let assignment_url = '<?= base_url("/courses/assignmentStatus/") ?>';
        let homework_url = '<?= base_url("/courses/homeworkStatus/") ?>';
        let classwork_url = '<?= base_url("/courses/classworkStatus/") ?>';
        let unit_url = "<?= base_url('courses/ajaxChangeUnitStatus/') ?>";
        let chapter_url = "<?= base_url('courses/ajaxChangeChapterStatus/') ?>";
        let content_url = "<?= base_url('courses/ajaxChangeContentStatus/') ?>";
        let link_url = "<?= base_url('courses/ajaxChangeLinkStatus/') ?>";
        let attachment_url = "<?= base_url('courses/ajaxChangeFileStatus/') ?>";
        let quiz_url = "<?= base_url('courses/ajaxChangeQuizStatus/') ?>";
        let course_url = "<?= base_url('courses/ajaxChangeCourseStatus/') ?>";

        function changeStatus(id, type) {
            if (type == 'assignment') {
                url = assignment_url
            } else if (type == 'homework') {
                url = homework_url
            } else if (type == 'classwork') {
                url = classwork_url
            } else if (type == 'unit') {
                url = unit_url
            } else if (type == 'chapter') {
                url = chapter_url
            } else if (type == 'content') {
                url = content_url
            } else if (type == 'link') {
                url = link_url
            } else if (type == 'attachment') {
                url = attachment_url
            } else if (type == 'quiz') {
                url = quiz_url
            } else if (type == 'course') {
                url = course_url
            }
            $.post(url + id).done(function() {
                $('#loading').hide();
                showSuccessToast();
            }).fail(function(error) {
                $('#loading').hide();
                showFailureToast();
            });
        }
    </script>

<script>
        $('.course-content .sort-type').on('click', function() {
            // var aa = $('#disable');
            // console.log(aa);
            // aa.attr("disabled",true);
            $("li").addClass('disabled');

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
                    is_student_view: 2
                },
                success: function(data) {
                    // console.log(data);
                    $("li").removeClass('disabled');
                    $('#viewAttachment').modal('show');
                    $('#view_ajax_attachment').append(data);

                }
            });
        });
    </script>

    <script>
        $('body').on('submit', '#add-chapter-with-unit, #edit-chapter-with-unit ', function(e) {
            e.preventDefault();
            $('.chapter-error').text('');
            $('.chapter-code-error').text('');
            var frm = $('#add-chapter-with-unit, #edit-chapter-with-unit');
            $.ajax({
                url: frm.attr('action'),
                method: 'post',
                data: frm.serialize(),
                dataType: 'html',
                success: function(data) {

                    var response = jQuery.parseJSON(data);

                    if (response.status) {
                        if (response.render) {
                            toastr["success"](response.message)
                            location.reload();
                        }

                       else{
                            $('.chapter-error').html(response.chapter_name_error);
                            $('.chapter-code-error').html(response.chapter_code_error);
                        }
                    }
                }
            });

        });
    </script>
<?php endif; ?>