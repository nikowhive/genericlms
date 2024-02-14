<style>
    .quiz-display-section {
        width: 80%;
    }
    #seeMore {
          cursor: pointer;
    }
</style>
<div class="right-side--fullHeight  ">
    <div class="row w-100 ">
        <?php $this->load->view("components/course_menu"); ?>
        <div class="course-content">
            <div class="container container--md">
                <header class="pg-header">
                    <h1 class="pg-title">Add quiz question</h1>
                </header>

                <div class="row row--quiz">
                    <div class="col-lg-4 order-lg-2 mb-3 mb-lg-0">
                        <div class="card card--quiz card--quiz-Sidebar js-affix-top affix-top">
                            <div class="card-body">
                                <a class="quiz-sidebar-title " role="button" data-toggle="collapse" href="#quizFilters" aria-expanded="false">
                                    <span>Quiz Settings</span>
                                    <i class="fa fa-caret-down"></i>
                                </a>
                                <h3 class="card-title">Quiz Settings</h3>
                                <div id="quizFilters" class="collapse">
                                    <div class="md-form--select md-form">
                                        <?php
                                        $array = array(0 => $this->lang->line("question_bank_select"));
                                        foreach ($classes as $group) {
                                            $array[$group->classesID] = $group->classes;
                                        }
                                        if ($this->uri->segment(3)) {
                                            echo form_dropdown("class_id", $array, $set_class, "id='class_id' class='mdb-select' disabled");
                                        } else {
                                            echo form_dropdown("class_id", $array, set_value("class_id"), "id='class_id' class='mdb-select'");
                                        } ?>

                                        <label class="mdb-main-label">Class</label>
                                    </div>

                                    <div class="md-form--select md-form">
                                        <?php
                                        $array = array(0 => $this->lang->line("question_bank_select"));
                                        foreach ($subjects as $group) {
                                            $array[$group->subjectID] = $group->subject;
                                        }
                                        if ($this->uri->segment(3)) {
                                            echo form_dropdown("subject_id", $array, set_value("subject_id", $set_subject), "id='subject_id' class='mdb-select' disabled");
                                        } else {
                                            echo form_dropdown("subject_id", $array, set_value("subject_id"), "id='subject_id' class='mdb-select'");
                                        }
                                        ?>
                                        <label class="mdb-main-label">Subject</label>
                                    </div>
                                    <div class="md-form--select md-form">
                                        <?php
                                        $array = array(0 => $this->lang->line("question_bank_select"));
                                        foreach ($units as $unit) {
                                            $array[$unit->id] = $unit->unit_name;
                                        }
                                        if ($this->uri->segment(3)) {
                                            echo form_dropdown("unit_id", $array, set_value("unit", $set_unit), "id='unit_id' class='mdb-select' disabled");
                                        } else {
                                            echo form_dropdown("unit_id", $array, set_value("unit"), "id='unit_id' class='mdb-select'");
                                        }
                                        ?>
                                        <label class="mdb-main-label">Unit</label>
                                    </div>

                                    <div class="md-form--select md-form">
                                        <?php
                                        $array = array(0 => $this->lang->line("question_bank_select"));
                                        foreach ($chapters as $chapter) {
                                            $array[$chapter->id] = $chapter->chapter_name;
                                        }
                                        if ($this->uri->segment(3)) {
                                            echo form_dropdown("chapter_id", $array, set_value("chapter_id", $set_chapter), "id='chapter_id' class='mdb-select' disabled");
                                        } else {
                                            echo form_dropdown("chapter_id", $array, set_value("chapter_id"), "id='chapter_id' class='mdb-select'");
                                        }
                                        ?>
                                        <label class="mdb-main-label">Chapter</label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 order-lg-1 ">
                        <div class="card card--quiz">
                            <div class="card-list card-list--title">
                                <input type="hidden" name="quizID" value="<?= $quiz->id ?>">
                                <span class="icon-round icon-round--primary">
                                    <i class="fa fa-puzzle-piece"></i>
                                </span>
                                <div class="md-form mt-2 mb-0" style="margin-right:20px;">
                                    <textarea id="quiz__title" placeholder="Your Quiz here..." class="md-textarea form-control" rows="1" name="quiz_title"><?= $quiz->quiz_name ?></textarea>
                                    <label for="quiz__title">Quiz Title</label>
                                    <span class="text-danger error" id="quiz-title-error"></span>
                                </div>
                                <div class="md-form mt-2 mb-0">
                                    <input type="text" class="form-control" id="quiz__percentage" name="quiz_percentage" value="<?= $quiz->percentage_coverage ?>">
                                    <label for="quiz__percentage">Percentage</label>
                                    <span class="text-danger error" id="quiz-percentage-error"></span>
                                </div>

                            </div>
                            <div class="card-list card-list--item">
                                <a class="icon-round collapsed qbutton" role="button" data-toggle="collapse" href="#quiz_question_list_doc_load" aria-expanded="false">
                                    <i class="fa fa-caret-down"></i>
                                </a>
                                <h4 class="text-primary mt-2">
                                    <div class="question_count_doc_load"></div>
                                </h4>
                            </div>
                            <div id="quiz_question_list_doc_load" class="collapse">

                            </div>
                            <div class="card-body">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs nav-tabs--buttons" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#import" aria-controls="profile" role="tab" data-toggle="tab" id="import_tab">Import Questions from library</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#create" aria-controls="home" role="tab" data-toggle="tab" id="create_tab">Create New Question</a>
                                    </li>

                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="import">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="md-form--select md-form">
                                                    <?php
                                                    $array = array(0 => $this->lang->line("question_bank_select"));
                                                    foreach ($groups as $group) {
                                                        $array[$group->questionGroupID] = $group->title;
                                                    }
                                                    echo form_dropdown("group", $array, set_value("group"), "id='group' class='mdb-select'");
                                                    ?>
                                                    <label class="mdb-main-label">Question Group</label>
                                                    <span class="text-danger error" id="group-error">Question group is required.</span>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="md-form--select md-form">
                                                    <?php
                                                    $array = array(0 => $this->lang->line("question_bank_select"));
                                                    foreach ($levels as $level) {
                                                        $array[$level->questionLevelID] = $level->name;
                                                    }
                                                    echo form_dropdown("level", $array, set_value("level"), "id='level' class='mdb-select'");
                                                    ?>
                                                    <label class="mdb-main-label">Difficulty Level</label>
                                                    <span class="text-danger error" id="level-error">Difficulty level is required.</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="md-form--select md-form" id="div_type">
                                                    <?php
                                                    $array = array(0 => $this->lang->line("question_bank_select"));
                                                    foreach ($types as $type) {
                                                        $array[$type->questionTypeID] = $type->name;
                                                    }
                                                    echo form_dropdown("question_type_id", $array, set_value("questionTypeID"), "id='question_type_id' class='mdb-select'");
                                                    ?>
                                                    <label class="mdb-main-label">Question Type</label>
                                                </div>
                                            </div>

                                        </div>
                                        <form class="form-horizontal" role="form" method="post" id="update_quiz">
                                            <input type="hidden" name="quiz_id" value="<?= $quiz->id ?>">
                                            <input type="hidden" name="chapter_id" value="<?= $set_chapter ?>">
                                            <input type="hidden" name="course_id" value="<?= $course_id ?>">
                                            <div class="wrapper_list_question" style="display: none;">
                                                <h4 class="text-primary mt-2 mb-3 question_count"></h4>
                                                <input class="form-check-input" type="checkbox" name="checkedAll" id="checkedAll" />
                                                <label class="form-check-label" for="checkedAll" id="bulkImportBtn">
                                                    Bulk Import
                                                </label>
                                                <input type="hidden" name="quiz_title" value="<?php echo $quiz->quiz_name ?>" id="quiz_title">
                                                <input type="hidden" name="quiz_percentage" value="<?php echo $quiz->percentage_coverage ?>" id="quiz_percentage">
                                                <div id="load_question_list"></div>
                                                <div class="text-center mt-4" id="seeMore">
                                                    <a href="#"> See More ...</a>
                                                </div>
                                                <button type="submit" class="btn btn-success mt-3" id="importQuestionBtn">
                                                    Import Question
                                                </button>


                                            </div>
                                        </form>
                                        <button id="updateQuiz" class="btn btn-success mt-3">
                                            Update Quiz
                                        </button>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="create">
                                        <form class="form-horizontal" role="form" method="post" id="create_question" enctype="multipart/form-data">
                                            <!-- <div class="tab-card card"> -->
                                            <div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="md-form--select md-form">
                                                            <?php
                                                            $array = array(0 => $this->lang->line("question_bank_select"));
                                                            foreach ($groups as $group) {
                                                                $array[$group->questionGroupID] = $group->title;
                                                            }
                                                            echo form_dropdown("group", $array, set_value("group"), "id='group_add' class='mdb-select'");
                                                            ?>
                                                            <label class="mdb-main-label">Question Group</label>
                                                            <span class="text-danger error" id="group_add-error">Question group is required.</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="md-form--select md-form">
                                                            <?php
                                                            $array = array(0 => $this->lang->line("question_bank_select"));
                                                            foreach ($levels as $level) {
                                                                $array[$level->questionLevelID] = $level->name;
                                                            }
                                                            echo form_dropdown("level", $array, set_value("level"), "id='level_add' class='mdb-select'");
                                                            ?>
                                                            <label class="mdb-main-label">Difficulty Level</label>
                                                            <span class="text-danger error" id="level_add-error">Difficulty level is required.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="md-form">
                                                    <textarea id="question" name="question" class="md-textarea form-control" rows="4"></textarea>
                                                    <label for="question">Question Title</label>
                                                    <span class="text-danger error" id="question-error">Question title is required</span>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="md-form--select md-form">
                                                            <?php
                                                            $array = array(0 => $this->lang->line("question_bank_select"));
                                                            foreach ($types as $type) {
                                                                $array[$type->questionTypeID] = $type->name;
                                                            }
                                                            echo form_dropdown("type_id", $array, set_value("questionTypeID"), "id='type_id' class='mdb-select'");
                                                            ?>
                                                            <label class="mdb-main-label">Question Type</label>
                                                            <input type="hidden" id="type" name="type" />
                                                            <span class="text-danger error" id="type-error">Question type is required</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="md-form--select md-form" id="totalOptionDiv">
                                                            <select name="totalOption" id="totalOption" class="mdb-select">
                                                                <option value="0" class="js-sub">Please Select</option>
                                                                <?php
                                                                foreach (range(1, 10) as $i) {
                                                                    echo '<option value="' . $i . '" class="js-nonsub-' . $i . '">' . $i . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                            <label class="mdb-main-label">Option Numbers</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="answer-block">
                                                    <div class="form-horizontal">

                                                        <div id="in"></div>
                                                        <span class="text-danger error" id="error-option">All options are required</span>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="md-form mb-md-3 mb-0">
                                                                <input type="number" class="form-control" id="mark" name="mark" value="" />
                                                                <label for="mark">Mark</label>
                                                                <span class="text-danger error" id="mark-error">Mark is required</span>
                                                                <span class="text-danger error" id="negative-mark-error">Mark should be greater than 0.</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="md-form md-form--file  mt-md-4 mt-0 mb-md-3 mb-0 ">
                                                                <div class="file-field nomargin">
                                                                    <div class="btn" data-toggle="tooltip" data-placement="top" title="Upload file">
                                                                        <span><i class="fa fa-paperclip"></i> </span>
                                                                        <input type="file" accept="image/png, image/jpeg, image/gif, application/msword,application/pdf" name="photos[]" multiple />
                                                                    </div>
                                                                    <div class="file-path-wrapper">
                                                                        <input class="file-path validate form-control" type="text" placeholder="Upload your file" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="md-form ">
                                                <textarea type="text" placeholder="Write instruction if any" id="form_explanation" class=" md-textarea form-control" rows="3"></textarea>
                                                <label for="form_explanation">Instruction (Optional)</label>
                                            </div>

                                            <div class="md-form ">
                                                <textarea type="text" placeholder="Write hints if any" id="form_hint" class=" md-textarea form-control" rows="3"></textarea>
                                                <label for="form_hint">Hints (Optional)</label>
                                            </div>

                                            <span class="btn btn-success mt-3" onclick="save_question()">
                                                Add Question
                                            </span>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="ajax-get-question-type-from-question-type-id" value="<?php echo base_url() ?>question_bank/ajaxGetQuestionTypeNumberFromQuestionTypeId">
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('change', '#unit_id', function() {
        let unit_id = $(this).val();
        let url = "<?php echo base_url() ?>chapter/ajaxGetChaptersFromUnitId";

        $.ajax({
            url: url + "?unit_id=" + unit_id,
        }).done(function(data) {
            $('#chapter_id').html(data);
            $('.mdb-select').material_select('destroy');
            $('.mdb-select').material_select();
        });
    })

    $('#quiz__title').on('keyup', function(e) {
        $("#quiz_title").val($(this).val());
    });
    $('#quiz__percentage').on('keyup', function(e) {
        $("#quiz_percentage").val($(this).val());
    });

    $(document).ready(function() {
        $(window).load(function() {
            $(function() {
                var field = {
                    'quiz_id': '<?= $quiz_id ?>',
                    'chapter_id': $('#chapter_id').val(),
                    'course_id': "<?= $course_id ?>",
                    'method_url': "<?php echo base_url('courses/quiz_question_list_doc_load') ?>",
                }
                makingPostDataPreviousofAjaxCall(field);
            });

            $(function() {
                var field = {
                    'quiz_id': '<?= $quiz_id ?>',
                    'chapter_id': $('#chapter_id').val(),
                    'course_id': "<?= $course_id ?>",
                }
                makingPostDataPreviousofAjaxCall(field);
            });

            $(document).on('change', '#group', function() {
                $('#load_question_list').html("");
                var field = {
                    'quiz_id': '<?= $quiz_id ?>',
                    'chapter_id': $('#chapter_id').val(),
                    'group_id': $('#group').val(),
                    'level_id': $('#level').val(),
                    'type_id': $('#question_type_id').val(),
                    'course_id': "<?= $course_id ?>",
                }
                makingPostDataPreviousofAjaxCall(field);
            });

            $(document).on('change', '#level', function() {
                $('#load_question_list').html("");
                var field = {
                    'quiz_id': '<?= $quiz_id ?>',
                    'chapter_id': $('#chapter_id').val(),
                    'group_id': $('#group').val(),
                    'level_id': $('#level').val(),
                    'type_id': $('#question_type_id').val(),
                    'course_id': "<?= $course_id ?>",
                }
                makingPostDataPreviousofAjaxCall(field);
            });

            $(document).on('change', '#question_type_id', function() {
                $('#load_question_list').html("");
                var field = {
                    'quiz_id': '<?= $quiz_id ?>',
                    'chapter_id': $('#chapter_id').val(),
                    'group_id': $('#group').val(),
                    'level_id': $('#level').val(),
                    'type_id': $('#question_type_id').val(),
                    'course_id': "<?= $course_id ?>",
                }
                makingPostDataPreviousofAjaxCall(field);
            });

            function makingPostDataPreviousofAjaxCall(field) {
                passData = field;
                ajaxCall(passData);
            }

            function ajaxCall(passData) {
                console.log(passData);
                var methodUrl = "<?= base_url('courses/quiz_question_list') ?>";
                if ('method_url' in passData) {
                    methodUrl = "<?= base_url('courses/quiz_question_list_doc_load') ?>";
                }
                $.ajax({
                    type: 'POST',
                    url: methodUrl,
                    data: passData,
                    dataType: "html",

                    success: function(data) {
                        console.log(data);
                        var response = JSON.parse(data);
                        renderLoder(response, passData);
                    }
                });
            }

            function renderLoder(response, passData) {
                var divId = 'load_question_list';
                console.log(response);
                if ('method_url' in passData) {
                    divId = 'quiz_question_list_doc_load';
                    if (response.count == 0) {
                        $('.card-list--item').hide();
                    }
                    $('.question_count_doc_load').html('<b>' + response.count + ' Questions Added</b>');
                } else {

                    if (response.row > 0) {
                        var row = response.row;
                    } else {
                        var row = 0;
                    }
                    $(".wrapper_list_question").show();

                    $('.question_count').html('<b>' + response.count + ' Questions found</b><input type="hidden" id="row" value=' + row + '><input type="hidden" id="all" value=' + response.count + '>');
                    if (response.count < 10) {
                        $('#seeMore').hide();
                    }
                    if (response.count == 0) {
                        $("#bulkImportBtn").hide();
                        $("#checkedAll").hide();
                        $('#importQuestionBtn').hide();
                    } else {
                        $("#bulkImportBtn").show();
                        $("#checkedAll").show();
                        $('#importQuestionBtn').show();
                    }
                }
                if (response.status) {
                    $('#' + divId).html(response.render);
                    for (var key in passData) {
                        if (passData.hasOwnProperty(key)) {
                            $('#' + key).parent().removeClass('has-error');
                        }
                    }
                } else {
                    for (var key in passData) {
                        if (passData.hasOwnProperty(key)) {
                            $('#' + key).parent().removeClass('has-error');
                        }
                    }

                    for (var key in response) {
                        if (response.hasOwnProperty(key)) {
                            $('#' + key).parent().addClass('has-error');
                        }
                    }
                }
            }

            $('#updateQuiz').click(function() {

                $('#quiz-percentage-error').html('');
                $('#quiz-title-error').html('');

                var quizTitle = $('#quiz__title').val();
                var quizPercentage = $('#quiz__percentage').val();

                if (quizTitle == '') {
                    $('#quiz-title-error').html('Quiz title is empty.');
                }

                if (quizPercentage == '') {
                    $('#quiz-percentage-error').html('Quiz percentage is empty.');
                }
                if (quizTitle != 0 && quizPercentage != 0) {
                    var field = {
                        'quiz_id': '<?= $quiz_id ?>',
                        'quiz_title': quizTitle,
                        'quiz_percentage': quizPercentage,
                    }
                    $.ajax({
                        type: 'POST',
                        url: "<?= base_url('courses/updateQuiz/' . $quiz_id) ?>",
                        data: field,
                        success: function(data) {
                            var response = jQuery.parseJSON(data);
                            if (!response.status) {
                                $('#quiz-percentage-error').html(response.quiz_percentage);
                                $('#quiz-title-error').html(response.quiz_title);
                            } else {
                                showToast('Success.');
                            }
                        }
                    });
                }

            });


            $('body').on('submit', '#update_quiz', function(e) {
                e.preventDefault();
                var field = {
                    'quiz_id': '<?= $quiz_id ?>',
                    'chapter_id': $('#chapter_id').val(),
                    'question_id': $(this).attr('qid'),
                    'row': Number($('#row').val()),
                    'method_url': "<?php echo base_url('courses/quiz_question_list_doc_load') ?>",
                }
                var field1 = {
                    'quiz_id': '<?= $quiz_id ?>',
                    'chapter_id': $('#chapter_id').val(),
                    'question_id': $(this).attr('qid'),
                    'row': Number($('#row').val()),
                }

                $("#add-question-" + $(this).attr('qid')).hide();
                $("#remove-question-" + $(this).attr('qid')).show();

                var frm = $(this);
                $.ajax({
                    url: '<?= base_url('courses/insert_quiz_question/' . $quiz_id) ?>',
                    method: 'post',
                    data: frm.serialize(),
                    dataType: 'html',
                    success: function(data) {
                        var response = jQuery.parseJSON(data);
                        console.log(response);
                        if (response.status) {
                            if (response.render) {
                                makingPostDataPreviousofAjaxCall(field);
                                makingPostDataPreviousofAjaxCall(field1);

                                $("#row").val(row);
                                toastr["success"](response.message)

                            }
                            if (response.error) {
                                $('#quiz-percentage-error').html(response.quiz_percentage);
                                $('#quiz-title-error').html(response.quiz_title);
                                // alert(response.error);
                            }
                        }

                    }
                });
            });





            $(document).on('click', '.add-question', function(e) {
                e.preventDefault();
                var field = {
                    'quiz_id': '<?= $quiz_id ?>',
                    'chapter_id': $('#chapter_id').val(),
                    'question_id': $(this).attr('qid'),
                    'method_url': "<?php echo base_url('courses/quiz_question_list_doc_load') ?>",
                }

                var field1 = {
                    'quiz_id': '<?= $quiz_id ?>',
                    'chapter_id': $('#chapter_id').val(),
                    'question_id': $(this).attr('qid'),
                    'row': Number($('#row').val()),
                }

                $("#add-question-" + $(this).attr('qid')).hide();
                $("#remove-question-" + $(this).attr('qid')).show();
                $(".checkbox-" + $(this).attr('qid')).prop('checked', true);
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('courses/add_single_question') ?>",
                    data: field,
                    dataType: "html",
                    success: function(data) {
                        console.log(data);
                        makingPostDataPreviousofAjaxCall(field);
                        makingPostDataPreviousofAjaxCall(field1);
                        showToast('Question Added.');
                    }
                });
            });

            $(document).on('click', '.remove-question', function() {
                var field = {
                    'quiz_id': '<?= $quiz_id ?>',
                    'chapter_id': $('#chapter_id').val(),
                    'question_id': $(this).attr('qid'),
                    'method_url': "<?php echo base_url('courses/quiz_question_list_doc_load') ?>",
                }
                var field1 = {
                    'quiz_id': '<?= $quiz_id ?>',
                    'chapter_id': $('#chapter_id').val(),
                    'question_id': $(this).attr('qid'),
                    'row': Number($('#row').val()),
                }

                console.log(field);
                $("#add-question-" + $(this).attr('qid')).show();
                $("#remove-question-" + $(this).attr('qid')).hide();
                $(".checkbox-" + $(this).attr('qid')).prop('checked', false);
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('courses/remove_single_question') ?>",
                    data: field,
                    dataType: "html",
                    success: function(data) {
                        console.log(data);
                        $("#question-list-" + data).remove();
                        makingPostDataPreviousofAjaxCall(field);
                        makingPostDataPreviousofAjaxCall(field1);
                        showToast('Question Removed.');
                        // location.reload();
                    }
                });
            });
        });

    });




    $("#checkedAll").change(function() {
        if (this.checked) {
            $(".checkSingle").each(function() {
                this.checked = true;
            })
        } else {
            $(".checkSingle").each(function() {
                this.checked = false;
            })
        }
    });


    $(function() {
        $('#totalOptionDiv').hide();
    });

    $(document).ready(function() {
        var totalOptionID = '<?= $totalOptionID ?>';
        if (totalOptionID > 0) {
            $('#totalOptionDiv').show();

            if (totalOptionID == 4) {
                $('.js-sub').show();
                $('.js-nonsub').hide();
            } else if (totalOptionID == 5) {
                $('#totalOptionDiv').hide();
            } else {
                $('.js-sub').hide();
                $('.js-nonsub').show();
            }
        }
    });

    $('#create_tab').click(function() {
        $('#div_type').hide();
    })

    $('#import_tab').click(function() {
        $('#div_type').show();
    })

    $('#type_id').change(function() {
        let question_type_id = $(this).val();
        let url = $('#ajax-get-question-type-from-question-type-id').val()
        $.ajax({
            url: url + "?question_type_id=" + question_type_id,
        }).done(function(data) {
            $('#type').val(data);
            $('#type').trigger('change');
            $('.mdb-select').material_select('destroy');
            $('.mdb-select').material_select();
        });
    })

    $('#type').change(function() {
        $('#in').children().remove();
        var type = $(this).val();
        var type_id = parseInt($('#type_id').val());

        if (type == 0 || type_id == 6) {
            $('#totalOptionDiv').hide();
        } else if (type_id == 4 || type_id == 2) {
            $('#totalOption').val(2);
            $('#totalOption').trigger('change');
            $('#totalOptionDiv').show();
            $('.mdb-select').material_select('destroy');
            $('.mdb-select').material_select();
        } else if (type_id == 1) {
            $('#totalOption').val(1);
            $('#totalOption').trigger('change');
            $('#totalOptionDiv').show();
            $('.mdb-select').material_select('destroy');
            $('.mdb-select').material_select();
        } else {
            $('#totalOption').val(0);
            $('#totalOption').trigger('change');
            $('#totalOptionDiv').show();
            $('.mdb-select').material_select('destroy');
            $('.mdb-select').material_select();
        }

        if (type == 4) {
            $('.js-sub').show();
            $('.js-nonsub').hide();
            $('.mdb-select').material_select('destroy');
            $('.mdb-select').material_select();
        } else {
            $('.js-sub').hide();
            $('.js-nonsub').show();
            $('.mdb-select').material_select('destroy');
            $('.mdb-select').material_select();
        }

    });

    $('#totalOption').change(function() {
        var valTotalOption = $(this).val();
        var type = $('#type').val();
        var type_id = parseInt($('#type_id').val());

        // if (type_id == 1 && valTotalOption > 1) {
        //     alert('Single answer must be 1 only');
        //     $('#type_id').trigger('change');
        //     return;
        // }

        if (type_id == 2 && valTotalOption < 2) {
            alert('Multiple answer must have option greater than 1');
            $('#type_id').trigger('change');
            return;
        }

        if (type_id == 4 && valTotalOption != 2) {
            alert('True False must have 2 options');
            $('#type_id').trigger('change');
            return;
        }

        if (parseInt(valTotalOption) != 0) {
            var opt = type_id == 4 ? ['', 'True', 'False'] : [];
            var ans = [];
            var count = $('.coption').size();

            for (j = 1; j <= count; j++) {
                if (type == 3) {
                    opt[j] = $('#answer' + j).val();
                } else {
                    opt[j] = $('#option' + j).val();
                    if ($('#ans' + j).prop('checked')) {
                        ans[j] = 'checked="checked"';
                    }
                }
            }

            $('#in').children().remove();
            $('#in').append('<h5 class="text-primary"><b>Answer Options</b></h5>');

            for (i = 1; i <= valTotalOption; i++) {
                if ($('#in').size())
                    $('#in').append(formHtmlData(i, type, opt[i], ans[i]));
                else
                    $('#in').append(formHtmlData(i, type));
            }

        } else {
            $('#in').children().remove();
        }

    });


    function formHtmlData(id, type, value = '', checked = '') {
        var required = 'required';
        var html = ''
        if (type == 1) {
            type = 'radio';
        } else if (type == 2) {
            type = 'checkbox';
            required = '';

        } else if (type == 3) {
            html += '<div class="row"><div class="col-md-4"><label for="answer' + id + '" class="col-sm-2 control-label"><?= $this->lang->line("question_bank_answer") ?> ' + id + '</label></div>';
            html += '<div class="col-md-8"><textarea  type="text" placeholder="Type here" class=" md-textarea form-control" rows="1" id="answer' + id + '" name="answer[]">' + value + '</textarea></div><span class="col-sm-4 control-label text-red" id="anserror' + id + '"><?php if (isset($form_validation['answer1'])) {
                                                                                                                                                                                                                                                                                    echo $form_validation['answer1'];
                                                                                                                                                                                                                                                                                } ?></span></div>';
            return html;
        }
        html += '<div class="row"><div class="col-md-6"><div class="answer-option-check"><div class="form-check"><input class="form-check-input" id="ans' + id + '" ' + checked + ' type="' + type + '" name="answer[]" value="' + id + '" data-toggle="tooltip" data-placement="top" title="Correct Answer" ' + required + '/><label class="form-check-label" for="ans' + id + '"> </label><div class="md-form mb-md-3 mb-0" style="display:inline-table"><input type="text" id="option' + id + '" name="option[]" value="' + value + '" class=" form-control"></input><label for="queestion"><?= $this->lang->line("question_bank_option") ?> ' + id + '</label></div></div></div></div>';
        html += '<div class="col-md-6"><div class="md-form md-form--file  mt-md-4 mt-0 mb-md-3 mb-0 "><div class="file-field nomargin"><div class="btn" data-toggle="tooltip" data-placement="top" title="Upload file"><span><i class="fa fa-paperclip"></i> </span><input type="file" name="image' + id + '" id="image' + id + '"/></div><div class="file-path-wrapper"><input class="file-path validate form-control" type="text" placeholder="Upload your file" /></div></div></div><span class="col-sm-3 control-label text-red" id="anserror' + id + '"><?php if (isset($form_validation['answer1'])) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    echo $form_validation['answer1'];
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                } ?></span></div>';
        return html;
    }


    $('#group-error').hide();
    $('#level-error').hide();
    $('#group_add-error').hide();
    $('#level_add-error').hide();
    $('#question-error').hide();
    $('#type-error').hide();
    $('#mark-error').hide();
    $('#explain-error').hide();
    $('#hint-error').hide();
    $('#error-option').hide();
    $('#negative-mark-error').hide();

    function save_question() {
        $('#group_add-error').hide();
        $('#level_add-error').hide();
        $('#question-error').hide();
        $('#type-error').hide();
        $('#mark-error').hide();
        $('#explain-error').hide();
        $('#hint-error').hide();
        $('#error-option').hide();
        $('#negative-mark-error').hide();

        var myForm = $("#create_question")[0];
        var formData = new FormData(myForm);

        formData.append('chapter_id', $('#chapter_id').val());

        if ($('#group_add').val() == 0) {
            $('#group_add-error').show();
        }

        if ($('#level_add').val() == 0) {
            $('#level_add-error').show();
        }

        if ($('#question').val() == '') {
            $('#question-error').show();
        }
        if ($('#mark').val() == '') {
            $('#mark-error').show();
        }
        if ($('#mark').val() != '' && $('#mark').val() < 0) {
            $('#negative-mark-error').show();
        }


        if ($('#type').val() == 0) {
            $('#type-error').show();
        } else if ($('#totalOption').val() == 0) {
            $('#type-error').show();
            $('#type-error').text('Please select options.');
        } else {

            var type_id = parseInt($('#type_id').val());
            empty_option = false;
            checked = false;


            if (type_id == 3) {
                checked = true;
                $('input[id^="answer"]').each(function(i, e) {
                    if (this.value == '') {
                        $('#error-option').show();
                        $('#error-option').text("All options are required");;
                        empty_option = true;
                    }
                });
            } else {
                checked = false;

                $('input[id^="option"]').each(function(i, e) {
                    if (this.value == '') {
                        $('#error-option').show();
                        $('#error-option').text("All options are required");;
                        empty_option = true;
                    }
                });


                if (!empty_option) {
                    $('input[id^="ans"]').each(function(i, e) {
                        if ($(this).is(":checked")) {
                            checked = true;
                        }
                    });

                    if (!checked) {
                        $('#error-option').show();
                        $('#error-option').text("Please Select One right answer");
                    }
                }
            }
        }

        if (!empty_option && checked && $('#group_add').val() != 0 && $('#level_add').val() != 0 && $('#question').val() != '' && $('#type').val() != 0 && $('#mark').val() != 0 && $('#mark').val() > 0) {
            $.ajax({
                url: '<?php echo base_url() ?>courses/create_question',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function(data) {
                    //showToast('Success');
                    //setTimeout(function () {
                    location.reload(true);
                    //  }, 5000);
                }
            });
        }
    }
