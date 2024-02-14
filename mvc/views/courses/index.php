<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


<style>

    ul.list-inline li {
        margin-bottom: 5px;
    }

</style>


<?php if ($usertypeID != 4) { ?>
    <div class="container container--sm">
        <header class="pg-header">
            <?php if ($usertypeID == 2) { ?>
                <header class="pg-header">
                    <h1 class="pg-title">My teaching courses</h1>
                </header>
            <?php } else { ?>
                <header class="pg-header">
                    <h1 class="pg-title">Courses</h1>
                </header>
            <?php }?>
            <?php if (permissionChecker('courses_add')) { ?>
                <a
                        href="javascript:;"
                        data-toggle="modal"
                        
                        data-target="#addCourse"
                        onclick="showHideModalField(true)"
                        class="btn btn-success"
                >
                    <i class="fa fa-plus"></i>
                    <span>Add Course</span>
                </a>
            <?php } ?>
        </header>
        <?php if (customCompute($classes)) { ?>
            <?php foreach ($classes as $class) {
                if(isset($class->courses) && !empty($class->courses)) {
                if (customCompute($class->courses)) {
                    $courseCount = count($class->courses);
                    ?>
                    <section class="section-course">
                        <header class="section-header">
                            <?php if ($usertypeID != 3) { ?>
                                <h3 class="section-title">Class <?= $class->classes ?></h3>
                            <?php } ?>
                            <?php if (isset($class->sections) && customCompute($class->sections)) { ?>
                                <ul class="list-inline">
                                    <?php foreach ($class->sections as $section) { ?>
                                        <li><span class="pill"><?= $section->section ?></span></li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                            
                        </header>
                        <?php if ($courseCount >= 6) { ?>
                            <header class="section-header">
                                <a href="#" class="btn btn-default js-morelink">Show More</a>
                            </header>
                        <?php } ?>

                        <?php if (customCompute($class->courses)) { ?>
                        <div class="cards cards--coureses">
                            <?php foreach ($class->courses as $index => $course) { if($course->subject!= NULL){?>
                                <div class="card">
                                    <?php
                                    $image = $course->photo == '' ? 'assets/images/no-image-preview.png' :  $course->photo;
                                    ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <img src="<?= imagelink1($image,512) ?>" class="embed-responsive-item" alt="<?= $course->subject; ?>" />

                                    </div>
                                    <div class="card-body">
                                        <a href="<?= ($usertypeID != 3) ? base_url() . 'courses/show/' . $course->id : base_url() . 'courses/student_view/' . $course->id ?>">
                                            <h3 class="card-title"><?= $course->subject; ?></h3>
                                        </a>
                                        <ul class="list-inline list-inline--course-lists">
                                            <li data-toggle="tooltip" data-placement="top" title="Units">
                                                <span class="counter"><?= $course->units ?></span>
                                                <i class="fa fa-book"></i>
                                            </li>
                                            <li data-toggle="tooltip" data-placement="top" title="Chapters">
                                                <span class="counter"><?= $course->chapters ?></span>
                                                <i class="fa fa-th-list"></i>
                                            </li>
                                        </ul>
                                        <div class="card-footer">
                                            <?php if ($usertypeID == 3) { ?>
                                                <span class="virtual-class" id='<?php echo $course->id ?>'>
                                                    <a href='#'
                                                       class='btn btn-success btn-xs mrg join-virtual-class join-virtual-class-<?php echo $course->id ?>'>Join Classroom</a>
                                                </span>
                                            <?php } ?>
                                            <?php if (permissionChecker('courses_edit')) { ?>
                                                <div class="onoffswitch-small">
                                                    <input type="checkbox" name="course"
                                                           class="onoffswitch-small-checkbox"
                                                           id="course<?= $course->id ?>" <?php if ($course->published == '1') { ?> checked='checked' <?php }
                                                    if ($course->published == '1') echo "value='2'"; else echo "value='1'"; ?>>
                                                    <label class="onoffswitch-small-label switch"
                                                           for="course<?= $course->id ?>"
                                                           courseid="<?php echo $course->id; ?>">
                                                        <span class="onoffswitch-small-inner"></span>
                                                        <span class="onoffswitch-small-switch"></span>
                                                    </label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if (permissionChecker('courses_add')) { ?>
                                    <?php if ($index == 1 || $courseCount == 1) { ?>
                                        <div class="card card-course-add" onclick="showHideModalField(false, '<?=$class->classesID?>');">
                                            <div class="card-body">
                                                <a
                                                        href="javascript:;"
                                                        class="card-anchor-absolute"
                                                        data-toggle="modal"
                                                        data-target="#addCourse"
                                                >
                                                    <i class="fa fa-plus"></i>
                                                    <span>Add Course</span>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php }
                            }
                            }
                            } ?>
                        </div>

                    </section>
                <?php }
                }
                ?>
                <?php
            }
        } ?>
    </div>
<?php } else {
    foreach ($students as $student) { ?>
        <div class="container container--sm">
            <section class="section-course">
                <header class="section-header">
                    <h3 class="section-title"><?= $student->name ?> - Class <?=$student->classes_numeric?></h3>
                    <?php $courseCount = count($student->courses);
                        if ($courseCount >= 6) { ?>
                        <a href="#" class="btn btn-default js-morelink">Show More</a>
                    <?php } ?>
                </header>
                <div class="cards cards--coureses">
                        <?php if (customCompute($student->courses)) {
                            foreach ($student->courses as $course) { ?>

                                <div class="card">
                                    <?php
                                    $image = $course->photo == '' ? 'assets/images/no-image-preview.png' : 'uploads/images/' . $course->photo;
                                    ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <img
                                                src="<?= base_url($image) ?>"
                                                class="embed-responsive-item"
                                                alt="<?= $course->subject; ?>"
                                        />
                                    </div>
                                    <div class="card-body">
                                        <a href="<?= base_url() . 'courses/student_view/' . $course->id . '/' . $student->studentID ?>">
                                            <h3 class="card-title"><?= $course->subject; ?></h3>
                                        </a>
                                        <ul class="list-inline list-inline--course-lists">
                                            <li data-toggle="tooltip" data-placement="top" title="Units">
                                                <span class="counter"><?= $course->units ?></span>
                                                <i class="fa fa-book"></i>
                                            </li>
                                            <li data-toggle="tooltip" data-placement="top" title="Chapters">
                                                <span class="counter"><?= $course->chapters ?></span>
                                                <i class="fa fa-th-list"></i>
                                            </li>
                                        </ul>
                                        <div class="card-footer">
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                </div>
            </section>
        </div>
    <?php }
} ?>    

<?php if (permissionChecker('courses_add')) { ?>
    <!-- add course modal starts -->
    <div class="modal fade" tabindex="-1" role="dialog" id="addCourse">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Add new course</h3>
                </div>
                <div class="modal-body">
                    <form method="post">
                        
                        <div class="form-group modal-show-hide-field">
                            <div class="md-form md-form--select">
                                <?php
                                $array = array();
                                $array[0] = $this->lang->line("select_class");
                                foreach ($classes as $classa) {
                                    $array[$classa->classesID] = $classa->classes;
                                }
                                echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='mdb-select'");
                                ?>
                                <label for="" class="modal-show-hide-field mdb-main-label">Select Class</label>
                                <span class="text-danger error">
                                <p id="class-error" ></p>
                                </span>
                            </div>
                        </div>
                        
                        <div class="form-group" >
                            <div class="md-form md-form--select">
                                <?php
                                $array = array();
                                $array[0] = $this->lang->line("select_subject");
                                foreach ($subjects as $subject) {
                                    $array[$subject->subjectID] = $subject->subject;
                                }
                                echo form_dropdown("subject_id", $array, set_value("subject_id"), "id='ajax-get-subjects' class='mdb-select'");
                                ?>
                                 <label for=""  class=" mdb-main-label">Select Subject</label>
                                 <span class="text-danger error">
                                    <p id="subject-error" ></p>
                                    </span>
                            </div>
                        </div>
                        <?php if(permissionChecker('subject_add')) { ?>
                            <div class="form-group" id="add-subject-toggle">
                                <a href="" id="redirect-anchor" class="btn btn-success">Add subject</a>
                            </div>
                        <?php } ?>

                        <div class="form-group" >
                            <?php 
                                if(form_error('description')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                                ?>
                                        
                                <div class="md-form">
                                    <label for="description"> Description <br> </label>
                                        <textarea type="text" placeholder="Enter course description here..." class="form-control md-textarea" id ="desc" name="description" ></textarea>
                                                    
                                        <span class="text-danger error">
                                        <?php echo form_error('description'); ?> 
                                        </span>
                                </div>
                                            
                            </div>
                         </div>     
                         
                         <div class="form-group" >
                            <?php 
                                if(form_error('duration')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                                ?>
                                        
                                <div class="md-form">
                                    <label for="duration"> Duration </label>
                                        <input type="text" placeholder="Enter course duration here..." class="form-control" id ="duration" name="duration" >
                                                    
                                        <span class="text-danger error">
                                        <?php echo form_error('duration'); ?> 
                                        </span>
                                </div>
                                            
                            </div>
                         </div>  

                         <div class="form-group" >
                            <?php 
                                if(form_error('hour_per_week')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                                ?>
                                        
                                <div class="md-form">
                                    <label for="hour_per_week">Study Hour per Week </label>
                                        <input type="text" placeholder="Enter study hour per week..." class="form-control" id ="hour_per_week" name="hour_per_week" >
                                                    
                                        <span class="text-danger error">
                                        <?php echo form_error('hour_per_week'); ?> 
                                        </span>
                                </div>
                                            
                            </div>
                         </div>  
                       
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="add-course" class="btn btn-primary">Add Course Now</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- add course modal ends -->
<?php } ?>


<script type="text/javascript">
    $('.select2').select2();

    $('#add-subject-toggle').hide();

    $(document).ready(function () {
        $(".join-virtual-class").hide();

        $(".virtual-class").each(function () {
            // Test if the div element is empty
            id = $(this).attr("id");
            checkIfMeetingRunning(id);
        });
    });

    setInterval(function () {
        $(".virtual-class").each(function () {
            // Test if the div element is empty
            id = $(this).attr("id");
            checkIfMeetingRunning(id);
        });
    }, 20000);

    function doAttendance(id) {
        let url = "<?=base_url('courses/checkMeeting/')?>";
        $.post(url + id).done(function () {

        });
    }

    $('.join-virtual-class').click(function (e) {
        var start = $(this).attr("class").match(/join-virtual-class-[\w-]*\b/)
        var id = start[0].replace('join-virtual-class-', '')
        $.ajax({
            url: "<?=base_url('courses/join')?>", type: 'post', data: {id: id}, success: function (result) {
                if (result) {
                    console.log(result);
                    window.open(result, '_blank');
                    checkIfMeetingRunning(id);
                    setTimeout(function () {
                        doAttendance(id);
                    }, 5000);
                }
            }
        });
    });


    function checkIfMeetingRunning(id) {
        $.ajax({
            url: "<?=base_url('courses/ajaxCheckIfMeetingRunning')?>",
            type: 'post',
            data: {id: id},
            success: function (result) {
                if (result == 1) {
                    $(".end-virtual-class-" + id).show();
                    $(".join-virtual-class-" + id).show();
                    $(".start-virtual-class-" + id).hide();
                } else {
                    $(".end-virtual-class-" + id).hide();
                    $(".join-virtual-class-" + id).hide();
                    $(".start-virtual-class-" + id).show();
                }
            }
        });
    }

</script>

<?php if (permissionChecker('courses_add')) { ?>
    <script>

        $(document).on('change', '#classesID', function () {
            let class_id = $(this).val();
            showSubjects(class_id);
        });

        function showSubjects (classId) {
            let url = '<?=base_url("subject/ajaxGetSubjectsFromClassIdTeacherId") ?>'

            $.ajax({
                url: url + "?class_id=" + classId,
            }).done(function (data) {
                data = JSON.parse(data);
                if (data.has_subject) {
                    $('#add-subject-toggle').hide();
                } else {
                    $('#add-subject-toggle').show();
                    let redirectUrl = '<?=site_url("subject/add?redirect=courses")?>' + '&class=' + classId;
                    $('#redirect-anchor').attr('href', redirectUrl);
                }
                $('#ajax-get-subjects').html(data.form);
                $('.mdb-select').material_select('destroy'); 
                $('.mdb-select').material_select();
            });
        }

        function showHideModalField (showFiled, classId) {
            if(showFiled) {
                $('.modal-show-hide-field').show();
            } else {
                $('.modal-show-hide-field').hide();
                $('#classesID').val(classId);
                showSubjects(classId);
            }
        }
        $('.switch').click(function (e) {
            courseid = $(this).attr("courseid")
            $.ajax({
                type: 'POST',
                url: "<?=base_url('courses/ajaxChangeCourseStatus/')?>" + courseid,
                dataType: "html",
                success: function (data) {
                    showSuccessToast();
                }
            });
        })


        $('#add-course').click(function (e) {
            class_id = $('#classesID').val();
            subject_id = $('#ajax-get-subjects').val();
            description = $('#desc').val();
            duration = $('#duration').val();
            hour_per_week = $('#hour_per_week').val();
            $('#class-error').text('');
            $('#subject-error').text('');
            if (class_id == 0) {
                $('#class-error').text('Class is empty.');
            }
            if (subject_id == 0) {
                $('#subject-error').text('Subject is empty.');
            }
            if (class_id != 0 && subject_id != 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('courses/ajaxAdd')?>",
                    data: {"class_id": class_id, "subject_id": subject_id, "description": description, "duration": duration, "hour_per_week":hour_per_week},
                    dataType: "html",
                    success: function (data) {
                        if (data == true) {
                            showToast('success');
                            location.reload(true);
                        } else {
                            $('#subject-error').text('Course already exists.');
                        }
                    }
                });
            }
        })
    </script>
<?php } ?>
