<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

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
                    <?php if (permissionChecker('quiz_add')) : ?>
                        <a href="javascript:;" data-toggle="modal" data-target="#addQuiz" class="btn-sm btn btn-success"><i class="fa fa-plus"></i> Add quiz</a>
                    <?php endif; ?>
                </header>

                <div class="sortable-list">
                    <ul id="course" class="course-wrapper">

                        <?php foreach ($quizzes as $quiz) { ?>
                            <li style="margin-bottom:20px;
                            background-color: white;
                            border-radius: 10px;
                            border-color: #b7b7b773;
                            border-width: 2px;
                            border-style: solid;">
                                <div class="sortable-block sortable-blockunit">
                                    <div class="sortable-header">
                                        <!-- <div class="panned-icon">⋮⋮</div> -->
                                        <a class="btn btn-sm btn-link collapsed" role="button" data-toggle="collapse" <?= empty($quiz->questions) ? "disabled='disabled'" : ''; ?> href="#question<?= $quiz->id ?>" aria-expanded="true">
                                            <i class="fa fa-angle-down"></i>
                                        </a>

                                        <div class="panned-icon"><i class="fa fa-puzzle-piece" aria-hidden="true"></i></div>
                                        <h3 class="quiz-title sortable-title" data-id="<?= $quiz->id ?>" data-course="<?= $course->id; ?>">

                                            <small>
                                                <?php echo $quiz->unit; ?> - <?php echo $quiz->chapter_name; ?></small>
                                            <?php echo $quiz->quiz_name; ?>
                                        </h3>

                                    </div>
                                    <div class="sortable-actions">
                                        <?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
                                            <label class="switch" data-toggle="tooltip" data-placement="top" data-original-title="Publish/Unpublish">
                                                <input type="checkbox" class="switch__input" onclick="changeQuizStatus('<?= $quiz->id ?>')" class="onoffswitch-small-checkbox" id="switch-quiz<?= $quiz->unit_id ?>" <?= $quiz->published == '1' ? "checked='checked'" : ''; ?>>
                                                <span class="switch--unchecked">
                                                    <i class="fa fa-ban"></i>
                                                </span>
                                                <span class="switch--checked">
                                                    <i class="fa fa-check-circle"></i>
                                                </span>
                                            </label>
                                        <?php endif; ?>

                                        <?php if(
                                                        permissionChecker('quiz_edit')
                                                        || permissionChecker('quiz_delete') 
                                                ) : ?>

                                        <div class="dropdown">
                                            <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                            <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">
                                                <?php if (permissionChecker('quiz_edit')) : ?>
                                                    <li>
                                                        <a href="<?php echo base_url() . 'courses/new_quiz_ui/' . $quiz->id . '/' . $quiz->coursechapter_id . '?course=' . $course->id ?>">Edit Quiz</a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if (permissionChecker('quiz_delete')) : ?>
                                                    <li>
                                                        <a onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" href="<?php echo base_url() . 'courses/deletequiz/' . $quiz->id . '/' . $quiz->coursechapter_id . '?course=' . $course->id . '&link=' . 'quizzes' ?>">Delete Quiz</a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                        <?php endif; ?>

                                    </div>
                                </div>

                                <ul id="question<?= $quiz->id ?>" class="collapse unit-wrapper" style="margin-top:10px;">
                                    <?php foreach ($quiz->questions as $y => $question) { ?>
                                        <li class="sortable-content" data-row-id="<?= $question->questionBankID ?>" data-index=<?= $y ?>>
                                            <div class="sortable-block" style="padding: 0px 16px 0px 16px;">
                                                <div class="sortable-header">
                                                    <?php if ($usertypeID == 1 || $usertypeID == 2) : ?> <div class="panned-icon">⋮⋮ </div> <?php endif; ?>
                                                    <div class="header-icon"><i class="fa fa-book" aria-hidden="true"></i></div>
                                                    <h4 class="sortable-title">
                                                        <?= $question->question ?>
                                                    </h4>
                                                </div>
                                                <div class="sortable-actions">
                                                    <?php if (permissionChecker('courses_add')) : ?>
                                                        <a href="<?php echo base_url() . 'question_bank/view/' . $question->questionBankID ?>" class="btn btn-m mrg" data-placement="top" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>

                                </ul>
                            </li>
                        <?php
                        } ?>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- add quiz modal starts -->
<div class="modal fade" tabindex="-1" role="dialog" id="addQuiz">
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
                            $array    = array();
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

                    <div class='form-group'>
                        <div class="md-form">
                            <label for="quiz_name">Quiz Title</label>
                            <input type="text" class="form-control" id="quiz_name" name="quiz_name">
                            <span class="text-danger error">
                                <p id="title-error"></p>
                            </span>
                        </div>
                    </div>

                    <div class='form-group'>
                        <div class="md-form">
                            <label for="percentage_coverage">Percentage</label>
                            <input type="text" class="form-control" id="percentage_coverage" name="percentage_coverage">
                            <span class="text-danger error">
                                <p id="percentage-error"></p>
                            </span>
                        </div>
                    </div>
                </form>
                <input type="hidden" id="ajax-get-chapter-url" value="<?php echo base_url() ?>chapter/ajaxGetChaptersFromUnitId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="add-quiz" class="btn btn-primary">Add Quiz</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- add quiz modal ends -->

<script>
    $('.quiz-switch').click(function(e) {
        quizid = $(this).attr("quizid")
        $.ajax({
            type: 'POST',
            url: "<?= base_url('courses/ajaxChangeQuizStatus/') ?>" + quizid,
            dataType: "html",
            success: function(data) {
                console.log('updated');
            }
        });
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

    $('#add-quiz').click(function(e) {
        unit_id = $('#unit_id').val();
        chapter_id = $('#chapter_id').val();
        quiz_name = $('#quiz_name').val();
        percentage_coverage = $('#percentage_coverage').val();
        course_id = "<?php echo $course->id; ?>"

        $('#unit-error').text('');
        $('#chapter-error').text('');
        $('#title-error').text('');
        $('#percentage-error').text('');
        $('#form-error').text('');

        // if (unit_id == 0) {
        //     $('#unit-error').text('Unit is empty.');
        // }
        // if (chapter_id == 0) {
        //     $('#chapter-error').text('Chapter is empty.');
        // }
        if (quiz_name == '') {
            $('#title-error').text('Title is empty.');
        }
        if (percentage_coverage == '') {
            $('#percentage-error').text('Percentage Coverage is empty.');
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
                            $('#percentage-error').html(response.error.percentage_coverage);
                        }
                        if (response.error.quiz_name) {
                            $('#title-error').html(response.error.quiz_name);
                        }
                    }
                }
            }
        });
        // }
    })
</script>

<?php if ($usertypeID == 1 || $usertypeID == 2) : ?>
    <script>
        let url = "<?= base_url('courses/ajaxChangeQuizStatus/') ?>";

        function changeQuizStatus(id) {
            $.post(url + id).done(function() {
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

    <script>
        $(document).ready(function() {

            <?php if ($usertypeID == 1 || $usertypeID == 2) { ?>
                chapter_ids = <?php echo json_encode($quizzes_id) ?>;

                chapter_ids.forEach(function(chapterId) {
                    var element = document.getElementById('question' + chapterId);
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

                                var positions = [];

                                $('#question' + chapterId).children('li').each(function(li) {


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
                                        rowId: $(this).data('row-id'),
                                        quizzId: chapterId
                                    });

                                });



                                $.ajax({
                                    url: "<?= base_url('courses/changeQuizQuestionOrder/') ?>",
                                    type: "post",
                                    data: {
                                        positions: JSON.stringify(positions)
                                    },
                                    success: function(response) {
                                        console.log(response);
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

<?php endif; ?>