<div class="right-side--fullHeight  ">
    <div class="row w-100 ">
        <?php $this->load->view("components/course_menu"); ?>
        <div class="course-content">
            <div class="container container--sm">

                <header class="pg-header mt-4">
                    <h1 class="pg-title">
                        <div><small>Course</small></div>
                        Edit Daily Plan
                    </h1>
                </header>
                <div class="card card--spaced">

                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">

                                <form name="frm" method="post" enctype="multipart/form-data" id="myform">
                                    <?php
                                    if (form_error('title'))
                                        echo "<div class='form-group has-error' >";
                                    else
                                        echo "<div class='form-group' >";
                                    ?>
                                    <div class='form-group'>
                                        <div class="md-form">
                                            <label for="title">
                                                <?= $this->lang->line("daily_title") ?> <span class="text-red">*</span>
                                            </label>
                                            <input type="text" placeholder="Enter daily title here..." class="form-control" id="title" name="title" value="<?= set_value('title', isset($daily->title) ? $daily->title : '') ?>">
                                        </div>
                                    </div>
                            </div>


                            <?php
                            if (form_error('daily_date'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                            ?>
                            <div class="md-form">
                                <label for="daily_date">
                                    <?= $this->lang->line("daily_date") ?> <span class="text-red">*</span>
                                </label>
                                <input type="text" autocomplete="off" class="form-control" id="daily_date" name="daily_date" value="<?= set_value('daily_date', date('d-m-Y', strtotime($daily->create_date))) ?>">
                                <span class="text-danger error">
                                    <?php echo form_error('daily_date'); ?>
                                </span>
                            </div>
                        </div>

                        <?php
                        if (form_error('activities'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                        ?>
                        <label for="activities">
                            <?= $this->lang->line("activities") ?> <span class="text-red">*</span>
                        </label>
                        <div class="md-form">
                            <textarea class="md-textarea form-control" id="activities" name="activities" rows="4"><?= set_value('activities', $daily->activities) ?></textarea>
                            <span class="text-danger error">
                                <?php echo form_error('activities'); ?>
                            </span>
                        </div>
                    </div>

                    <?php
                    if (form_error('assignment'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                    <label for="assignment">
                        <?= $this->lang->line("assignment") ?> <span class="text-red">*</span>
                    </label>
                    <div class="md-form">
                        <textarea class="md-textarea form-control" id="assignment" name="assignment" rows="4"><?= set_value('assignment', $daily->assignments) ?></textarea>
                        <!-- <input type="text" placeholder="Enter assignment here..." class="form-control" id="assignment" name="assignment" value="<?= set_value('assignment', $daily->assignments) ?>"> -->
                        <span class="text-danger error">
                            <?php echo form_error('assignment'); ?>
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <?php
                        if (form_error('select_absent_student'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                        ?>
                        <div class="md-form--select md-form">
                            <?php
                            $array = array();
                            $array[0] = $this->lang->line("select_absent_student");
                            for ($i = 1; $i <= $students; $i++) {
                                $array[$i] = $i;
                            }

                            echo form_dropdown("absent_student_count", $array, set_value("absent_student_count", $daily->absent_student_count), "id='absent_student_count' class='mdb-select'");
                            ?>
                            <label for="absent_student_count" class="mdb-main-label">
                                <?= $this->lang->line("select_absent_student") ?> <span class="text-red">*</span>
                            </label>
                            <span class="text-danger error">
                                <?php echo form_error('select_absent_student'); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="md-form">
                        <label for="present_student">
                            <?= $this->lang->line("present_student") ?>
                        </label>
                        <!-- <input type="text" placeholder="Enter daily present_student here..." class="form-control" id="present_student"  readonly name="present_student" value="<?= isset($students)? (int)$students - (int)$daily->absent_student_count:''; ?>"> -->
                        <input type="text" placeholder="Enter daily present_student here..." class="form-control" id="present_student"  readonly name="present_student" value="<?= set_value("absent_student_count", $daily->present_student_count); ?>">
                        
                    </div>
                </div>

            </div>

            <?php
            if (form_error('absent_students'))
                echo "<div class='form-group has-error' >";
            else
                echo "<div class='form-group' >";
            ?>
            <label for="absent_students">
                <?= $this->lang->line("absent_students") ?> 
            </label>
            <div class="md-form">
                <textarea class="md-textarea form-control" id="absent_students" name="absent_students" rows="4"><?= set_value('absent_students',$daily->absent_students) ?></textarea>
                <!-- <input type="text" placeholder="Enter absent_students here..." class="form-control" id="absent_students" name="absent_students" value="<?= set_value('absent_students') ?>"> -->
                <span class="text-danger error">
                    <?php echo form_error('absent_students'); ?>
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
                $array[0] = $this->lang->line("daily_select_classes");

                foreach ($classes as $classa) {
                    $array[$classa->classesID] = $classa->classes;
                }


                echo form_dropdown("classesID", $array, set_value("classesID", $daily->classesID), "id='classesID' class='mdb-select' disabled");
                ?>
                <label for="classesID" class="mdb-main-label">
                    <?= $this->lang->line("daily_select_classes") ?> <span class="text-red">*</span>
                </label>
                <span class="text-danger error">
                    <?php echo form_error('classesID'); ?>
                </span>
            </div>
        </div>

        <?php
        if (form_error('subjectID'))
            echo "<div class='form-group has-error' >";
        else
            echo "<div class='form-group' >";
        ?>

        <div class="md-form--select md-form">
            <?php
            $array = array('0' => $this->lang->line("daily_select_subject"));
            if ($subjects != "empty") {
                foreach ($subjects as $subject) {
                    $array[$subject->subjectID] = $subject->subject;
                }
            }

            $disabled = ($subjectID != 0) ? 'disabled' : '';

            echo form_dropdown("subjectID", $array, set_value("subjectID", $subjectID), "id='subjectID' class='mdb-select' $disabled");
            ?>
            <label for="subjectID" class="mdb-main-label">
                <?= $this->lang->line("daily_select_subject") ?> <span class="text-red">*</span>
            </label>
            <span class="text-danger error">
                <?php echo form_error('subjectID'); ?>
            </span>
        </div>
    </div>

    <?php
    if (form_error('lesson_id'))
        echo "<div class='form-group has-error' >";
    else
        echo "<div class='form-group' >";
    ?>
    <div class="md-form--select md-form">
        <?php
        $array = array('0' => $this->lang->line("daily_select_lesson"));
        if ($lessons != "empty") {
            foreach ($lessons as $lesson) {
                $array[$lesson->id] = $lesson->title;
            }
        }
        echo form_dropdown("lesson_id", $array, set_value("lesson_id", $daily->lesson_id), "id='lesson_id' class='mdb-select' ");

        ?>
        <label for="lesson_id" class="mdb-main-label">
            <?= $this->lang->line("daily_select_lesson") ?> <span class="text-red">*</span>
        </label>
        <span class="text-danger error">
            <?php echo form_error('lesson_id'); ?>
        </span>
    </div>
</div>



<?php
if (form_error('feedback'))
    echo "<div class='form-group has-error' >";
else
    echo "<div class='form-group' >";
?>
<label for="feedback">
    <?= $this->lang->line("feedback") ?> 
</label>
<div class="md-form">
    <textarea class="md-textarea form-control" id="feedback" name="feedback" rows="4"><?= set_value('feedback', $daily->feedback) ?></textarea>
    <span class="text-danger error">
        <?php echo form_error('feedback'); ?>
    </span>
</div>
</div>

<?php
if (form_error('remark'))
    echo "<div class='form-group has-error' >";
else
    echo "<div class='form-group' >";
?>
<label for="remark">
    <?= $this->lang->line("remark") ?> 
</label>
<div class="md-form">
    <textarea class="md-textarea form-control" id="remark" name="remark" rows="4"><?= set_value('remark', $daily->remarks) ?></textarea>
    <span class="text-danger error">
        <?php echo form_error('remark'); ?>
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
    $unitArray = array('0' => $this->lang->line("daily_select_unit"));
    if ($units != "empty") {
        foreach ($units as $unit) {
            $unitArray[$unit->id] = $unit->unit_name;
        }
    }
    echo form_dropdown("unitId", $unitArray, set_value("unitId", $daily->unit_id), "id='unitId' class='mdb-select'");
    ?>
    <label for="unitId" class="mdb-main-label">
        <?= $this->lang->line("daily_unit") ?>
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
    <?php
    $chapterArray = array('0' => $this->lang->line("daily_select_chapter"));
    if ($chapters != "empty") {
        foreach ($chapters as $chapter) {
            $chapterArray[$chapter->id] = $chapter->chapter_name;
        }
    }
    echo form_dropdown(
        "chapterId",
        $chapterArray,
        set_value("chapterId", $daily->chapter_id),
        "id='chapterId' class='mdb-select'"
    );
    ?>
    <label for="chapterId" class="mdb-main-label">
        <?= $this->lang->line("daily_chapter") ?>
    </label>
    <span class="text-danger error">
        <?php echo form_error('chapterId'); ?>
    </span>
</div>
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
        <?php foreach ($versions as $i => $k) :
            foreach ($k->media as $i1 => $k1) :
        ?>

                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="">
                                <!-- <div class="panned-icon">⋮⋮</div> -->
                                <div class="panned-icon">
                                    <i class="fa <?= checkFileExtension($k1->file) ?>" aria-hidden="true"></i>
                                </div>
                                <h5 class="card-title"><?= $k1->caption ?></h5>
                            </div>
                            <!-- <img src="..." class="card-img-top" alt="..."> -->
                        </div>
                    </div>
                </div>

        <?php endforeach;
        endforeach;
        ?>
    </div>
</div>
<br>


<input type="submit" value="Upload File" class="btn btn-success">
<a href="<?= $this->agent->referrer(); ?>" class="btn btn-default">Cancel</a>

</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>


<script language="javascript">
   
    tinymce.init({
        selector: '#remark, #feedback, #activities,  #assignment, #absent_students',
        width: 600,
        height: 300,
        plugins: [
        'advlist autolink link image lists charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
        'table emoticons template powerpaste help tiny_mce_wiris '
        ],
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | link image | print preview media fullpage | ' +
        'forecolor backcolor emoticons | help tiny_mce_wiris_formulaEditor | tiny_mce_wiris_formulaEditorChemistry',
        powerpaste_allow_local_images: true,
        powerpaste_word_import: 'prompt',
        powerpaste_html_import: 'prompt',
        menu: {
        favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons'}
        },
        automatic_uploads: true,
        relative_urls: false,
        remove_script_host: false,
          /*
            URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
            images_upload_url: 'postAcceptor.php',
            here we add custom filepicker only to Image dialog
          */
          file_picker_types: 'image',
          /* and here's our custom image picker*/
          file_picker_callback: function (cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            /*
              Note: In modern browsers input[type="file"] is functional without
              even adding it to the DOM, but that might not be the case in some older
              or quirky browsers like IE, so you might want to add it to the DOM
              just in case, and visually hide it. And do not forget do remove it
              once you do not need it anymore.
            */

            input.onchange = function () {
              var file = this.files[0];

              var reader = new FileReader();
              reader.onload = function () {
                /*
                  Note: Now we need to register the blob in TinyMCEs image blob
                  registry. In the next release this part hopefully won't be
                  necessary, as we are looking to handle it internally.
                */
                var id = 'blobid' + (new Date()).getTime();
                var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(',')[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                /* call the callback and populate the Title field with the file name */
                cb(blobInfo.blobUri(), { title: file.name });
              };
              reader.readAsDataURL(file);
            };

            input.click();
          },
        menubar: 'favs file edit view insert format tools table help',
        content_css: 'css/content.css'
    });


    document.getElementById("absent_student_count").onchange = function() {
        var total = <?= $students; ?>;
        document.getElementById("present_student").value = total - this.value;
    };

    $("#daily_date").datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate: '<?= $schoolyearobj->startingdate ?>',
        endDate: '<?= $schoolyearobj->endingdate ?>',
    });

    $(document).ready(function() {
        var i = 1;

        function clone() {
            var html = "<div class=\"increment-block mb-0 row\" data-changed=\"0\"><div class=\"col-lg-6\"><div class=\"md-form md-form--file\"><div class=\"file-field\"><div class=\"btn btn-success btn-sm float-left\"><span>Choose file</span><input type=\"file\" name=\"upload_Files[]\"  /></div><div class=\"file-path-wrapper\"><input class=\"file-path validate form-control\" type=\"text\" name=\"file_name1[]\" placeholder=\"Upload your file\" readonly/></div></div></div></div><div class=\"namepanel col-lg-4\"><div class=\"md-form\"><input type=\"text\" name=\"caption[]\" id=\"file-name" + i + "\" class=\"form-control\" data-item=\"0\"  placeholder=\"Caption\" > <span class=\"text-danger error\"><p id=\"file-name-error" + i + "\"></p></span></div></div><div class=\"col-lg-2 align-self-lg-center\"><button type=\"button\" class=\"btn btn-danger\" onclick=\"remove_input(this)\" title=\"Remove\" id=\"remove-clone\"><i class='fa fa-minus'></i></button></div></div>";
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

    function validate(f) {
        var chkFlg = false;
        var txt = document.getElementsByName('file_name');
        for (var i = 0; i < f.length; i++) {
            if (f.elements[i].type == "file" && f.elements[i].value != "") {
                chkFlg = true;
            }
        }
        if (!chkFlg) {
            alert('Please browse/choose at least one file');
            return false;
        }
        f.pgaction.value = 'upload';
        return true;
    }

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
</script>
<style type="text/css">
    .md-form--file .file-field input[type="file"] {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 0;
        margin: 0;
        cursor: pointer;
        filter: alpha(opacity=0);
        opacity: 0;
    }

    .md-form--file .file-field .btn * {
        cursor: pointer;
    }

    .md-form input,
    .md-form textarea {
        box-sizing: border-box !important;
    }

    input[type="file"] {
        display: block;
    }

    input,
    button,
    select,
    textarea {
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
    }
</style>