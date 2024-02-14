<link rel="stylesheet" href="<?php echo base_url('assets/inilabs/fb-img-grid/images-grid.css'); ?>" />
<div class="social-feed template-rightsidebar-page ">
    <div class="social-feed-container">
        <div class="container container--sm" id="feeds-data">

        </div>
    </div>

    <aside class="social-feed-sidebar right-sidebar " style="display:flex;">
        <section class="aside-section  card">
            <div class="card-body aside-section-note">
                <header class="aside-header note-header">
                    <h5 class="aside-title">Your Notes</h5>
                    <button class="btn btn-xs btn-success js-note-trigger">Add Note</button>
                </header>
                <div class="aside-body">
                    <span class="note__error"></span>
                    <div class="note__add" style="display: none;">
                        <div class="note-section note-section--show" id="note-add">
                            <form class="note-section-form" role="form" method="post" action="<?php echo site_url('note/feed_add') ?>" id="add_note">
                                <div class="md-form">
                                    <textarea type="text" class="md-textarea form-control" id="note" name="note"></textarea>
                                    <label for="note">Title</label>
                                </div>
                                <div class="note-section-form-footer">
                                    <input type="submit" class="btn btn-success js-note-save" value="Submit">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="note__section">
                        <?php foreach ($notes as $n) {
                            $note = $n->note;
                            $noteID = $n->noteID;
                        ?>
                            <div class="aside-item">
                                <form class="note-section note-section-form" id="updateNote" method="post">
                                    <div class="md-form">
                                        <textarea type="text" name="note" id="" class="md-textarea form-control"> <?php echo $note ?></textarea>
                                        <label for="note">Title</label>
                                        <input type="hidden" name="nid" value="<?php echo $noteID ?>">
                                    </div>
                                    <div class="note-section-form-footer">
                                        <input type="submit" value="Done" class="btn btn-xs btn-success js-note-show" id="updateNote">
                                    </div>
                                </form>
                                <div class="note-section-view ">
                                    <div class="js-note-edit">
                                        <?php echo $note ?>
                                    </div>
                                    <a href="#" class="icon-round  " onclick="thisNoteDelete(<?php echo $noteID ?>);"> <i class="fa fa-trash"></i> </a>

                                </div>
                            </div>
                        <?php
                        } ?>
                    </div>
                </div>
            </div>
            <?php if ($assignments) : ?>

                <div class="card-body aside-section-assignment">
                    <header class="aside-header  ">
                        <h5 class="aside-title">Assignments</h5>
                        <?php
                        $usertype = $this->session->userdata('usertypeID');
                        if ($usertype == 2 or $usertype == 1) :
                        ?>
                            <a class="btn btn-xs btn-success" href="<?php echo base_url('courses') ?>">Add Assignment</a>

                        <?php endif; ?>
                        <?php
                        ?>
                    </header>
                    <div class="aside-body">
                        <div id="assignment__section">
                            <?php foreach ($assignments as $as) {
                                $title = $as->title;
                                $subject = $as->subject;
                                $class_name = $as->class_name;
                                $section = $as->section_name;
                                $deadlinedate = $as->deadlinedate;
                                $assignment_status = $as->assignment_status;
                                $assignment_link = $as->link;
                                $count_assignment_submit = $as->count_assignment_submit;
                                $assignment_view_link = $as->view_link;
                                $status = $as->status_label_title;
                            ?>
                                <div class="aside-item">
                                    <div class="assignment-list">
                                        <div class="media-block">
                                            <figure class="avatar__figure">
                                                <span class="avatar__image">
                                                    <img src="<?= imagelink($as-> user_image,56) ?>" alt="">
                                                </span>
                                            </figure>
                                            <div class="media-block-body">
                                                <div class="class-block">
                                                    <div class="">
                                                        <?php echo $subject ?>
                                                        <!-- <span class="pill pill--flat pill--sm"> -->
                                                        <?php echo ($usertype == 4 or $usertype == 2 or $usertype == 1) ? ' |' . $class_name : '' ?>
                                                        <?php echo ($usertype == 4 or $usertype == 2 or $usertype == 1) ? ' |' . $section : '' ?>
                                                        <!-- </span> -->
                                                    </div>
                                                    <?php
                                                    if ($usertype == 2 or $usertype == 1) : ?>
                                                        <a href="<?= base_url('assignment/view/' . $as->assignmentID . '/' . $as->classesID . '?course=' . $as->course_id) ?>" role="button"><?php echo $count_assignment_submit ?>/<?php echo $totalStudentAssignment[$as->classesID] ?></a>
                                                    <?php endif; ?>
                                                </div>
                                                <p>
                                                    <?php echo $title; ?>

                                                </p>
                                                <div class="<?php echo $assignment_status ?> h5 mt-2"><b> (Deadline: <?php echo $deadlinedate ?>)</b></div>
                                            </div>
                                        </div>

                                        <?php if ($usertype == 3 || $usertype == 4) : ?>
                                            <small><span class="label <?= $as->status_label; ?>"><?= $status ?></span></small>
                                        <?php endif; ?>

                                        <?php if ($usertype == 3) : ?>
                                            <?php if ($assignment_link) { ?>
                                                <a href="<?php echo $assignment_link ?>" class="stretched-link mt-2 btn btn-xs btn-primary" target="_blank" title="<?php echo $as->link_title ?>">Submit</a>
                                            <?php } ?>
                                        <?php endif; ?>

                                        <?php if ($assignment_view_link) { ?>
                                            <a href="javascript:void(0)" class="viewAtt stretched-link mt-2 btn btn-xs btn-info" data-id="<?= $as->assignmentID ?>" data-course="<?= $as->course_id ?>" title="<?php echo $as->link_title ?>">View</a>
                                            <?php if ($usertype == 4) : ?>
                                                <a href="<?php echo $assignment_view_link ?>" class="stretched-link mt-2 btn btn-xs btn-success" target="_blank" title="<?php echo $as->link_title ?>">View Sub</a>
                                            <?php endif; ?>
                                        <?php } ?>

                                    </div>
                                </div>
                            <?php
                            } ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <?php if ($homeworks) : ?>

                <div class="card-body aside-section-assignment">
                    <header class="aside-header  ">
                        <h5 class="aside-title">Homework</h5>
                        <?php
                        $usertype = $this->session->userdata('usertypeID');
                        if ($usertype == 2 or $usertype == 1) :
                        ?>
                            <a class="btn btn-xs btn-success" href="<?php echo base_url('courses') ?>">Add Homework</a>
                        <?php endif; ?>
                        <?php
                        ?>
                    </header>
                    <div class="aside-body">
                        <div id="homework__section">
                            <?php foreach ($homeworks as $hw) {
                                $title = $hw->title;
                                $subject = $hw->subject;
                                $class_name = $hw->class_name;
                                $section = $hw->section_name;
                                $deadlinedate = $hw->deadlinedate;
                                $hw_status = $hw->hw_status;
                                $hw_link = $hw->link;
                                $count_hw_submit = $hw->count_hw_submit;
                                $hw_view_link = $hw->view_link;
                                $ha_status = $hw->status_label_title;
                            ?>
                                <div class="aside-item">
                                    <div class="assignment-list">
                                        <div class="media-block">
                                            <figure class="avatar__figure">
                                                <span class="avatar__image">
                                                    <img src="<?= imagelink($hw->user_image,56) ?>" alt="">
                                                </span>
                                            </figure>
                                            <div class="media-block-body">
                                                <div class="class-block">
                                                    <div class="">
                                                        <?php echo $subject ?>
                                                        <!-- <span class="pill pill--flat pill--sm">A</span> -->
                                                    </div>
                                                    <?php
                                                    if ($usertype == 2 or $usertype == 1) : ?>
                                                        <a href="<?= base_url('homework/view/' . $hw->homeworkID . '/' . $hw->classesID . '?course=' . $hw->course_id) ?>" role="button"><?php echo $count_hw_submit ?>/<?php echo $totalStudentHomework[$hw->classesID] ?></a>
                                                    <?php endif; ?>
                                                </div>
                                                <p>
                                                    <?php echo $title; ?>

                                                </p>
                                                <div class="<?php echo $hw_status ?> h5 mt-2"><b> (Deadline: <?php echo $deadlinedate ?>)</b></div>
                                            </div>
                                        </div>
                                        <?php if ($usertype == 3 || $usertype == 4) : ?>
                                            <small><span class="label <?= $hw->status_label; ?>"><?= $ha_status ?></span></small>
                                        <?php endif; ?>
                                        <?php if ($usertype == 3) : ?>
                                            <?php if ($hw_link) { ?>
                                                <a href="<?php echo $hw_link ?>" class="stretched-link mt-2 btn btn-xs btn-primary" target="_blank" title="<?php echo $hw->link_title ?>">Submit</a>
                                            <?php } ?>
                                        <?php endif; ?>
                                        <?php if ($hw_view_link) { ?>
                                            <a href="javascript:void(0)" class="viewHomework stretched-link mt-2 btn btn-xs btn-info" data-id="<?= $hw->homeworkID ?>" data-course="<?= $hw->course_id ?>" title="<?php echo $hw->link_title ?>">View</a>
                                            <?php if ($usertype == 4) : ?>
                                                <a href="<?php echo $hw_view_link ?>" class="stretched-link mt-2 btn btn-xs btn-success" target="_blank" title="<?php echo $hw->link_title ?>">View Sub</a>
                                            <?php endif; ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php
                            } ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <?php if ($classworks) : ?>

                <div class="card-body aside-section-assignment">
                    <header class="aside-header  ">
                        <h5 class="aside-title">Classwork</h5>
                        <?php
                        $usertype = $this->session->userdata('usertypeID');
                        if ($usertype == 2 or $usertype == 1) :
                        ?>
                            <a class="btn btn-xs btn-success" href="<?php echo base_url('courses') ?>">Add Classwork</a>
                        <?php endif; ?>
                        <?php
                        ?>
                    </header>
                    <div class="aside-body">
                        <div id="classwork__section">
                            <?php foreach ($classworks as $cw) {
                                $title = $cw->title;
                                $subject = $cw->subject;
                                $class_name = $cw->class_name;
                                $section = $cw->section_name;
                                $deadlinedate = $cw->deadlinedate;
                                $cw_status = $cw->cw_status;
                                $cw_link = $cw->link;
                                $count_cw_submit = $cw->count_cw_submit;
                                $cw_view_link = $cw->view_link;
                                $ha_status = $cw->status_label_title;
                            ?>
                                <div class="aside-item">
                                    <div class="assignment-list">
                                        <div class="media-block">
                                            <figure class="avatar__figure">
                                                <span class="avatar__image">
                                                    <img src="<?= imagelink($cw->user_image,56) ?>" alt="">
                                                </span>
                                            </figure>
                                            <div class="media-block-body">
                                                <div class="class-block">
                                                    <div class="">
                                                        <?php echo $subject ?>
                                                        <!-- <span class="pill pill--flat pill--sm">A</span> -->
                                                    </div>
                                                    <?php
                                                    if ($usertype == 2 or $usertype == 1) : ?>
                                                        <a href="<?= base_url('classwork/view/' . $cw->classworkID . '/' . $cw->classesID . '?course=' . $cw->course_id) ?>" role="button"><?php echo $count_cw_submit ?>/<?php echo $totalStudentHomework[$cw->classesID] ?></a>
                                                    <?php endif; ?>
                                                </div>
                                                <p>
                                                    <?php echo $title; ?>

                                                </p>
                                                <div class="<?php echo $cw_status ?> h5 mt-2"><b> (Deadline: <?php echo $deadlinedate ?>)</b></div>
                                            </div>
                                        </div>
                                        <?php if ($usertype == 3 || $usertype == 4) : ?>
                                            <small><span class="label <?= $cw->status_label; ?>"><?= $ha_status ?></span></small>
                                        <?php endif; ?>
                                        <?php if ($usertype == 3) : ?>
                                            <?php if ($cw_link) { ?>
                                                <a href="<?php echo $cw_link ?>" class="stretched-link mt-2 btn btn-xs btn-primary" target="_blank" title="<?php echo $cw->link_title ?>">Submit</a>
                                            <?php } ?>
                                        <?php endif; ?>
                                        <?php if ($cw_view_link) { ?>
                                            <a href="javascript:void(0)" class="viewClasswork stretched-link mt-2 btn btn-xs btn-info" data-id="<?= $cw->classworkID ?>" data-course="<?= $cw->course_id ?>" title="<?php echo $cw->link_title ?>">View</a>
                                            <?php if ($usertype == 4) : ?>
                                                <a href="<?php echo $cw_view_link ?>" class="stretched-link mt-2 btn btn-xs btn-success" target="_blank" title="<?php echo $cw->link_title ?>">View Sub</a>
                                            <?php endif; ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php
                            } ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if ($dailys) : ?>

                <div class="card-body aside-section-dailys">
                    <header class="aside-header  ">
                        <h5 class="aside-title">Daily Plans</h5>
                        <?php
                        $usertype = $this->session->userdata('usertypeID');
                        if ($usertype == 2) :
                        ?>
                            <a class="btn btn-xs btn-success" href="<?php echo base_url('courses') ?>">Add Daily Plans</a>

                        <?php endif; ?>
                        <?php
                        ?>
                    </header>
                    <div class="aside-body">
                        <div id="assignment__section">
                            <?php foreach ($dailys as $daily) {
                                $title = $daily->title;
                                $unit = $daily->unit;
                                $chapter_name = $daily->unit;
                                $subject = $daily->subject_name;
                                
                            ?>
                                <div class="aside-item">
                                    <div class="assignment-list">
                                        <div class="media-block">

                                            <figure class="avatar__figure">
                                                <span class="avatar__image">
                                                    <img src="<?= imagelink($daily-> user_image,56) ?>" alt="">
                                                </span>
                                            </figure>
                                            
                                            <div class="media-block-body">
                                                <div class="class-block">
                                                    <div class="">
                                                        <p><?php echo $subject ?></p>
                                                        <p><?php echo $unit ?> -  <?php echo $chapter_name ?> </p>
                                                    </div> 
                                                    
                                                </div>
                                                <p>
                                                    <?php echo $title; ?>
                                                </p>
                                                <p>
                                                    <?php
                                                    if ($usertype == 2 or $usertype == 1 or $usertype == 3) : ?>
                                                         <a href="javascript:void(0)" class="viewDaily stretched-link mt-2 btn btn-xs btn-info" data-toggle="modal" onclick="" data-id="<?php echo $daily->id ?>" data-course="<?php echo $daily->course_id; ?>" >
                                                            View
                                                        </a>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>

        </section>

    </aside>
</div>


<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p>We'd like to send you notifications for the notices and events.</p>
                <button type="submit" class="btn btn-primary" id="push-subscription-button">Allow</button>
                <button class="btn btn-default" data-dismiss="modal" id="cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/inilabs/fb-img-grid/images-grid.js'); ?>"></script>


<script>
    $(document).ready(function() {
        loadFeed();
    });

    function loadFeed() {
        $.ajax({
            url: "<?= base_url('feed/loadFeed/') ?>",
            type: "get",
            success: function(response) {
                $('#feeds-data').append(response);
            },
            error: function(response) {
                loadFeed();
            }
        });
    }
</script>

<script>
    $(document).ready(function() {

        jsNoteTrigger();
        $('#push-subscription-button').submit(function() {
            $('#push-subscription-button').modal('hide');
            return false;
        });

        $("#cancel").click(function() {
            localStorage.setItem("cancel", 1);
        });
    });
</script>

<script type="application/javascript">
    $(document).on('submit', '#updateNote', function(e) {
        e.preventDefault();
        var note = $(this).find("textarea[name='note']").val();
        var noteid = $(this).find("input[name='nid']").val();
        var check = isEmpty(note);
        if (check == true) {

            $(".note__error").html('<p style="background:red;">please add note</p>');
            setTimeout(function() {
                $('.note__error p').remove();
            }, 2000);
            return false;
        }
        $.ajax({
            url: "<?php echo base_url('note/ajax_note_update') ?>",
            type: "POST",
            dataType: "JSON",
            data: {
                note: note,
                nid: noteid,
            },
            success: function(response) {
                var htmltxt = '';
                $.each(response, function(index, data) {
                    htmltxt = htmltxt + '<div class="aside-item"><form class="note-section note-section-form" id="updateNote" method="post"><div class="md-form" ><textarea type="text" name="note" id="" class="md-textarea form-control" >' + data.note + '</textarea><input type="hidden" name="nid" value="' + data.noteID + '" ><label for="note">Title</label></div><div class="note-section-form-footer"><input type="submit" value="Done" class="btn btn-xs btn-success js-note-show" id="updateNote"></div></form><div class="note-section-view "><div class="js-note-edit">' + data.note + '</div><a href="#" class="icon-round  " onclick="thisNoteDelete(' + data.noteID + ');"> <i class="fa fa-trash"></i> </a></div></div>';
                });
                $("#note__section").html(htmltxt);
                jsNoteTrigger();
            },
            error: function(xhr, status, error) {
                alert('eror');
            }

        });

    });

    $(document).on('submit', '#add_note', function(e) {
        e.preventDefault();
        var note = $(this).find("textarea[name='note']").val();
        var check = isEmpty(note);
        if (check == true) {
            $(".note__error").html('<p style="background:red;">please add note</p>');
            setTimeout(function() {
                $('.note__error p').remove();
            }, 2000);
            return false;
        }
        $.ajax({
            url: "<?php echo base_url('note/ajax_note_add') ?>",
            type: "POST",
            dataType: "JSON",
            data: {
                note: note,
            },
            success: function(response) {
                var htmltxt = '';
                $.each(response, function(index, data) {
                    htmltxt = htmltxt + '<div class="aside-item"><form class="note-section note-section-form" id="updateNote" method="post"><div class="md-form" ><textarea type="text" name="note" id="" class="md-textarea form-control" >' + data.note + '</textarea><label for="note">Title</label><input type="hidden" name="nid" value="' + data.noteID + '" ></div><div class="note-section-form-footer"><input type="submit" value="Done" class="btn btn-xs btn-success js-note-show" id="updateNote"></div></form><div class="note-section-view "><div class="js-note-edit">' + data.note + '</div><a href="#" class="icon-round  " onclick="thisNoteDelete(' + data.noteID + ');"> <i class="fa fa-trash"></i> </a></div></div>';
                    $('#add_note textarea[name=note').val('');
                });
                $("#note__section").html(htmltxt);
                jsNoteTrigger();
            },
            error: function(xhr, status, error) {
                alert('eror');
            }

        });

    });
</script>
<script>
    function thisNoteDelete(nid) {
        nid = nid;
        $.ajax({
            url: "<?php echo base_url('note/ajax_note_delete') ?>",
            type: "POST",
            dataType: "JSON",
            data: {
                noteID: nid,
            },
            success: function(response) {
                var htmltxt = '';
                $.each(response, function(index, data) {
                    htmltxt = htmltxt + '<div class="aside-item"><form class="note-section note-section-form" id="updateNote" method="post"><div class="md-form" ><textarea type="text" name="note" id="" class="md-textarea form-control" >' + data.note + '</textarea><label for="note">Title</label><input type="hidden" name="nid" value="' + data.noteID + '" ></div><div class="note-section-form-footer"><input type="submit" value="Done" class="btn btn-xs btn-success js-note-show" id="updateNote"></div></form><div class="note-section-view "><div class="js-note-edit">' + data.note + '</div><a href="#" class="icon-round  " onclick="thisNoteDelete(' + data.noteID + ');"> <i class="fa fa-trash"></i> </a></div></div>';
                    $('#add_note textarea[name=note').val('');
                });
                $("#note__section").empty();
                $("#note__section").append(htmltxt);
                jsNoteTrigger();
            },
            error: function(xhr, status, error) {
                alert('eror');
            }

        });
    }

    function jsNoteTrigger() {
        $(".js-note-trigger").on("click", function(e) {
            $(this).parent().hide();
            $(".note__add").show();
        });

        $(document).on("click", ".js-note-save", function(e) {
            // alert('hello');
            $(".note__add").hide();
            $(".note-header").css("display", "flex");
        });
    }
    $("body").delegate(".js-note-edit", "click", function(e) {
        $(this).parents(".note-section-view").hide();
        $(this).parents(".note-section-view").prev('.note-section-form').show();
    });
    $("body").delegate(".js-note-show", "click", function(e) {
        $(this).parents(".note-section-form").hide();
        $(this).parents(".note-section-form").next('.note-section-view').show();
    });

    function isEmpty(str) {
        if (!str || str.length === 0)
            return true;
        else {
            return false;
        }
    }

    $(".viewDaily").on("click", function (params) {
    var id = $(this).data("id");
    var course = $(this).data("course");
    $("#view_ajax_modal_content").empty();
   
    $.ajax({
      type: "POST",
      url: BASE_URL + "daily_plan/getDailyByAjax",
      dataType: "html",
      data: { id: id, course: course },
      success: function (data) {
        console.log(data);
        $("#viewmodal").modal("show");
        $("#view_ajax_modal_content").append(data);
      },
    });
  });

</script>