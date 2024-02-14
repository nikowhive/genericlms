<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<div class="right-side--fullHeight  ">

    <div class="row w-100 ">
        <?php $this->load->view("components/course_menu"); ?>

        <div class="course-content">
            <div class="container container--sm">
                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                        <div><small>Course</small></div>
                        <?= $this->lang->line('panel_title') ?>
                    </h1>
                </header>
                <div class="card card--spaced">
                    <!-- <div class="box-header">
                <h3 class="box-title"><i class="fa icon-classwork"></i> <?= $this->lang->line('panel_title') ?></h3>
            </div> -->
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="card-body">

                        <form class="" enctype="multipart/form-data" role="form" method="post">
                            <?php
                            if (form_error('title'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                            ?>

                            <div class="md-form">
                                <label for="title">
                                    <?= $this->lang->line("classwork_title") ?> <span class="text-red">*</span>
                                </label>

                                <input type="text" class="form-control" id="title" name="title" value="<?= set_value('title', $classwork->title) ?>">

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
                    <label for="description" class="mdb-main-label">
                        <?= $this->lang->line("classwork_description") ?> <span class="text-red">*</span>
                    </label>
                    <div class="md-form">
                        <textarea class="md-textarea form-control" style="resize:none;" id="description" name="description" rows="4"><?= set_value('description', $classwork->description) ?></textarea>

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
                        <?= $this->lang->line("classwork_deadlinedate") ?> <span class="text-red">*</span>
                    </label>

                    <input type="text" class="form-control" id="deadlinedate" name="deadlinedate" value="<?= set_value('deadlinedate', date('d-m-Y', strtotime($classwork->deadlinedate))) ?>">

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



            <div class="md-form md-form--select">
                <?php
                $array = array();
                $array[0] = $this->lang->line("classwork_select_classes");
                foreach ($classes as $classa) {
                    $array[$classa->classesID] = $classa->classes;
                }
                echo form_dropdown("classesID", $array, set_value("classesID", $classwork->classesID), "id='classesID' class='mdb-select'");
                ?>
                <label for="classesID" class="mdb-main-label">
                    <?= $this->lang->line("classwork_classes") ?> <span class="text-red">*</span>
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



        <div class="md-form md-form--select">
            <?php
            $array = array();
            if ($sections != "empty") {
                foreach ($sections as $section) {
                    $array[$section->sectionID] = $section->section;
                }
            }

            echo form_multiselect("sectionID[]", $array, set_value("sectionID", $sectionID), "id='sectionID' class='mdb-select'");
            ?>
            <label for="sectionID" class="mdb-main-label">
                <?= $this->lang->line("classwork_section") ?>
            </label>

            <span class="text-danger error">
                <?php echo form_error('sectionID'); ?>
            </span>
        </div>

    </div>

    <?php
    if (form_error('subjectID'))
        echo "<div class='form-group has-error' >";
    else
        echo "<div class='form-group' >";
    ?>


    <div class="md-form md-form--select">

        <?php
        $array = array('0' => $this->lang->line("classwork_select_subject"));
        if ($subjects != "empty") {
            foreach ($subjects as $subject) {
                $array[$subject->subjectID] = $subject->subject;
            }
        }

        echo form_dropdown("subjectID", $array, set_value("subjectID", $classwork->subjectID), "id='subjectID' class='mdb-select' disabled");
        ?>
        <label for="subjectID" class="mdb-main-label">
            <?= $this->lang->line("classwork_subject") ?>
        </label>
        <span class="text-danger error">
            <?php echo form_error('subjectID'); ?>
        </span>
        <input type="hidden" name="subjectID" value="<?= $classwork->subjectID ?>">
    </div>




</div>

<?php
if (form_error('unitId'))
    echo "<div class='form-group has-error' >";
else
    echo "<div class='form-group' >";
?>



<div class="md-form md-form--select">
    <?php
    $unitArray = array('0' => $this->lang->line("classwork_select_unit"));
    if ($units != "empty") {
        foreach ($units as $unit) {
            $unitArray[$unit->id] = $unit->unit_name;
        }
    }
    echo form_dropdown("unitId", $unitArray, set_value("unitId", $classwork->unit_id), "id='unitId' class='mdb-select'");
    ?>
    <label for="unitId" class="mdb-main-label">
        <?= $this->lang->line("classwork_unit") ?>
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


<div class="md-form md-form--select">

    <?php
    $chapterArray = array('0' => $this->lang->line("classwork_select_chapter"));
    if ($chapters != "empty") {
        foreach ($chapters as $chapter) {
            $chapterArray[$chapter->id] = $chapter->chapter_name;
        }
    }
    echo form_dropdown(
        "chapterId",
        $chapterArray,
        set_value("chapterId", $classwork->chapter_id),
        "id='chapterId' class='mdb-select'"
    );
    ?>
    <label for="chapterId" class="mdb-main-label">
        <?= $this->lang->line("classwork_chapter") ?>
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

<span class="col-sm-8">
    <?php echo form_error('photos[]'); ?>
</span>
</div>

<div class="dvFile" id="dvFile">
    <div class="increment-block mb-0 row" id="director-uploads" data-number="0" data-changed="0">
        <div class="col-lg-2 align-self-lg-center">
            <button type="button" class="btn btn-success" id="add-director"><i class='fa fa-plus'></i> </button>
        </div>
    </div>
</div>
<br>

<div class="content">

    <div class="row">
        <?php foreach ($classwork_medias as $v) : ?>
            <div class="col-sm-6" id="pip-<?= $v->id; ?>" style="margin-bottom: 1em">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="">
                            <div class="panned-icon">
                                <i class='fa <?= checkFileExtension($v->attachment) ?>' aria-hidden='true'></i>
                            </div>
                            <p class="card-text"><small><?= substr($v->caption, 0, 20) ?>.<?= pathinfo($v->attachment, PATHINFO_EXTENSION); ?></small></p>
                        </div>
                        <a class="btn btn-danger" onclick="deleteImage(<?= $v->id; ?>)">Remove</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<br>



<input type="submit" class="btn btn-success" value="<?= $this->lang->line("update_classwork") ?>">
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
        startDate: '<?= $schoolyearsessionobj->startingdate ?>',
        endDate: '<?= $schoolyearsessionobj->endingdate ?>',
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
                url: "<?= base_url('classwork/subjectcall') ?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    $('#subjectID').html(data);
                    $('.mdb-select').material_select('destroy');
                    $('.mdb-select').material_select();
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?= base_url('classwork/sectioncall') ?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    $('#sectionID').html(data);
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
            $(".image-preview-input-title").text("<?= $this->lang->line('classwork_file_browse') ?>");
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
                $(".image-preview-input-title").text("<?= $this->lang->line('classwork_file_browse') ?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
            }
            reader.readAsDataURL(file);
        });
    });

    function deleteImage(id) {
        var result = confirm("Are you sure to delete?");
        if (result) {
            $.post("<?php echo base_url('classwork/deleteImage'); ?>", {
                id: id
            }, function(data) {
                var response = jQuery.parseJSON(data);
                if (response.status) {

                    console.log(response);
                    $('#pip-' + id).remove();
                    toastr["success"](response.message)
                }
            });
        }
    }
</script>