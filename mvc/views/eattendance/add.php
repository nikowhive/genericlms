
<div class="container container--sm">
<header class="pg-header mt-4">
        <div>
            
            <h1 class="pg-title">
            
                <?=$this->lang->line('panel_title')?>
                </h1>
                <ol class="breadcrumb">
                <li><a href="<?=base_url("dashboard/index")?>"> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li><a href="<?=base_url("eattendance/index")?>"><?=$this->lang->line('menu_eattendance')?></a></li>
                <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_eattendance')?></li>
            </ol>
        </div>
</header>
    <div class="card card--spaced">
  
        <!-- form start -->
        <div class="card-body">
          
    
                    <form method="POST">
                      
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="<?php echo form_error('examID') ? 'form-group has-error' : 'form-group'; ?>">
                                            <div class="md-form md-form--select">
                                               
                                                <?php
                                                    $examArray = array("0" => $this->lang->line("eattendance_select_exam"));
                                                    foreach ($exams as $exam) {
                                                        $examArray[$exam->examID] = $exam->exam;
                                                    }
                                                    echo form_dropdown("examID", $examArray, set_value("examID"), "id='examID' class='mdb-select'");
                                                ?>
                                                 <label for="examID" class="mdb-main-label">
                                                    <?=$this->lang->line('eattendance_exam')?> <span class="text-red">*</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="col-md-6">
                                        <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>" >
                                          
                                            <div class="md-form md-form--select">
                                                <?php
                                                    $classArray = array("0" => $this->lang->line("eattendance_select_classes"));
                                                    foreach ($classes as $classa) {
                                                        $classArray[$classa->classesID] = $classa->classes;
                                                    }
                                                    echo form_dropdown("classesID", $classArray, set_value("classesID"), "id='classesID' class='mdb-select'");
                                                ?>
                                                  <label for="classesID" class="mdb-main-label">
                                                    <?=$this->lang->line('eattendance_classes')?> <span class="text-red">*</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="col-md-6">
                                        <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            
                                            <div class="md-form md-form--select">
                                                <?php
                                                    $sectionArray = array('0' => $this->lang->line("eattendance_select_section"));
                                                    if(customCompute($sections)) {
                                                        foreach ($sections as $section) {
                                                            $sectionArray[$section->sectionID] = $section->section;
                                                        }
                                                    }
                                                    echo form_dropdown("sectionID", $sectionArray, set_value("sectionID"), "id='sectionID' class='mdb-select'");
                                                ?>
                                                <label for="sectionID" class="mdb-main-label">
                                                    <?=$this->lang->line('eattendance_section')?> <span class="text-red">*</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="col-md-6">
                                        <div class="<?php echo form_error('subjectID') ? 'form-group has-error' : 'form-group'; ?>">
                                            <div class="md-form md-form--select">
                                                
                                                <?php
                                                    $subjectArray = array('0' => $this->lang->line("eattendance_select_subject"));
                                                    if(customCompute($subjects)) {
                                                        foreach ($subjects as $subject) {
                                                            $subjectArray[$subject->subjectID] = $subject->subject;
                                                        }
                                                    }
                                                    echo form_dropdown("subjectID", $subjectArray, set_value("subjectID"), "id='subjectID' class='mdb-select'");
                                                ?>
                                                <label for="subjectID" class="mdb-main-label">
                                                    <?=$this->lang->line("eattendance_subject")?> <span class="text-red">*</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success"><?=$this->lang->line("add_attendance")?></button>
 
                                        
                          
                    </form>
    
                    <?php if(customCompute($eattendanceinfo)) { ?>
                        <div class="mt-3 mb-3 border card-body">
                            <h3 class="mt-0"> <?php echo $this->lang->line('eattendance_details');?></h3>
                            <div class="row">
                            <div class="col-md-3">
                                <small> <?php echo $this->lang->line('eattendance_exam');?></small>
                                <h4> <b><?php echo $eattendanceinfo['exam']->exam ?></b> </h4>
                            </div>
                            <div class="col-md-3">
                                <small> <?php echo $this->lang->line('eattendance_classes');?></small>
                                <h4> <b><?php echo $eattendanceinfo['class']->classes ?></b> </h4>
                            </div>

                            <div class="col-md-3">
                                <small> <?php echo $this->lang->line('eattendance_section');?></small>
                                <h4> <b><?php echo $eattendanceinfo['section']->section ?></b> </h4>
                            </div>

                            <div class="col-md-3">
                                <small> <?php echo $this->lang->line('eattendance_subject');?></small>
                                <h4> <b><?php echo $eattendanceinfo['subject']->subject ?></b> </h4>
                            </div>
                            </div>
                          
                        </div>
                    <?php } ?>
    
                    <?php if(customCompute($students)) { ?>
                        <div id="hide-table">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                        <th class="col-sm-2"><?=$this->lang->line('eattendance_photo')?></th>
                                        <th class="col-sm-2"><?=$this->lang->line('eattendance_name')?></th>
                                        <th class="col-sm-2"><?=$this->lang->line('eattendance_section')?></th>
                                        <th class="col-sm-2"><?=$this->lang->line('eattendance_email')?></th>
                                        <th class="col-sm-2"><?=$this->lang->line('eattendance_roll')?></th>
                                        <th class="col-sm-1"><?=btn_attendance('', '', 'all_attendance', $this->lang->line('add_all_attendance')).$this->lang->line('action')?></th>
                                    </tr>
                                </thead>
                                <tbody id="list">
                                    <?php if(customCompute($students)) {$i = 1; foreach($students as $student) { ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('slno')?>">
                                                <?php echo $i; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('eattendance_photo')?>">
                                                <?=profileproimage($student->photo)?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('eattendance_name')?>">
                                                <?php echo $student->name; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('eattendance_section')?>">
                                                <?php echo $student->srsection; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('eattendance_email')?>">
                                                <?php echo $student->email; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('eattendance_roll')?>">
                                                <?php echo $student->srroll; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('action')?>">
                                                <?php
                                                    if(isset($eattendances[$student->studentID])) {
                                                        $method = '';
                                                        if($eattendances[$student->studentID]->eattendance == "Present") { $method = "checked"; }
                                                        echo btn_attendance($student->studentID, $method, 'attendance btn btn-warning', $this->lang->line('add_title'));
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php $i++; }} ?>
                                </tbody>
                            </table>
                        </div>
    
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
    
                            $('.attendance').click(function() {
                                var examID = "<?=$examID?>";
                                var classesID = "<?=$classesID?>";
                                var sectionID = "<?=$sectionID?>";
                                var subjectID = "<?=$subjectID?>";
                                var studentID = $(this).attr('id');
                                var status = "";
    
                                if($(this).prop('checked')) {
                                    status = "checked";
                                } else {
                                    status = "unchecked";
                                }
    
                                if(parseInt(examID) && parseInt(classesID) && parseInt(subjectID)) {
                                    $.ajax({
                                        type: 'POST',
                                        url: "<?=base_url('eattendance/single_add')?>",
                                        data: {"examID" : examID, "classesID" : classesID, 'sectionID' : sectionID, "subjectID" : subjectID, "studentID" : studentID , "status" : status },
                                        dataType: "html",
                                        success: function(data) {
                                            toastr["success"](data)
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
                                    });
                                }
                            });
    
    
                            $('.all_attendance').click(function() {
                                var examID = "<?=$examID?>";
                                var classesID = "<?=$classesID?>";
                                var sectionID = "<?=$sectionID?>";
                                var subjectID = "<?=$subjectID?>";
                                var status = "";
    
                                if($(".all_attendance").prop('checked')) {
                                    status = "checked";
                                    $('.attendance').prop("checked", true);
                                } else {
                                    status = "unchecked";
                                    $('.attendance').prop("checked", false);
                                }
    
                                if(parseInt(examID) && parseInt(classesID) && parseInt(subjectID)) {
                                    $.ajax({
                                        type: 'POST',
                                        url: "<?=base_url('eattendance/all_add')?>",
                                        data: {"examID" : examID, "classesID" : classesID, 'sectionID' : sectionID, "subjectID" : subjectID , "status" : status },
                                        dataType: "html",
                                        success: function(data) {
                                            toastr["success"](data)
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
                                    });
                                }
                            });
                        </script>
                    <?php } ?>
                
        </div><!-- Body -->
    </div><!-- /.box -->
</div>

<script type="text/javascript">
$('.select2').select2();
$('#classesID').change(function(event) {
    var classesID = $(this).val();
    if(classesID === '0') {
        $('#subjectID').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('eattendance/subjectcall')?>",
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
            url: "<?=base_url('eattendance/sectioncall')?>",
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
</script>
