<?php if ($siteinfos->note==1) { ?>
    <div class="callout callout-danger">
        <p><b>Note:</b> Create exam, class & section  before add mark</p>
    </div>
<?php } ?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="#">Student Attendance By Exam</a></li>
            <li class="active"> Add </li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">
                                
                                <div class="col-md-3">
                                    <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label for="classesID" class="control-label">
                                            <?=$this->lang->line('studentattendancebyexam_classes')?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                            $array = array("0" => $this->lang->line("studentattendancebyexam_select_classes"));
                                            foreach ($classes as $classa) {
                                                $array[$classa->classesID] = $classa->classes;
                                            }
                                            echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control select2 classesID'");
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="<?php echo form_error('examID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label for="examID" class="control-label">
                                            <?=$this->lang->line('studentattendancebyexam_exam')?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                            $array = array("0" => $this->lang->line("studentattendancebyexam_select_exam"));
                                            foreach ($exams as $exam) {
                                                $array[$exam->examID] = $exam->exam;
                                            }
                                            echo form_dropdown("examID", $array, set_value("examID"), "id='examID' class='form-control select2 examID'");
                                        ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label class="control-label"><?=$this->lang->line('studentattendancebyexam_section')?> <span class="text-red">*</span></label>
                                        <?php
                                            $arraysection = array('0' => $this->lang->line("studentattendancebyexam_select_section"));
                                            if(customCompute($sections)) {
                                                foreach ($sections as $section) {
                                                    $arraysection[$section->sectionID] = $section->section;
                                                }
                                            }
                                            echo form_dropdown("sectionID", $arraysection, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>
                              
                               
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group" >
                                        <input type="submit" class="btn btn-success col-md-12 col-xs-12" style="margin-top: 20px;" value=<?=$this->lang->line('add_studentattendancebyexam')?> name="add_studentattendancebyexam" />
                                    </div>
                                </div>
                                                             
                            </div>
                        </div>

                    </div>
                </form>


                <?php if(customCompute($sendExam) && customCompute($sendClasses) && customCompute($sendSection) ) { ?>
                    <div class="col-sm-4 col-sm-offset-4 box-layout-fame">
                        <?php
                            echo '<h5><center>'.$this->lang->line('studentattendancebyexam_details').'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('studentattendancebyexam_exam').' : '.$sendExam->exam.'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('studentattendancebyexam_classes').' : '. $sendClasses->classes.'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('studentattendancebyexam_section').' : '. $sendSection->section.'</center></h5>';
                        ?>
                    </div>
                <?php } ?>
            </div>

           

            <div class="col-sm-12">
                <?php if(customCompute($students)) { ?>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <p>
                            <strong>School days base on exam:</strong>
                             <input class= "form-control" require  id="school_days" name="school_days" value ="<?php echo $schoolDays; ?>"/>
                        </p>
                    </div>

                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th><?=$this->lang->line('slno')?></th>
                                    <th><?=$this->lang->line('studentattendancebyexam_photo')?></th>
                                    <th><?=$this->lang->line('studentattendancebyexam_name')?></th>
                                    <th><?=$this->lang->line('studentattendancebyexam_roll')?></th>
                                    <th><?=$this->lang->line('studentattendancebyexam_presentdays')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(customCompute($students)) 
                                {$i = 1; 
                                    foreach($students as $student) { 
                                     ?>
                                    <tr>
                                         
                                        <td data-title="<?=$this->lang->line('slno')?>">
                                            <?php echo $i; ?>
                                           
                                        </td>
                                        <td data-title="<?=$this->lang->line('studentattendancebyexam_photo')?>">
                                            <?=profileproimage($student['photo'])?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('studentattendancebyexam_name')?>">
                                            <?php echo $student['name']; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('studentattendancebyexam_roll')?>">
                                            <?php echo $student['roll']; ?>
                                        </td>
                                         <td>
                                              <input class= "form-control presentDays" name="presentDays-<?php echo $student['studentID'] ?>" value ="<?php echo $student['presentDays']; ?>"/>
                                        </td>
                                    </tr>
                                <?php $i++;  }} ?>
                            </tbody>
                        </table>
                    </div>
                    <input type="button" class="btn btn-success" id="add_attendance" name="add_attendance" value="<?=$this->lang->line("add_sub_studentattendancebyexam")?>" />

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
                        
                        

                        $("#add_attendance").click(function() {
                            
                            var schooldays = $('#school_days').val();
                            if(schooldays == ''){
                                alert('School days can not empty.');
                                return false;
                            }

                            var remarks = $('input[name^=presentDays]').map(function(){
                                return {  id: this.name , value: this.value};
                            }).get();

                            $.ajax({
                                type: 'POST',
                                url: "<?=base_url('studentattendancebyexam/sendAttendance')?>",
                                data: {"examID" : "<?=$set_exam?>", "classesID" : "<?=$set_classes?>", "sectionID" : "<?=$set_section?>", "inputs" : remarks,"schooldays":schooldays},
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
                                        if(response.inputs) {
                                            toastr["error"](response.inputs)
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



    $('.select2').select2();
    $("#classesID").change(function() {
        var classesID = $(this).val();
        if(parseInt(classesID)) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('studentattendancebyexam/examcall')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#examID').html(data);
                }
            });

          

            $.ajax({
                type: 'POST',
                url: "<?=base_url('studentattendancebyexam/sectioncall')?>",
                data: {"id" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });
        }
    });

</script>
