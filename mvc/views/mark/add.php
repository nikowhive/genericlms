<?php if ($siteinfos->note==1) { ?>
    <div class="callout callout-danger">
        <p><b>Note:</b> Create exam, class, section & subject before add mark</p>
    </div>
<?php } ?>

<header class="pg-header mt-4">
        <div>
            
            <h1 class="pg-title">
            
                <?=$this->lang->line('panel_title')?>
                </h1>
                <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("mark/index")?>"><?=$this->lang->line('menu_mark')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_mark')?></li>
        </ol>
        </div>
</header>
<div class="card card--spaced">
    <!-- <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("mark/index")?>"><?=$this->lang->line('menu_mark')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_mark')?></li>
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
                                    <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        
                                        <div class="md-form md-form--select">
                                            <?php
                                                $array = array("0" => $this->lang->line("mark_select_classes"));
                                                foreach ($classes as $classa) {
                                                    $array[$classa->classesID] = $classa->classes;
                                                }
                                                echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='mdb-select classesID'");
                                            ?>
                                            <label for="classesID" class="mdb-main-label">
                                                <?=$this->lang->line('mark_classes')?>  <span class="text-red"> *</span>

                                            </label>
                                            <span class="text-red"> <?php echo form_error('classesID'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="<?php echo form_error('examID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <div class="md-form md-form--select">
                                            
                                            <?php
                                                $array = array("0" => $this->lang->line("mark_select_exam"));
                                                foreach ($exams as $exam) {
                                                    $array[$exam->examID] = $exam->exam;
                                                }
                                                echo form_dropdown("examID", $array, set_value("examID"), "id='examID' class='mdb-select examID'");
                                            ?>
                                            <label for="examID" class="mdb-main-label">
                                                <?=$this->lang->line('mark_exam')?> <span class="text-red">*</span>
                                            </label>
                                            <span class="text-red"> <?php echo form_error('examID'); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        
                                        <div class="md-form md-form--select">
                                            <?php
                                                $arraysection = array('0' => $this->lang->line("mark_select_section"));
                                                if(customCompute($sections)) {
                                                    foreach ($sections as $section) {
                                                        $arraysection[$section->sectionID] = $section->section;
                                                    }
                                                }
                                                echo form_dropdown("sectionID", $arraysection, set_value("sectionID"), "id='sectionID' class='mdb-select'");
                                            ?>
                                            <label class="mdb-main-label"><?=$this->lang->line('mark_section')?> <span class="text-red">*</span></label>
                                            <span class="text-red"> <?php echo form_error('sectionID'); ?></span>
                                        </div>
                                       
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="<?php echo form_error('subjectID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        
                                        <div class="md-form md-form--select">
                                            <?php
                                                $subjectArray = array("0" => $this->lang->line("mark_select_subject"));
                                                if(customCompute($subjects)) {
                                                    foreach ($subjects as $subject) {
                                                        $subjectArray[$subject->subjectID] = $subject->subject;
                                                    }
                                                }
                                                echo form_dropdown("subjectID", $subjectArray, set_value("subjectID"), "id='subjectID' class='mdb-select'");
                                            ?>
                                            <label for="subjectID" class="mdb-main-label">
                                                <?=$this->lang->line('mark_subject')?> <span class="text-red">*</span>
                                            </label>
                                            <span class="text-red"> <?php echo form_error('subjectID'); ?></span>
                                        </div>  
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="<?php echo form_error('excel') ? 'form-group has-error' : 'form-group'; ?>" >

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
                                                    <span class="text-red"> <?php echo form_error('excel'); ?></span>
                                                </div>
                                       
                                               
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="list-inline">
                                            <button type="submit" name="add_mark" class="btn btn-success"><?=$this->lang->line('add_mark')?></button>
                                            <button type="submit" name="download_template" class="btn btn-default">Download Template</button>
                                            <button type="submit" name="bulk_import" class="btn btn-default">Import Marks</button>
                            </div>
                                            
                     
 
                </form>


                <?php if( customCompute($sendClasses) && customCompute($sendSection) && customCompute($sendSubject)) { ?>
                    <div class="mt-3 mb-3 border card-body">
                    <h3 class="mt-0"> <?php echo $this->lang->line('mark_details');?></h3>
                    <div class="row">
                            <div class="col-md-3">
                                <small> <?php echo $this->lang->line('mark_exam');?></small>
                                <h4> <b><?php echo $sendExam->exam ?></b> </h4>
                            </div>
                            <div class="col-md-3">
                                <small> <?php echo $this->lang->line('mark_classes');?></small>
                                <h4> <b><?php echo $sendClasses->classes ?></b> </h4>
                            </div>
                            <div class="col-md-3">
                                <small> <?php echo $this->lang->line('mark_section');?></small>
                                <h4> <b><?php echo $sendSection->section ?></b> </h4>
                            </div>
                            <div class="col-md-3">
                                <small> <?php echo $this->lang->line('mark_subject');?></small>
                                <h4> <b><?php echo $sendSubject->subject ?></b> </h4>
                            </div>
                    </div>
                        
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-12">
                <?php if(customCompute($students)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th><?=$this->lang->line('slno')?></th>
                                    <th><?=$this->lang->line('mark_photo')?></th>
                                    <th><?=$this->lang->line('mark_name')?></th>
                                    <th><?=$this->lang->line('mark_roll')?></th>
                                    <?php
                                        foreach ($markpercentages as $data) {
                                            if($data->markpercentagetype == 'Theory' || $data->markpercentagetype == 'theory') {
                                                echo "<th>$data->markpercentagetype ($data->percentage)</th>";
                                            } else {
                                                if($selected_subject->finalmark == 50 && $sendExam->examID != 9 && $sendExam->examID != 10) {
                                                    echo "<th>$data->markpercentagetype (5)</th>";
                                                } else {
                                                    echo "<th>$data->markpercentagetype ($data->percentage)</th>";
                                                }
                                            }
                                        }
                                    ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(customCompute($students)) {$i = 1; foreach($students as $student) { foreach ($marks as $mark) { if($student->studentID == $mark->studentID) {   ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('slno')?>">
                                            <?php echo $i; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('mark_photo')?>">
                                            <?=profileproimage($student->photo)?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('mark_name')?>">
                                            <?php echo $student->name; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('mark_roll')?>">
                                            <?php echo $student->roll; ?>
                                        </td>
                                        <?php
                                            foreach ($markpercentages as $data) {
                                                if($data->markpercentagetype == 'theory' || $data->markpercentagetype == 'Theory') {
                                                    echo "<td data-title='$data->markpercentagetype'>";
                                                        echo "<input class='form-control mark' type='number' name='mark-".$markwr[$student->studentID][$data->markpercentageID]."' id='".$data->markpercentageID."' value='".$markRelations[$student->studentID][$data->markpercentageID]."' min='0' max='".$data->percentage."' />";
                                                    echo "</td>";
                                                } else {
                                                    if($selected_subject->finalmark == 50 && $sendExam->examID != 9 && $sendExam->examID != 10) {
                                                        $total = 5;
                                                        echo "<td data-title='$data->markpercentagetype'>";
                                                            echo "<input class='form-control mark' type='number' name='mark-".$markwr[$student->studentID][$data->markpercentageID]."' id='".$data->markpercentageID."' value='".$markRelations[$student->studentID][$data->markpercentageID]."' min='0' max='".$total."' />";
                                                        echo "</td>";
                                                    } else {
                                                        echo "<td data-title='$data->markpercentagetype'>";
                                                            echo "<input class='form-control mark' type='number' name='mark-".$markwr[$student->studentID][$data->markpercentageID]."' id='".$data->markpercentageID."' value='".$markRelations[$student->studentID][$data->markpercentageID]."' min='0' max='".$data->percentage."' />";
                                                        echo "</td>";
                                                    }
                                                }
                                            }
                                        ?>

                                    </tr>
                                <?php $i++;  }}}} ?>
                            </tbody>
                        </table>
                    </div>
                    <input type="button" class="btn btn-success" id="add_mark" name="add_mark" value="<?=$this->lang->line("add_sub_mark")?>" />

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
                            if(parseInt($(this).val())) {
                                var val = parseInt($(this).val());
                                var minMark = parseInt($(this).attr('min'));
                                var maxMark = parseInt($(this).attr('max'));
                                if(minMark > val || val > maxMark) {
                                    $(this).val('');
                                }
                            } else {
                                if($(this).val() == '0') {
                                } else {
                                    $(this).val('');
                                }
                            }
                        });

                        $("#add_mark").click(function() {
                            var inputs = "";
                            var inputs_value = "";
                            var mark = $('input[name^=mark]').map(function(){
                                return { markpercentageid:this.id, mark: this.name , value: this.value};
                            }).get();
                            console.log(mark);
                            $.ajax({
                                type: 'POST',
                                url: "<?=base_url('mark/mark_send')?>",
                                data: {"examID" : "<?=$set_exam?>", "classesID" : "<?=$set_classes?>", "subjectID" : "<?=$set_subject?>","sectionID" : "<?=$set_section?>", "inputs" : mark},
                                dataType: "html",
                                success: function(data) {
                                    var response = jQuery.parseJSON(data);
                                    if(response.status) {
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
                                        if(response.error) {
                                            toastr["error"](response.error)
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
        $(this).siblings($('input[id="'+$(this).attr('mid')+'"]')).val($(this).val());
    })

    $('.select2').select2();
    $("#classesID").change(function() {
        var classesID = $(this).val();
        if(parseInt(classesID)) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('mark/examcall')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                    $('#examID').html(data);
                    $('.mdb-select').material_select('destroy');
                    $('.mdb-select').material_select();
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?=base_url('mark/subjectcall')?>",
                data: {"id" : classesID},
                dataType: "html",
                success: function(data) {
                    $('#subjectID').html(data);
                    $('.mdb-select').material_select('destroy');
                    $('.mdb-select').material_select();
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?=base_url('mark/sectioncall')?>",
                data: {"id" : classesID},
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