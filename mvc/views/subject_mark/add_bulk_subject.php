<?php if ($siteinfos->note == 1) { ?>
    <div class="callout callout-danger">
        <p><b>Note:</b> Create exam, class, section before add mark</p>
    </div>
<?php } ?>

<header class="pg-header mt-4">
    <div>

        <h1 class="pg-title">

            <?= $this->lang->line('panel_title') ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li><a href="<?= base_url("subject_mark/index") ?>"><?= $this->lang->line('subject_mark') ?></a></li>
            <li class="active"><?= $this->lang->line('menu_add') ?> <?= $this->lang->line('subject_mark_add') ?></li>
        </ol>
    </div>
</header>
<div class="card card--spaced">
    <!-- <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> <?= $this->lang->line('panel_title') ?></h3>
        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li><a href="<?= base_url("mark/index") ?>"><?= $this->lang->line('menu_mark') ?></a></li>
            <li class="active"><?= $this->lang->line('menu_add') ?> <?= $this->lang->line('menu_mark') ?></li>
        </ol>
    </div> -->
    <!-- /.box-header -->
    <!-- form start -->
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">

                <form method="POST" enctype="multipart/form-data">

                    <div class="row">

                        <div class="col-md-3">
                            <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>">

                                <div class="md-form md-form--select">
                                    <?php
                                    $array = array("0" => $this->lang->line("subject_mark_select_classes"));
                                    foreach ($classes as $classa) {
                                        $array[$classa->classesID] = $classa->classes;
                                    }
                                    echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='mdb-select classesID'");
                                    ?>
                                    <label for="classesID" class="mdb-main-label">
                                        <?= $this->lang->line('subject_mark_select_classes') ?> <span class="text-red">*</span>
                                    </label>
                                    <span class="text-red"> <?php echo form_error('classesID'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="<?php echo form_error('examID') ? 'form-group has-error' : 'form-group'; ?>">
                                <div class="md-form md-form--select">

                                    <?php
                                    $array = array("0" => $this->lang->line("subject_mark_select_exam"));
                                    foreach ($exams as $exam) {
                                        $array[$exam->examID] = $exam->exam;
                                    }
                                    echo form_dropdown("examID", $array, set_value("examID"), "id='examID' class='mdb-select examID'");
                                    ?>
                                    <label for="examID" class="mdb-main-label">
                                        <?= $this->lang->line('subject_mark_select_exam') ?> <span class="text-red">*</span>
                                    </label>
                                    <span class="text-red"> <?php echo form_error('examID'); ?></span>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="list-inline">
                        <button type="submit" name="add_mark" class="btn btn-success">Search</button>

                    </div>



                </form>


                <?php if (customCompute($sendClasses)) { ?>
                    <div class="mt-3 mb-3 border card-body">
                        <h3 class="mt-0"> <?php echo $this->lang->line('mark_details'); ?></h3>
                        <div class="row">
                            <div class="col-md-3">
                                <small> <?php echo $this->lang->line('mark_exam'); ?></small>
                                <h4> <b><?php echo $sendExam->exam ?></b> </h4>
                            </div>
                            <div class="col-md-3">
                                <small> <?php echo $this->lang->line('mark_classes'); ?></small>
                                <h4> <b><?php echo $sendClasses->classes ?></b> </h4>
                            </div>
                            <!-- <div class="col-md-3">
                                <small> <?php echo $this->lang->line('mark_section'); ?></small>
                                <h4> <b><?php echo $sendSection->section ?></b> </h4>
                            </div> -->
                        </div>

                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-12">
                <?php
                if (customCompute($sendSubject)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th><?= $this->lang->line('slno') ?></th>
                                    <th>Subject</th>
                                    <th>Full Mark</th>
                                    <th>Pass Mark</th>
                                    <th>Order</th>
                                    <th>Show in report</th>

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <form method="post" id="myform">
                                    <input type="hidden" name="exam_id" value="<?= $sendExam->examID; ?>">
                                    <input type="hidden" name="class_id" value="<?= $sendClasses->classesID; ?>">
                                    <?php $i = 1;
                                    foreach ($sendSubject as $value) { ?>
                                        <tr>

                                            <td data-title="<?= $this->lang->line('slno') ?>">
                                                <input type="hidden" name="id" value="<?= isset($value->id) ? $value->id : '' ?>">
                                                <?php echo $i; ?>
                                            </td>
                                            <td data-title="">
                                                <?= $value->subject; ?>
                                            </td>
                                            <td data-title="">
                                                <input class="form-control mark" type="number" name="subject[<?= $value->subjectID; ?>][]" value="<?= isset($value->fullmark) ? $value->fullmark : ''; ?>" required>
                                            </td>
                                            <td data-title="">
                                                <input class="form-control mark" type="number" name="subject[<?= $value->subjectID; ?>][]" value="<?= isset($value->passmark) ? $value->passmark : ''; ?>" required>
                                            </td>
                                            </td>

                                            <td data-title="">
                                                <input class="form-control mark" type="number" name="subject[<?= $value->subjectID; ?>][]" value="<?= isset($value->order_no) ? $value->order_no : ''; ?>" required>
                                            </td>
                                            </td>

                                            <td data-title="">
                                                <?php
                                                if ($value->coscholatics != 0) { ?>
                                                    <input class="checkbox subject-check-input" type="checkbox" name="subject[<?= $value->subjectID; ?>][]" value="0" <?php
                                                                                                                                                                        if ($value->no_coscholastic == 0)
                                                                                                                                                                            echo "checked";
                                                                                                                                                                        else
                                                                                                                                                                            echo "";
                                                                                                                                                                        ?>>

                                                <?php }  ?>

                                            </td>
                                            </td>



                                        </tr>
                                    <?php $i++;
                                    }

                                    ?>
                                </form>
                            </tbody>
                        </table>
                    </div>
                    <input type="button" class="btn btn-success" id="update_subject" name="add_mark" value="Update" />

                    <script type="text/javascript">
                        window.addEventListener('load', function() {
                            setTimeout(lazyLoad, 1000);
                        });

                        function lazyLoad() {
                            var card_images = document.querySelectorAll('.card-image');
                            card_images.forEach(function(card_image) {
                                var image_url = card_image.getAttribute('data-image-full');
                                var content_image = card_image.querySelector('img');
                                content_image.src = image_url;
                                content_image.addEventListener('load', function() {
                                    card_image.style.backgroundImage = 'url(' + image_url + ')';
                                    card_image.className = card_image.className + ' is-loaded';
                                });
                            });
                        }

                        $(document).on("keyup", ".mark", function() {
                            if (parseInt($(this).val())) {
                                var val = parseInt($(this).val());
                                var minMark = parseInt($(this).attr('min'));
                                var maxMark = parseInt($(this).attr('max'));
                                if (minMark > val || val > maxMark) {
                                    $(this).val('');
                                }
                            } else {
                                if ($(this).val() == '0') {} else {
                                    $(this).val('');
                                }
                            }
                        });

                        $("#update_subject").click(function() {
                            var inputs = "";
                            var inputs_value = "";
                            var form = $('#myform');
                            // var mark = $('input[name^=mark]').map(function(){
                            //     return { markpercentageid:this.id, mark: this.name , value: this.value};
                            // }).get();

                            $.ajax({
                                type: 'POST',
                                url: "<?= base_url('subject_mark/update_bulk_subject_mark') ?>",
                                data: form.serialize(),
                                dataType: "html",
                                success: function(data) {
                                    console.log(data);
                                    var response = jQuery.parseJSON(data);
                                    if (response.status) {
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
                                    } else {
                                        if (response.error) {
                                            toastr["error"](response.message)
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
                                        }
                                    }
                                }
                            });
                        });
                    </script>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".select_grade").on('change', function() {
        $(this).siblings($('input[id="' + $(this).attr('mid') + '"]')).val($(this).val());
    })

    $('.select2').select2();
    $("#classesID").change(function() {
        var classesID = $(this).val();
        if (parseInt(classesID)) {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('mark/examcall') ?>",
                data: {
                    "classesID": classesID
                },
                dataType: "html",
                success: function(data) {
                    $('#examID').html(data);
                    $('.mdb-select').material_select('destroy');
                    $('.mdb-select').material_select();
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?= base_url('mark/subjectcall') ?>",
                data: {
                    "id": classesID
                },
                dataType: "html",
                success: function(data) {
                    $('#subjectID').html(data);
                    $('.mdb-select').material_select('destroy');
                    $('.mdb-select').material_select();
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?= base_url('mark/sectioncall') ?>",
                data: {
                    "id": classesID
                },
                dataType: "html",
                success: function(data) {
                    $('#sectionID').html(data);
                    $('.mdb-select').material_select('destroy');
                    $('.mdb-select').material_select();
                }
            });
        }
    });
</script>