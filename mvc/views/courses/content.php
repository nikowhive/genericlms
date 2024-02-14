<style type="text/css">
    .content {
        margin-right: auto;
        /* display: flex; */
        flex-direction: row;
        justify-content: space-between;
        /* margin-left: auto; */
        padding-right: 15px;
        padding-left: 15px;
        /* justify-content: space-between; */
    }

    .image_col-2 {
        /* min-width: 700px; */
        /* background-color: #eaeaea; */
    }

    .image_col-2-wrapper {
        padding: 40px;
        height: 100%;
    }

    .mytext {
        display: none;
    }

    .content-desc {
        overflow-wrap: anywhere;
    }

    .card-body .read-more a {
        color: #236d37;
        font-size: 13px;
        font-weight: bold;
        float: right;
    }

    strong{
        font-weight: bold;
    }

</style>

<div class="row">
    <div class="content">
        <div class="col-sm-8">
            <!--Accordion wrapper-->
            <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">

                <?php if ($chapters) { ?>
                    <?php if ($chapters->lists) { ?>
                        <?php foreach ($chapters->lists as $z => $list) {
                            if (isset($list->attachment)) {
                                $title = "Attachment";
                                $faIcon = checkFileExtension($list->attachment);
                                $type = 'attachment';
                                $titleValue = $list->file_name;
                                $file = $list->attachment;
                                $id = $list->id;
                                $published = $list->published;
                                $class = "view-attachment";
                            } else if (isset($list->content_title)) {
                                $title = "Content";
                                $faIcon = "fa-book";
                                $type = 'content';
                                $titleValue = $list->content_title;
                                $desc = $list->chapter_content;
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
                                $file = $list->file;
                                $desc = $list->description;
                                $titleValue = $list->title;
                                $id = $list->classworkID;
                                $published = $list->is_published;
                            } else if (isset($list->homeworkID)) {
                                $title = "Homework";
                                $faIcon = "fa-book";
                                $type = 'homework';
                                $file = $list->file;
                                $desc = $list->description;
                                $titleValue = $list->title;
                                $id = $list->homeworkID;
                                $published = $list->is_published;
                            } else if (isset($list->assignmentID)) {
                                $title = "Assignment";
                                $faIcon = "fa-book";
                                $type = 'assignment';
                                $file = $list->file;
                                $desc = $list->description;
                                $titleValue = $list->title;
                                $id = $list->assignmentID;
                                $published = $list->is_published;
                            } else if (isset($list->courselink)) {
                                $title = "Link";
                                $faIcon = checkLinkType($list->type);
                                $type = 'link';
                                $url = $list->courselink;
                                $link_type = $list->type;
                                $titleValue = namesorting($list->courselink, 30);
                                $id = $list->id;
                                $published = $list->published;
                            } ?>

                            <?php if ($type == 'content') { ?>
                                <div class="card">
                                    <!-- Card header -->
                                    <div class="card-header" role="tab" id="content1<?= $id ?>">
                                        <a data-toggle="collapse" data-parent="#accordionEx1" href="#collapseOne<?= $type ?><?= $id ?>" aria-expanded="true" aria-controls="collapseOne<?= $type ?><?= $id ?>" class="collapsed">
                                            <h3 class="mb-0"><?= $title ?>:
                                                <?= $titleValue ?> <i class="fa fa-angle-down rotate-icon"></i>
                                            </h3>
                                        </a>
                                    </div>

                                    <!-- Card body -->
                                    <div id="collapseOne<?= $type ?><?= $id ?>" class="collapse" role="tabpanel" aria-labelledby="collapseOne<?= $type ?><?= $id ?>" data-parent="#accordionEx1" style="height: 0px;">
                                        <div class="card-body">
                                            <div class="description more"><?php echo $desc  ?>
                                            </div>
                                            <?php if (strlen($desc) > 500) { ?>
                                                <div class="read-more"><a href="#" class=""><span>READ MORE</span></a></div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>
                            <?php } ?>


                            <?php if ($type == 'attachment') { ?>
                                <div class="card">
                                    <!-- Card header -->
                                    <div class="card-header" role="tab" id="attachment<?= $id ?>">
                                        <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne<?= $type ?><?= $id ?>" aria-expanded="true" aria-controls="collapseOne<?= $type ?><?= $id ?>" class="collapsed">
                                            <h3 class="mb-0"><?= $title ?>:
                                                <?= $titleValue ?> <i class="fa fa-angle-down rotate-icon"></i>
                                            </h3>
                                        </a>
                                    </div>

                                    <!-- Card body -->
                                    <div id="collapseOne<?= $type ?><?= $id ?>" class="collapse" role="tabpanel" aria-labelledby="attachment<?= $id ?>" data-parent="#accordionEx" style="height: 0px;">
                                        <div class="card-body">

                                            <?php
                                            $allowed = array('gif', 'png', 'jpg');
                                            $txt_ext = array('pdf', 'xlsx', 'docx', 'csv', 'doc', 'xls');
                                            $vdo_extension = array('mp4', 'mov', 'flv', 'avi');

                                            $ext = pathinfo($file, PATHINFO_EXTENSION);
                                            if (in_array($ext, $allowed)) {
                                                echo '<img width="100%" height="" src=' . base_url('uploads/images/') . $file . ' />';
                                            } elseif (in_array($ext, $txt_ext)) {
                                                echo '<a  type="button" class="btn btn-sm" role="button" target="_blank" rel="noopener noreferrer"  href="' . base_url('uploads/images/') . $file . '">Download this <b>' . $ext . ' </b>for preview</a>';
                                                // echo '<embed src="' . base_url('uploads/images/') . $file . '" width="600px" height="350px" />';
                                            } elseif (in_array($ext, $vdo_extension)) {
                                                echo '<video width="600" height="350" controls> <source src="' . base_url('uploads/images/') . $file . '" type="video/mp4"></source></video>';
                                            } else {
                                                echo '';
                                            }
                                            ?>
                                        </div>
                                    </div>

                                </div>
                            <?php } ?>

                            <?php if ($type == 'link') { ?>
                                <div class="card">
                                    <!-- Card header -->
                                    <div class="card-header" role="tab" id="link<?= $id ?>">
                                        <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne<?= $type ?><?= $id ?>" aria-expanded="true" aria-controls="collapseOne<?= $type ?><?= $id ?>" class="collapsed">
                                            <h3 class="mb-0"><?= $title ?>:
                                                <?= $titleValue ?> <i class="fa fa-angle-down rotate-icon"></i>
                                            </h3>
                                        </a>
                                    </div>

                                    <!-- Card body -->
                                    <div id="collapseOne<?= $type ?><?= $id ?>" class="collapse" role="tabpanel" aria-labelledby="link<?= $id ?>" data-parent="#accordionEx" style="height: 0px;">
                                        <div class="card-body">
                                            <?php
                                            $url1 = (strncasecmp('http://', $url, 7) && strncasecmp('https://', $url, 8) ? 'http://' : '') . $url;

                                            if ($link_type == 'Youtube') {
                                                $url1 = getEmbedUrl($url);
                                            }
                                            ?>
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe class="embed-responsive-item" scrolling="no" width="560" height="315" src="<?= $url1 ?>" allowfullscreen></iframe>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            <?php } ?>


                            <?php if ($type == 'quiz') { ?>
                                <div class="card">
                                    <!-- Card header -->
                                    <div class="card-header" role="tab" id="quiz<?= $id ?>">
                                        <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne<?= $type ?><?= $id ?>" aria-expanded="true" aria-controls="collapseOne<?= $type ?><?= $id ?>" class="collapsed">
                                            <h3 class="mb-0"><?= $title ?>:
                                                <?= $titleValue ?> <i class="fa fa-angle-down rotate-icon"></i>
                                            </h3>
                                        </a>
                                    </div>
                                    <!-- Card body -->
                                    <div id="collapseOne<?= $type ?><?= $id ?>" class="collapse" role="tabpanel" aria-labelledby="quiz<?= $id ?>" data-parent="#accordionEx" style="height: 0px;">
                                        <div class="card-body">
                                            <a href="<?php echo base_url() . 'courses/quiz/' . $id; ?>" class="lists-item-link" target="_blank">
                                                <span class="lists-item-label"><?= $titleValue ?>  <button class="btn btn-xs btn-primary"> Start Quiz</button></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ($type == 'assignment') {  ?>
                                <div class="card">
                                    <!-- Card header -->
                                    <div class="card-header" role="tab" id="assignment<?= $id ?>">
                                        <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne<?= $type ?><?= $id ?>" aria-expanded="true" aria-controls="collapseOne<?= $type ?><?= $id ?>" class="collapsed">
                                            <h3 class="mb-0"><?= $title ?>:
                                                <?= $titleValue ?> <i class="fa fa-angle-down rotate-icon"></i>
                                            </h3>
                                        </a>
                                    </div>
                                    <!-- Card body -->
                                    <div id="collapseOne<?= $type ?><?= $id ?>" class="collapse" role="tabpanel" aria-labelledby="assignment<?= $id ?>" data-parent="#accordionEx" style="height: 0px;">
                                        <div class="card-body">
                                            <div class="card-text">
                                                <?= (strlen($desc) > 200) ? substr($desc, 0, 200) . '...' : $desc; ?>
                                            </div>


                                            <a href="javascript:void(0)" class="viewAtt stretched-link mt-2 btn btn-xs btn-info waves-effect waves-light" data-id="<?= $id ?>" data-course="<?= $course_id ?>" title="">Show More</a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ($type == 'homework') { ?>
                                <div class="card">
                                    <!-- Card header -->
                                    <div class="card-header" role="tab" id="homework<?= $id ?>">
                                        <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne<?= $type ?><?= $id ?>" aria-expanded="true" aria-controls="collapseOne<?= $type ?><?= $id ?>" class="collapsed">
                                            <h3 class="mb-0"><?= $title ?>:
                                                <?= $titleValue ?> <i class="fa fa-angle-down rotate-icon"></i>
                                            </h3>
                                        </a>
                                    </div>
                                    <!-- Card body -->
                                    <div id="collapseOne<?= $type ?><?= $id ?>" class="collapse" role="tabpanel" aria-labelledby="homework<?= $id ?>" data-parent="#accordionEx" style="height: 0px;">
                                        <div class="card-body">
                                            <div class="card-text">
                                                <?= (strlen($desc) > 200) ? substr($desc, 0, 200) . '...' : $desc; ?>
                                            </div>


                                            <a href="javascript:void(0)" class="viewHomework stretched-link mt-2 btn btn-xs btn-info waves-effect waves-light" data-id="<?= $id ?>" data-course="<?= $course_id ?>" title="">Show More</a>
                                        </div>

                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ($type == 'classwork') { ?>
                                <div class="card">
                                    <!-- Card header -->
                                    <div class="card-header" role="tab" id="classwork<?= $id ?>">
                                        <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne<?= $type ?><?= $id ?>" aria-expanded="true" aria-controls="collapseOne<?= $type ?><?= $id ?>" class="collapsed">
                                            <h3 class="mb-0"><?= $title ?>:
                                                <?= $titleValue ?> <i class="fa fa-angle-down rotate-icon"></i>
                                            </h3>
                                        </a>
                                    </div>
                                    <!-- Card body -->
                                    <div id="collapseOne<?= $type ?><?= $id ?>" class="collapse" role="tabpanel" aria-labelledby="classwork<?= $id ?>" data-parent="#accordionEx" style="height: 0px;">
                                        <div class="card-body">
                                            <div class="card-text">
                                                <?= (strlen($desc) > 200) ? substr($desc, 0, 200) . '...' : $desc; ?>
                                            </div>


                                            <a href="javascript:void(0)" class="viewClasswork stretched-link mt-2 btn btn-xs btn-info waves-effect waves-light" data-id="<?= $id ?>" data-course="<?= $course_id ?>" title="">Show More</a>
                                        </div>
                                    </div>
                                </div>
                            <?php }

                            ?>



                        <?php     }
                        ?>

                    <?php } ?>
                <?php } ?>

            </div>
            <!-- Accordion wrapper -->
        </div>
        <div class="col-sm-4">
            <div class="boxes right-boxes   js-affix-top">
                <?php if ($usertypeID == 3) { ?>
                    <div class="box  outheBoxShadow">
                        <div class="box-body outheMargAndBox">
                            <div class="box-header bg-white">
                                <h3 class="box-title fontColor">
                                    Progress <span id="covered"><?= $covered ?></span> of <?= $totalCoverage ?>
                                    <br>
                                </h3>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($chapters) { ?>
                    <div class="box outheBoxShadow">
                        <div class="box-header">
                            <h3 class="box-title">
                                <?php echo $chapters->unit; ?>: <?= $chapters->chapter_name ?>
                            </h3>
                        </div>

                        <div class="box-body">
                            <?php if ($chapters->lists) { ?>
                                <ul class="lists">
                                    <?php foreach ($chapters->lists as $z => $list) {
                                        if (isset($list->attachment)) {
                                            $title = "Attachment";
                                            $faIcon = checkFileExtension($list->attachment);
                                            $type = 'attachment';
                                            $titleValue = $list->file_name;
                                            $id = $list->id;
                                            $published = $list->published;
                                            $class = "view-attachment";
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
                                        } ?>

                                        <?php if ($type == 'content') { ?>
                                            <li class="clickable-li lists-item" id="icon-<?= $id ?>" type=<?= $type ?>>
                                                <a href="#content<?= $id ?>" class="lists-item-link "><i class="fa <?= $faIcon ?>" aria-hidden="true"></i> <span class="lists-item-label"><?= $titleValue ?></span></a>
                                            </li>
                                        <?php } ?>


                                        <?php if ($type == 'attachment') { ?>
                                            <li class="clickable-li lists-item" id="icon-<?= $id ?>" type=<?= $type ?>>
                                                <a href="#attachment<?= $id ?>" class="lists-item-link "><i class="fa <?= $faIcon ?>" aria-hidden="true"></i> <span class="lists-item-label"><?= $titleValue ?></span></a>
                                            </li>
                                        <?php } ?>

                                        <?php if ($type == 'link') { ?>
                                            <li class="clickable-li lists-item" id="icon-<?= $id ?>" type=<?= $type ?>>
                                                <a href="#link<?= $id ?>" class="lists-item-link"><i class="fa <?= $faIcon ?>" aria-hidden="true"></i> <span class="lists-item-label"><?= namesorting($list->courselink, 30); ?></span></a>
                                            </li>
                                        <?php } ?>


                                        <?php if ($type == 'quiz') { ?>
                                            <li class="clickable-li lists-item" id="icon-<?= $id ?>" type=<?= $type ?>>
                                                <a href="#quiz<?= $id ?>" class="lists-item-link"><i class="fa <?= $faIcon ?>" aria-hidden="true"></i> <span class="lists-item-label"><?= $list->quiz_name ?></span></a>
                                            </li>
                                        <?php } ?>

                                        <?php if ($type == 'assignment') {  ?>
                                            <li class="clickable-li lists-item" id="icon-<?= $id ?>" type=<?= $type ?>>
                                                <a href="#assignment<?= $id ?>" class="lists-item-link"><i class="fa <?= $faIcon ?>" aria-hidden="true"></i> <span class="lists-item-label"><?= $list->title ?></span></a>
                                            </li>
                                        <?php } ?>

                                        <?php if ($type == 'homework') { ?>
                                            <li class="clickable-li lists-item" id="icon-<?= $id ?>" type=<?= $type ?>>
                                                <a href="#homework<?= $id ?>" class="lists-item-link"><i class="fa <?= $faIcon ?>" aria-hidden="true"></i> <span class="lists-item-label"><?= $list->title ?></span></a>
                                            </li>
                                        <?php } ?>

                                        <?php if ($type == 'classwork') { ?>
                                            <li class="clickable-li lists-item" id="icon-<?= $id ?>" type=<?= $type ?>>
                                                <a href="#classwork<?= $id ?>" class="lists-item-link"><i class="fa <?= $faIcon ?>" aria-hidden="true"></i> <span class="lists-item-label"><?= $list->title ?></span></a>
                                            </li>
                                        <?php }

                                        ?>



                                    <?php     }
                                    ?>



                                </ul>
                            <?php } ?>
                        </div>



                    </div>
                <?php } ?>


                <div class="box outheBoxShadow">

                    <div class="box-header ">
                        <h3 class="box-title  ">
                            Contents

                        </h3>
                    </div>
                    <?php if ($usertypeID == 3) { ?>
                        <div class="box-body margAndBox" style="">

                            <?php if ($contents) { ?>
                                <ul class="lists">
                                    <?php foreach ($contents as $index => $content) {
                                    ?>
                                        <li class="clickable-li lists-item" id="icon-<?= $content->id ?>" type="content" mytitle="<?= $content->content_title ?>" contentid="<?= $content->id ?>" content_coverage="<?= $content->percentage_coverage ?>">

                                            <a href="#content<?= $content->id ?>" class="lists-item-link">
                                                <span class="lists-item-label">
                                                    <?= $content->exists ? '<i style="float: right" class="fa fa-check-square-o"></i>' : '' ?>
                                                    <?= $index + 1 ?>)
                                                    <?= $content->content_title ?>
                                                </span>

                                            </a>
                                        </li>

                                    <?php } ?>
                                </ul>
                            <?php } else {
                                echo "There are no contents";
                            } ?>

                        </div>
                    <?php } else { ?>
                        <div class="box-body margAndBox" style="">

                            <?php if ($contents) { ?>
                                <ul class="lists">

                                    <?php foreach ($contents as $index => $content) {
                                    ?>

                                        <li class="clickable-li lists-item" id="icon-<?= $content->id ?>" type="content" mytitle="<?= $content->content_title ?>" contentid="<?= $content->id ?>" content_coverage="<?= $content->percentage_coverage ?>">
                                            <a href="#content<?= $content->id; ?>" class="lists-item-link">
                                                <span class="lists-item-label">
                                                    <?= $index + 1 ?>)
                                                    <?= $content->content_title ?>
                                                </span>
                                            </a>
                                        </li>

                                    <?php } ?>
                                </ul>
                            <?php } else {
                                echo "There are no contents";
                            } ?>

                        </div>
                    <?php } ?>

                </div>
                <div class="box outheBoxShadow">

                    <div class="box-header ">
                        <h3 class="box-title ">
                            Attachments
                            <br>
                        </h3>
                    </div>

                    <div class="box-body margAndBox" style="">

                        <?php if (isset($attachment) && !empty($attachment)) { ?>
                            <ul class="lists">
                                <?php

                                foreach ($attachment as $index => $att) { ?>
                                    <li class="clickable-li lists-item" id="icon-<?= $att->id ?>" type="attachment">
                                        <a href="#attachment<?= $att->id ?>" class="lists-item-link">
                                            <span class="lists-item-label">
                                                <?= $index + 1 ?>)
                                                <?= $att->file_name ?>
                                            </span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php  } else {
                            echo "There are no attachment";
                        } ?>
                        </nav>
                    </div>

                </div>

                <div class="box outheBoxShadow">

                    <div class="box-header  ">
                        <h3 class="box-title ">
                            Links
                        </h3>
                    </div>

                    <div class="box-body margAndBox" style="">

                        <?php
                        if ($link) { ?>
                            <ul class="lists">
                                <?php foreach ($link as $index => $att) { ?>
                                    <li class="clickable-li lists-item" id="icon-<?= $att->id ?>" type="link">
                                        <a href="#link<?= $att->id ?>" class="lists-item-link">
                                            <span class="lists-item-label">
                                                <?= $index + 1 ?>)
                                                <?= namesorting($att->courselink, 30); ?>
                                            </span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } else {
                            echo "There are no links";
                        } ?>


                    </div>

                </div>

                <div class="box outheBoxShadow">

                    <div class="box-header ">
                        <h3 class="box-title  ">
                            Take a quiz

                        </h3>
                    </div>

                    <div class="box-body margAndBox" style="">


                        <?php
                        if ($quizzes) { ?>
                            <ul class="lists">
                                <?php foreach ($quizzes as $quiz) { ?>
                                    <li class="lists-item">
                                        <a href="<?php echo base_url() . 'courses/quiz/' . $quiz->id; ?>" class="lists-item-link" target="_blank">
                                            <span class="lists-item-label"><?= $quiz->quiz_name ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } else {
                            echo "There are no quizzes";
                        } ?>


                    </div>

                </div>
            </div>


        </div>
    </div>


    <script type="text/javascript">
        $('#first-list').click(function() {
            $('#icon-1').click();
            $('#first-list').hide();
        })

        $('.clickable-li').click(function(title, text) {
            $('#first-list').hide();
            $('.image_col-2-wrapper .content-title').html($(this).attr("mytitle"));
            $('.image_col-2-wrapper .content-desc').html($(this).find(".mytext").html());

            chapter_id = "<?= $chapter_id ?>";

            id = $(this).attr('id');
            type = $(this).attr('type');
            index = id.replace(/icon-/g, '');

            $('#collapseOne' + type + index).removeClass('collapse').addClass('in');
            $('#collapseOne' + type + index).css('height', 'auto');

            content_id = $(this).attr("contentid");
            current_content = $(this)

            content_coverage = $(this).attr("content_coverage");

            current_coverage = $('#covered').text();
            if (type == 'content') {
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('courses/trackContentProgress') ?>",
                    data: {
                        "id": content_id,
                        "chapter_id": chapter_id
                    },
                    success: function(response) {

                        if (response != 0) {
                            $('#covered').text(parseInt(content_coverage) + parseInt(current_coverage));
                            current_content.append(
                                "<i style='float: right' class='fa fa-check-square-o'></i>");
                        }
                    }
                });
            }

        });

        $(document).ready(function() {
            $(".card-body .read-more a").click(function(e) {
                e.preventDefault();
                $(this).toggleClass("active");
                $(this).parent().siblings('.description').toggleClass('full-text');
            });
        });
    </script>