</script>

<script>
    $(document).ready(function() {
        var pageValue = 10;
        var hasData = true;

        $("#seeMore").on('click', function() {

            var field = {
                'quiz_id': '<?= $quiz_id ?>',
                'chapter_id': $('#chapter_id').val(),
                'group_id': $('#group').val(),
                'level_id': $('#level').val(),
                'type_id': $('#question_type_id').val(),
                'course_id': "<?= $course_id ?>",
                'row': Number($('#row').val()),
            }

            var row = Number($('#row').val());
            var allcount = Number($('#all').val());
            row = row + pageValue;
            if (row <= allcount) {
                $("#row").val(row);
                if (hasData) {
                    $.ajax({
                        url: "<?= base_url('courses/quiz_question_list/') ?>" + row,
                        type: "get",
                        data: field,
                        dataType: 'html',
                        success: function(data) {
                            var response = JSON.parse(data);
                            console.log(response);
                            $('#load_question_list:last').after(response.render).show();
                            var rowno = row + pageValue;

                            if (rowno > allcount) {

                                $('#seeMore').text('No more data');

                            } else {
                                $("#seeMore").text("See More");
                            }
                        },
                        error: function(response) {
                            hasData = false;
                        }
                    });
                }
            } else {
                $('#seeMore').text('No more data');
            }
        });

    })
</script>

<?php
if (customCompute($select_options) || customCompute($select_answers)) {
    if ($typeID == 3) {
        $select_options =  $select_answers;
    } else {
        $select_options =  $select_options;
    }
    foreach ($select_options as $optionKey => $optionValue) {
?>
        <script type="text/javascript">
            var optID = '<?= $optionKey + 1 ?>';
            var optTypeID = '<?= $typeID ?>';
            var optVal = '<?= $optionValue ?>';
            var optAns = '';
            <?php if ($select_answers) { ?>
                var optAns = '<?= (in_array($optionKey + 1, $select_answers)) ? 'checked="checked"' : '' ?>';
            <?php } ?>
            $('#in').append(formHtmlData(optID, optTypeID, optVal, optAns));
        </script>
<?php     }
}
?>