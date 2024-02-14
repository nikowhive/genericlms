<?php if ($siteinfos->note == 1) { ?>
    <div class="callout callout-danger">
        <p><b>Note:</b> Create exam, class, section & subject before add mark</p>
    </div>
<?php } ?>

<header class="pg-header mt-4">
    <div>

        <h1 class="pg-title">
            <?= $this->lang->line('panel_title') ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"> <?= $this->lang->line('menu_dashboard') ?></a></li>
            <li class="active">Add Bulk Mark</li>
        </ol>
    </div>
</header>
<div class="card card--spaced">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">

                <form method="POST" enctype="multipart/form-data">

                    <div class="row">

                        <div class="col-md-3">
                            <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>">

                                <div class="md-form md-form--select">
                                    <?php
                                    $array = array("0" => $this->lang->line("mark_select_classes"));
                                    foreach ($classes as $classa) {
                                        $array[$classa->classesID] = $classa->classes;
                                    }
                                    echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='mdb-select classesID'");
                                    ?>
                                    <label for="classesID" class="mdb-main-label">
                                        <?= $this->lang->line('mark_classes') ?> <span class="text-red">*</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="<?php echo form_error('examID') ? 'form-group has-error' : 'form-group'; ?>">
                                <div class="md-form md-form--select">

                                    <?php
                                    $array = array("0" => $this->lang->line("mark_select_exam"));
                                    foreach ($exams as $exam) {
                                        $array[$exam->examID] = $exam->exam;
                                    }
                                    echo form_dropdown("examID", $array, set_value("examID"), "id='examID' class='mdb-select examID'");
                                    ?>
                                    <label for="examID" class="mdb-main-label">
                                        <?= $this->lang->line('mark_exam') ?> <span class="text-red">*</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>">

                                <div class="md-form md-form--select">
                                    <?php
                                    $arraysection = array('0' => $this->lang->line("mark_select_section"));
                                    if (customCompute($sections)) {
                                        foreach ($sections as $section) {
                                            $arraysection[$section->sectionID] = $section->section;
                                        }
                                    }
                                    echo form_dropdown("sectionID", $arraysection, set_value("sectionID"), "id='sectionID' class='mdb-select'");
                                    ?>
                                    <label class="mdb-main-label"><?= $this->lang->line('mark_section') ?> <span class="text-red">*</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="<?php echo form_error('excel') ? 'form-group has-error' : 'form-group'; ?>">

                                <div class="md-form md-form--file">
                                    <div class="file-field">
                                        <div class="btn btn-success btn-sm float-left waves-effect waves-light">
                                            <span>Upload Excel</span>
                                            <input type="file" name="excel" accept=".xlsx, .xls">
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input class="file-path validate form-control invalid" type="text" name="file_name1[]" placeholder="Upload your file" readonly="">
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="list-inline">
                        <button type="submit" name="download_template" class="btn btn-default">Download Template</button>
                        <button type="submit" name="bulk_import" class="btn btn-default">Import Marks</button>
                    </div>



                </form>
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