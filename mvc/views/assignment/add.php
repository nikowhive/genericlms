<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<div class="right-side--fullHeight  ">

    <div class="row w-100 ">
        <?php $this->load->view("components/course_menu"); ?>

        <div class="<?php echo isset($course) ? 'course-content' : 'col-md-12' ?>">
            <div class="container container--sm">
                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                        <div><small>Course</small></div>
                        <?= $this->lang->line('panel_title') ?>
                    </h1>
                </header>
                <div class="card card--spaced">
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="card-body">

                        <form class=" " enctype="multipart/form-data" role="form" method="post" id="add_assignment">

                            <?php
                            if (form_error('title'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                            ?>

                            <div class="md-form">
                                <label for="title">
                                    <?= $this->lang->line("assignment_title") ?> <span class="text-red">*</span>
                                </label>
                                <input type="text" placeholder="Enter assignment title here..." class="form-control" id="title" name="title" value="<?= set_value('title') ?>">
                                <span class="text-danger error">
                                    <?php echo form_error('title'); ?>
                                </span>
                            </div>



                    </div>

                    <?php
                    if (form_error('description'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                    <label for="description">
                        <?= $this->lang->line("assignment_description") ?> <span class="text-red">*</span>
                    </label>
                    <div class="md-form">

                        <textarea class="md-textarea form-control" id="description" name="description" rows="4"><?= set_value('description') ?></textarea>

                        <span class="text-danger error">
                            <?php echo form_error('description'); ?>
                        </span>
                    </div>
                </div>

                <?php
                if (form_error('deadlinedate'))
                    echo "<div class='form-group has-error' >";
                else
                    echo "<div class='form-group' >";
                ?>
                <div class="md-form">
                    <label for="deadlinedate">
                        <?= $this->lang->line("assignment_deadlinedate") ?> <span class="text-red">*</span>
                    </label>
                    <input type="text" autocomplete="off" class="form-control" id="deadlinedate" name="deadlinedate" value="<?= set_value('deadlinedate') ? set_value('deadlinedate') : date("d-m-Y") ?>">
                    <span class="text-danger error">
                        <?php echo form_error('deadlinedate'); ?>
                    </span>
                </div>
            </div>

            <?php
            if (form_error('classesID'))
                echo "<div class='form-group has-error' >";
            else
                echo "<div class='form-group' >";
            ?>
            <div class="md-form--select md-form">
                <?php
                $array = array();
                $array[0] = $this->lang->line("assignment_select_classes");

                foreach ($classes as $classa) {
                    $array[$classa->classesID] = $classa->classes;
                }

                $disabled = ($classesID != 0) ? 'disabled' : '';

                echo form_dropdown("classesID", $array, set_value("classesID", $classesID), "id='classesID' class='mdb-select' $disabled");
                ?>
                <label for="classesID" class="mdb-main-label">
                    <?= $this->lang->line("assignment_classes") ?> <span class="text-red">*</span>
                </label>
                <span class="text-danger error">
                    <?php echo form_error('classesID'); ?>
                </span>
            </div>
        </div>

        <?php
        if (form_error('sectionID'))
            echo "<div class='form-group has-error' >";
        else
            echo "<div class='form-group' >";
        ?>

        <div class="md-form--select md-form">
            <?php
            $array = array();
            if ($sections != "empty") {
                foreach ($sections as $section) {
                    $array[$section->sectionID] = $section->section;
                }
            }

            echo form_multiselect("sectionID[]", $array, set_value("sectionID"), "id='sectionID' class='mdb-select'");
            ?>
            <label for="sectionID" class="mdb-main-label">
                <?= $this->lang->line("assignment_section") ?>
            </label>
            <span class="text-danger error">
                <?php echo form_error('sectionID'); ?>
            </span>
        </div>

        <!-- <label for="sectionID" class="col-sm-2 control-label">
                                    <?= $this->lang->line("assignment_section") ?> 
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                    $array = array();
                                    if ($sections != "empty") {
                                        foreach ($sections as $section) {
                                            $array[$section->sectionID] = $section->section;
                                        }
                                    }

                                    echo form_multiselect("sectionID[]", $array, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                                    ?>
                                </div>
                        
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('sectionID'); ?>
                                </span> -->
    </div>

    <?php
    if (form_error('subjectID'))
        echo "<div class='form-group has-error' >";
    else
        echo "<div class='form-group' >";
    ?>

    <div class="md-form--select md-form">
        <?php
        $array = array('0' => $this->lang->line("assignment_select_subject"));
        if ($subjects != "empty") {
            foreach ($subjects as $subject) {
                $array[$subject->subjectID] = $subject->subject;
            }
        }

        $disabled = ($subjectID != 0) ? 'disabled' : '';

        echo form_dropdown("subjectID", $array, set_value("subjectID", $subjectID), "id='subjectID' class='mdb-select' $disabled");
        ?>
        <label for="subjectID" class="mdb-main-label">
            <?= $this->lang->line("assignment_subject") ?> <span class="text-red">*</span>
        </label>
        <span class="text-danger error">
            <?php echo form_error('subjectID'); ?>
        </span>
    </div>
</div>
<?php
if (form_error('unitId'))
    echo "<div class='form-group has-error' >";
else
    echo "<div class='form-group' >";
?>

<div class="md-form--select md-form">
    <?php
    $unitArray = array('0' => $this->lang->line("assignment_select_unit"));
    if ($units != "empty") {
        foreach ($units as $unit) {
            $unitArray[$unit->id] = $unit->unit_name;
        }
    }
    $check_unit = isset($_GET['unit']) ? $_GET['unit'] : set_value("unitId");
    echo form_dropdown("unitId", $unitArray, $check_unit, "id='unitId' class='mdb-select'");
    ?>
    <label for="unitId" class="mdb-main-label">
        <?= $this->lang->line("assignment_unit") ?>
    </label>
    <span class="text-danger error">
        <?php echo form_error('unitId'); ?>
    </span>
</div>
</div>

<?php
if (form_error('chapterId'))
    echo "<div class='form-group has-error' >";
else
    echo "<div class='form-group' >";
?>

<div class="md-form--select md-form">
    <?php if (isset($_GET['unit'])) {
        $chapterArray = array('0' => $this->lang->line("assignment_select_chapter"));
        foreach ($chapters as $chapter) {
            $chapterarray[$chapter->id] = $chapter->chapter_name;
        }

        $check_chapter = isset($_GET['chapter']) ? $_GET['chapter'] : set_value("chapterId");
        echo form_dropdown("chapterId", $chapterarray, $check_chapter, "id='chapterId' class='mdb-select'");
    } else {
        $chapterArray = array('0' => $this->lang->line("assignment_select_chapter"));
        echo form_dropdown("chapterId", $chapterArray, set_value("chapterId"), "id='chapterId' class='mdb-select'");
    } ?>
    <label for="chapter" class="mdb-main-label">
        <?= $this->lang->line("assignment_chapter") ?>
    </label>
    <span class="text-danger error">
        <?php echo form_error('chapterId'); ?>
    </span>
</div>
</div>

<?php
if (form_error('photos[]'))
    echo "<div class='form-group has-error' >";
else
    echo "<div class='form-group' >";
?>
<span class="text-danger error">
    <?php echo form_error('photos[]'); ?>
</span>
</div>
<div class="dvFile" id="dvFile">
    <div class="increment-block mb-0 row" id="director-uploads" data-number="0" data-changed="0">
        <div class="col-sm-4">
            <div class="md-form md-form--file">
                <div class="file-field">
                    <div class="btn btn-success btn-sm float-left">
                        <span>Choose file</span>
                        <input type="file" name="photos[]" />
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate form-control" type="text" name="attachment1[]" placeholder="Upload your file" readonly />
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-3 ">
            <div class="md-form">
                <input type="text" name="caption[]" class="form-control" data-item="0" id="file-name0" placeholder="Caption">
            </div>
        </div>


        <div class="col-lg-2 align-self-lg-center">
            <button type="button" class="btn btn-success" id="add-director"><i class='fa fa-plus'></i> </button>
        </div>
    </div>
</div>

<input type="submit" class="btn btn-success" value="<?= $this->lang->line("add_assignment") ?>">
<a href="<?= $this->agent->referrer(); ?>" class="btn btn-default">Cancel</a>
</form>
</div>
</div>
</div>
</div>
</div>

<script language="javascript">

    $(document).ready(function() {


        var i = 1;

        function clone() {
            var html = "<div class=\"increment-block mb-0 row\" data-changed=\"0\"><div class=\"col-sm-4\"><div class=\"md-form md-form--file\"><div class=\"file-field\"><div class=\"btn btn-success btn-sm float-left\"><span>Choose file</span><input type=\"file\" name=\"photos[]\"  /></div><div class=\"file-path-wrapper\"><input class=\"file-path validate form-control\" type=\"text\" name=\"attachment1[]\" placeholder=\"Upload your file\" readonly/></div></div></div></div><div class=\"col-sm-3\"><div class=\"md-form\"><input type=\"text\" name=\"caption[]\" id=\"file-name" + i + "\" class=\"form-control\" data-item=\"0\"  placeholder=\"Caption\" > <span class=\"text-danger error\"><p id=\"file-name-error" + i + "\"></p></span></div></div><div class=\"col-lg-2 align-self-lg-center\"><button type=\"button\" class=\"btn btn-danger\" onclick=\"remove_input(this)\" title=\"Remove\" id=\"remove-clone\"><i class='fa fa-minus'></i></button></div></div>";
            i++;
            $('#dvFile').append(html).find("*").each(function() {
                var name = $(this).attr('name');

            }).on('click', 'button.clone', clone);
        }

        $("button#add-director").on("click", clone);

        $("html").on('change', '.app-file', function() {
            var number = $(this).attr('data-item');
        });

    });

    function remove_input(e) {
        var thisId = e.closest('.increment-block').remove();
        console.log(thisId);
    }
</script>

<script>
    $(".select2").select2();

    $("#deadlinedate").datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate: '<?= $schoolyearobj->startingdate ?>',
        endDate: '<?= $schoolyearobj->endingdate ?>',
    });

    $('#classesID').change(function(event) {
        var classesID = $(this).val();
        if (classesID === '0') {
            $('#subjectID').val(0);
            $('#sectionID').val('');
        } else {
            $('#sectionID').val('');
            $.ajax({
                type: 'POST',
                url: "<?= base_url('assignment/subjectcall') ?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    $('#subjectID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?= base_url('assignment/sectioncall') ?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    $('#sectionID').html(data);
                }
            });
        }
    });

    $('#unitId').change(function(event) {
        var unitId = $(this).val();
        if (unitId === '0') {
            $('#unitId').val(0);
        } else {
            $.ajax({
                type: 'GET',
                url: "<?= base_url('assignment/getChapters/') ?>" + unitId,
                dataType: "html",
                success: function(data) {
                    $('#chapterId').html('');
                    $('#chapterId').html(data);
                    $('.mdb-select').material_select('destroy');
                    $('.mdb-select').material_select();
                }
            });
        }
    });

    $(document).on('click', '#close-preview', function() {
        $('.image-preview').popover('hide');
        // Hover befor close the preview
        $('.image-preview').hover(
            function() {
                $('.image-preview').popover('show');
                $('.content').css('padding-bottom', '100px');
            },
            function() {
                $('.image-preview').popover('hide');
                $('.content').css('padding-bottom', '20px');
            }
        );
    });

    $(function() {
        // Create the close button
        var closebtn = $('<button/>', {
            type: "button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class", "close pull-right");
        // Set the popover default content
        $('.image-preview').popover({
            trigger: 'manual',
            html: true,
            title: "<strong>Preview</strong>" + $(closebtn)[0].outerHTML,
            content: "There's no image",
            placement: 'bottom'
        });
        // Clear event
        $('.image-preview-clear').click(function() {
            $('.image-preview').attr("data-content", "").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("<?= $this->lang->line('assignment_file_browse') ?>");
        });
        // Create the preview image
        $(".image-preview-input input:file").change(function() {
            var img = $('<img/>', {
                id: 'dynamic',
                width: 250,
                height: 200,
                overflow: 'hidden'
            });
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function(e) {
                $(".image-preview-input-title").text("<?= $this->lang->line('assignment_file_browse') ?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
            }
            reader.readAsDataURL(file);
        });
    });

    $('#add_assignment').submit(function() {
        $("#add_assignment :disabled").removeAttr('disabled');
    });
</script>