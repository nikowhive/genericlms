<header class="pg-header mt-4">
    <h1 class="pg-title">
        <div>
            <h2>School Exam</h2>
        </div>

    </h1>
    <div class="list-inline">
        <?php if (permissionChecker('exam_add')) { ?>
            <!-- <a href="<?php echo base_url('exam/add') ?>" class="btn-sm btn btn-success waves-effect waves-light"><i class="fa fa-plus"></i> Add Exam</a> -->
            <a href="javascript:void(0)" data-toggle="modal" data-target="#addexam" class="btn-sm btn btn-success waves-effect waves-light add-exam"><i class="fa fa-plus"></i> Add Exam</a>
        <?php } ?>


        <?php if (($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
            <?php if (permissionChecker('online_exam_add')) { ?>
                <a href="<?php echo base_url('online_exam/add') ?>" class="btn-sm btn btn-success waves-effect waves-light"><i class="fa fa-plus"></i> Add Online Exam</a>
                <a href="<?php echo base_url('exam_setting/add') ?>" class="btn-sm btn btn-success waves-effect waves-light"><i class="fa fa-plus"></i> Add Setting</a>
        <?php }
        } ?>
    </div>


</header>

<div class="sortable-list">
    <ul id="course" class="course-wrapper">
        <?php if (customCompute($exams)) {
            $i = 1;
            foreach ($exams as $__index => $exam) {
                if ($exam->type == "Offline") { ?>
                    <li style="margin-bottom:20px;">
                        <div class="sortable-block sortable-blockunit">
                            <div class="sortable-header">
                                <!-- <div class="panned-icon">⋮⋮</div> -->
                                <div class="panned-icon"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                <h3 class="sortable-title" data-id="">
                                    <small>
                                        <?= $exam->exam ?> <span class="label label-primary"><?= $exam->type ?></span> <span class="label label-primary"><?php echo $exam->is_final_term == 1 ? 'Yes' : 'No' ?></span></small>

                                    <?php echo $exam->note; ?>
                                    <div class="text-danger h5 mt-2"> <?php echo date("d M Y", strtotime($exam->date)); ?></div>
                                </h3>
                            </div>
                            <div class="sortable-actions">
                                <?php if (permissionChecker('exam_edit') || permissionChecker('exam_delete')) { ?>
                                    <label class="switch" data-toggle="tooltip" data-placement="top" data-original-title="Publish/Unpublish">
                                        <input type="checkbox" class="switch__input" onclick="changeExamStatus('<?= $exam->examID ?>','<?= $exam->type?>')" class="onoffswitch-small-checkbox" data-type="<?= $exam->type ?>" id="switch-link<?= $exam->examID ?>" <?= $exam->published == '1' ? "checked='checked'" : ''; ?>>
                                        <span class="switch--unchecked">
                                            <i class="fa fa-ban"></i>
                                        </span>
                                        <span class="switch--checked">
                                            <i class="fa fa-check-circle"></i>
                                        </span>
                                    </label>



                                    <div class="dropdown">
                                        <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                        <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">

                                            <li>
                                                <a href="javascript:void(0)" class="edit-exam" data-id="<?= $exam->examID ?>">Edit Exam</a>
                                            </li>
                                            <li>
                                                <a onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" href="<?php echo base_url() . 'exam1/delete/' . $exam->examID ?>">Delete Exam</a>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </li>
                <?php } elseif ($exam->type == "Online") { ?>
                    <?php $showStatus = FALSE;
                    if ($usertypeID == '3') {
                        if (customCompute($student)) {
                            if ((($student->srclassesID == $exam->classID) || ($exam->classID == '0')) && (($student->srsectionID == $exam->sectionID) || ($exam->sectionID == '0')) && (($student->srstudentgroupID == $exam->studentGroupID) || ($exam->studentGroupID == '0')) && ($exam->published == 1)) {

                                if (isset($opsubject[$exam->subjectID])) {
                                    if ($exam->subjectID == $student->sroptionalsubjectID) {
                                        $showStatus = TRUE;
                                        $i++;
                                    }
                                } else {
                                    $showStatus = TRUE;
                                    $i++;
                                }
                            }
                        }
                    } else {
                        $i++;
                        $showStatus = TRUE;
                    } ?>
                    <?php if ($showStatus) { ?>
                        <li style="margin-bottom:20px;">
                            <div class="sortable-block sortable-blockunit">
                                <div class="sortable-header">
                                    <!-- <div class="panned-icon">⋮⋮</div> -->
                                    <div class="panned-icon"><i class="fa fa-youtube-play" aria-hidden="true"></i></div>
                                    <h3 class="sortable-title">
                                        <small>
                                            <?= $exam->subject ? $exam->subject->subject : ''; ?>
                                            <?= $exam->class ? $exam->class->classes : ''; ?>
                                            <span class="label label-primary"><?= $exam->type ?></span>
                                        </small>

                                        <?php
                                        if (strlen($exam->name) > 25)
                                            echo strip_tags(substr($exam->name, 0, 25) . "...");
                                        else
                                            echo strip_tags(substr($exam->name, 0, 25));
                                        ?>
                                        <div class="text-danger h5 mt-2"><b> <?php echo $exam->startDateTime; ?> - <?php echo $exam->endDateTime; ?> / </b></div>

                                    </h3>

                                </div>
                                <div class="sortable-actions">
                                    <?php if (permissionChecker('online_exam_edit') || permissionChecker('online_exam_delete')) { ?>
                                        <label class="switch" data-toggle="tooltip" data-placement="top" data-original-title="Publish/Unpublish">
                                            <input type="checkbox" class="switch__input" data-type="<?= $exam->type ?>" onclick="changeExamStatus('<?= $exam->onlineExamID ?>','<?= $exam->type?>')" class="onoffswitch-small-checkbox" id="switch-link<?= $exam->onlineExamID ?>" <?= $exam->published == '1' ? "checked='checked'" : ''; ?>>
                                            <span class="switch--unchecked">
                                                <i class="fa fa-ban"></i>
                                            </span>
                                            <span class="switch--checked">
                                                <i class="fa fa-check-circle"></i>
                                            </span>
                                        </label>



                                        <div class="dropdown">
                                            <a href="#" class=" " data-toggle="dropdown"> ⋮</a>
                                            <ul id="menu2" class="dropdown-menu" aria-labelledby="drop5">

                                                <li>
                                                    <a href="<?php echo base_url() . 'online_exam/addquestion/' . $exam->onlineExamID ?>">Add question</a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url() . 'online_exam/edit/' . $exam->onlineExamID ?>">Edit Online Exam</a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url('online_exam/togglepublish/' . $exam->onlineExamID) ?>"><?= !$exam->auto_published ? 'Enable Auto publish' : 'Disable Auto publish' ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url('online_exam/publishresult/' . $exam->onlineExamID) ?>"><?= !$exam->result_published ? 'Publish Result' : 'Publish Again' ?></a>
                                                </li>

                                                <li>
                                                    <a onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" href="<?= base_url('online_exam/delete/') . $exam->onlineExamID ?>">Delete Online Exam</a>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>
                        </li>

                <?php }
                } ?>
        <?php $i++;
            }
        } ?>
    </ul>
</div>

<script type="text/javascript" src="<?= base_url() . 'assets/lightbox2-2.11.3/dist/js/lightbox.js'; ?>"></script>



<script type="text/javascript">
    $('.select2').select2();

    $(function() {
        issue_date = $('#issue_date').val();
        if (issue_date != 0) {
            date = issue_date.split("-");

            console.log(date)
            converted_nepali_date = calendarFunctions.bsDateFormat("%y-%m-%d", parseInt(date[0]), parseInt(date[1]), parseInt(date[2]))
            $('#issue_date').val(converted_nepali_date);

            console.log(converted_nepali_date);
        }

        $('#issue_date').nepaliDatePicker({
            dateFormat: "%y-%m-%d",
            closeOnDateSelect: true,
            // minDate: 'सोम, जेठ १०, २०७३',
            // maxDate: 'मंगल, जेठ ३२, २०७३'
        });
    });


    $("#date").datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate: '<?= $schoolyearobj->startingdate ?>',
        endDate: '<?= $schoolyearobj->endingdate ?>',
    });

    // $('#add_exam').one('submit', function(e) {
    //     e.preventDefault();
    //     issue_date = $('#issue_date').val();
    //     if(issue_date != 0) {
    //         parsed_date = calendarFunctions.parseFormattedBsDate("%y-%m-%d", issue_date)
    //         eng_date = parsed_date.bsYear+'-'+parsed_date.bsMonth+'-'+parsed_date.bsDate
    //         $('#issue_date').val(eng_date);
    //     }
    //     $(this).submit();
    // });
</script>


<script>
    var pageValue = 0;
    var hasData = true
    $(window).scroll(function () {
        if ($(window).scrollTop() == $(document).height() - $(window).height()) {
            pageValue += 7;
            
            if(hasData) {
                $.ajax({
                    url: "<?=base_url('exam1/getMoreFeedData/')?>" + pageValue,
                    type: "get",
                    success: function (response) {
                        $('#feeds-data').append(response);
                    },
                    error: function (response) {
                        hasData = false;
                    }
                });
            }
        }
    });

    $('.add-exam').click(function(e) {
        $('#exam-error').html('');
        $("#add_exam").trigger("reset");

    });

    $('.edit-exam').on('click', function(e) {
        $('#exam-error').html('');
        var id = $(this).data("id");

        $.ajax({
            type: "POST",
            url: BASE_URL + "exam1/getExamByAjax",
            dataType: "html",
            data: {
                id: id
            },
            success: function(data) {

                var obj = $.parseJSON(data);
                console.log(obj);

                $('#exam').val(obj['exam']);
                $('#date').val(obj['date']);
                $('#note').val(obj['note']);
                $('#issue_date').val(obj['issue_date']);
                $('#order_no').val(obj['order_no']);
                $('#exam_id').val(id);

                $("#addexam").modal("show");
                // $("#addexam").html("");
                // $("#view_ajax_attachment").append(data);
            },
        });
    });

    $('body').on('submit', '#add_exam', function(e) {
        e.preventDefault();
        // alert('hi');
        var id = $('#exam_id').val();
        // alert(id);
        if (id) {
            var url = '<?= base_url('exam1/edit/') ?>' + id;
        } else {
            var url = '<?= base_url('exam1/add') ?>';
        }
        // $('#unit-error3').text('');
        var frm = $('#add_exam');
        $.ajax({
            url: url,
            method: 'post',
            data: frm.serialize(),
            dataType: 'html',
            type: 'post',
            success: function(data) {
                console.log(data);
                var response = jQuery.parseJSON(data);

                if (response.status) {

                    if (response.render) {
                        toastr["success"](response.message)
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
                        location.reload();

                        // window.location.href=response.url;

                    }


                    if (response.error) {

                        $('#exam-error').html(response.error);

                    }
                }

            }
        });


    });




   

    function changeExamStatus(id) {
      var type=  $("#switch-link"+id).data("type");
    //   alert(type);
      if(type=="Offline"){
         url = "<?= base_url('exam/postChangeExamStatus/') ?>";
      }
      else if(type=="Online")
      {
         url = "<?= base_url('online_exam/postChangeExamStatus/') ?>";
      }
    //    alert(url);
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