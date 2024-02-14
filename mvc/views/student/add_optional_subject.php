<?php if ($siteinfos->note == 1) { ?>
    <div class="callout callout-danger">
        <p><b>Note:</b> Create class, section before add subject</p>
    </div>
<?php } ?>

<header class="pg-header mt-4">
    <div>

        <h1 class="pg-title">

            Bulk optional Subject
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li><a href="">Optional Subject</a></li>
            <li class="active">Add </li>
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
                                    $array = array("0" => "Select class");
                                    foreach ($classes as $classa) {
                                        $array[$classa->classesID] = $classa->classes;
                                    }
                                    echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='mdb-select classesID'");
                                    ?>
                                    <label for="classesID" class="mdb-main-label">
                                        <?= $this->lang->line("student_classes") ?> <span class="text-red">*</span>
                                    </label>
                                    <span class="text-red"> <?php echo form_error('classesID'); ?></span>
                                </div>
                            </div>
                        </div>



                        <div class="col-md-3">
                            <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>">

                                <div class="md-form md-form--select">
                                    <?php
                                    $arraysection = array('0' => "Select Section");
                                    if (customCompute($sections)) {
                                        foreach ($sections as $section) {
                                            $arraysection[$section->sectionID] = $section->section;
                                        }
                                    }
                                    echo form_dropdown("sectionID", $arraysection, set_value("sectionID"), "id='sectionID' class='mdb-select'");
                                    ?>
                                    <label class="mdb-main-label">Section <span class="text-red">*</span></label>
                                    <span class="text-red"> <?php echo form_error('sectionID'); ?></span>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="list-inline">
                                <button type="submit" name="" class="btn btn-success">Search</button>

                            </div>
                        </div>

                    </div>





                </form>


                <?php if (customCompute($sendClasses) && customCompute($sendSection)) { ?>
                    <div class="mt-3 mb-3 border card-body">
                        <h3 class="mt-0"> Class Details</h3>
                        <div class="row">

                            <div class="col-md-3">
                                <small> <?php echo $this->lang->line('class'); ?></small>
                                <h4> <b><?php echo $sendClasses->classes ?></b> </h4>
                            </div>
                            <div class="col-md-3">
                                <small> <?php echo $this->lang->line('student_section'); ?></small>
                                <h4> <b><?php echo $sendSection->section ?></b> </h4>
                            </div>

                        </div>

                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-12">
                <?php
                if (customCompute($students)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th><?= $this->lang->line('slno') ?></th>
                                    <th><?= $this->lang->line('student_photo') ?></th>
                                    <th><?= $this->lang->line('student_name') ?></th>
                                    <th><?= $this->lang->line('student_roll') ?></th>
                                    <!-- <th>Optional I</th>
                                    <<th>Optional II</th> -->
                                    <?php
                                    foreach ($optional_subjects as $i => $data) {; ?>
                                        <th><input type="checkbox" data-id="<?php echo $data->subjectID; ?>" class="select-subject" id="selectall <?= $data->subjectID ?>"> <?php echo $data->subject; ?>
                                            <input type="hidden" name="<?= $data->subject ?>" id="hidden-subject">
                                        </th>

                                    <?php }
                                    ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <form id="myform">
                                    <?php if (customCompute($students)) {
                                        $i = 1;
                                        foreach ($students as $student) {  ?>
                                            <input type="hidden" name="class" value="<?= $sendClasses->classesID ?>">
                                            <input type="hidden" name="section" value="<?= $sendSection->sectionID ?>">
                                            <tr>
                                                <td data-title="<?= $this->lang->line('slno') ?>">
                                                    <?php echo $i; ?>
                                                </td>
                                                <td data-title="<?= $this->lang->line('mark_photo') ?>">
                                                    <?= profileproimage($student->photo) ?>
                                                </td>
                                                <td data-title="<?= $this->lang->line('mark_name') ?>">
                                                    <?php echo $student->name; ?>
                                                </td>
                                                <td data-title="<?= $this->lang->line('mark_roll') ?>">
                                                    <?php echo $student->roll; ?>
                                                </td>
                                                <?php
                                                foreach ($optional_subjects as $data) { ?>
                                                    <td align="center"> <input class="checkbox subject-check-input-<?= $data->subjectID ?>" type="checkbox" name="subjects[<?= $student->srstudentID ?>][]" value="<?= $data->subjectID ? $data->subjectID : 0 ?>" <?= $data->subjectID == $student->sroptionalsubjectID || $data->subjectID == $student->sranotheroptionalsubjectID ? 'checked' : '' ?>></td>

                                                <?php }
                                                ?>

                                            </tr>
                                    <?php $i++;
                                        }
                                    } ?>
                                </form>
                            </tbody>
                        </table>
                    </div>
                    <input type="button" class="btn btn-success" id="add_optional_subject" name="add_add_optional_subjectmark" value="Update" />

                    <script type="text/javascript">
                        window.addEventListener('load', function() {
                            setTimeout(lazyLoad, 1000);
                        });

                        $(".select-subject").each(function() {
                            var id = $(this).data("id");

                            $(this).on("click", function() {

                                if (this.checked) {
                                    $(".subject-check-input-" + id).each(function() {
                                        this.checked = true;
                                    })
                                } else {
                                    $(".subject-check-input-" + id).each(function() {
                                        this.checked = false;
                                    })
                                }

                            });

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
                    </script>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(params) {
        $('#add_optional_subject').on('click', function() {


            var form = $('#myform');
            $.ajax({
                type: 'POST',
                url: "<?= base_url('optionalsubject/add_optional_subject') ?>",
                data: form.serialize(),
                dataType: "html",
                success: function(data) {


                    var response = jQuery.parseJSON(data);
                    console.log(response);
                    if (response.status) {
                        toastr["success"](response.message)
                        
                    } else {
                        if (response.error) {
                            toastr["error"](response.message)
                            
                        }
                    }


                },

            });

        });
    })
</script>

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