<?php if ($siteinfos->note==1) { ?>
    <div class="callout callout-danger">
        <p><b>Note:</b> Add student attendance base on class and section.</p>
    </div>
<?php } ?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="#">Student Attendance</a></li>
            <li class="active">Add</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <form method="POST">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">
                                
                                <div class="col-md-3">
                                    <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label for="classesID" class="control-label">
                                            <?=$this->lang->line('studentattendance_classes')?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                            $array = array("0" => $this->lang->line("studentattendance_select_classes"));
                                            foreach ($classes as $classa) {
                                                $array[$classa->classesID] = $classa->classes;
                                            }
                                            echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control select2 classesID'");
                                        ?>
                                    </div>
                                </div>
                                
                            

                                <div class="col-md-3">
                                    <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label class="control-label"><?=$this->lang->line('studentattendance_section')?> <span class="text-red">*</span></label>
                                        <?php
                                            $arraysection = array('0' => $this->lang->line("studentattendance_select_section"));
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
                                        <button type="submit" class="btn btn-success col-md-12 col-xs-12" style="margin-top: 20px;"><?=$this->lang->line('add_studentattendance')?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>


                <?php if(customCompute($sendClasses) && customCompute($sendSection)) { ?>
                    <div class="col-sm-4 col-sm-offset-4 box-layout-fame">
                        <?php
                            echo '<h5><center>'.$this->lang->line('studentattendance_classes').' : '. $sendClasses->classes.'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('studentattendance_section').' : '. $sendSection->section.'</center></h5>';
                        ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-12">
                <?php if(customCompute($students)) { 
                    ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th><?=$this->lang->line('slno')?></th>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Roll</th>
                                    <th>
                                        Present Days
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(customCompute($students)) {
                                    $i = 1;
                                     foreach($students as $student) {
                                            ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('slno')?>">
                                            <?php echo $i; ?>
                                        </td>
                                        <td data-title="Photo">
                                            <?=profileproimage($student->photo)?>
                                        </td>
                                        <td data-title="Name">
                                            <?php echo $student->name; ?>
                                        </td>
                                        <td data-title="Roll">
                                            <?php echo $student->roll; ?>
                                        </td>
                                       <td data-title="Present Days">
                                           <input type="number" value="<?php echo $student->presentdays; ?>" class= "form-control presents" name="presents-<?php echo $student->studentID ?>"/>
                                       </td>

                                    </tr>
                                <?php $i++;  }} ?>
                            </tbody>
                        </table>
                    </div>
                    <input type="button" class="btn btn-success" id="add_attendance" name="add_attendance" value="Add" />

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
                            var inputs = "";
                            var inputs_value = "";
                            var sattendance = $('input[name^=presents]').map(function(){
                                return { 
                                    days: this.name , value: this.value
                                };
                            }).get();


                            $.ajax({
                                type: 'POST',
                                url: "<?=base_url('studentattendance/studentAttendance_send')?>",
                                data: {"classesID" : "<?=$set_classes?>", "inputs" : sattendance},
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
                url: "<?=base_url('studentattendance/sectioncall')?>",
                data: {"id" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });
        }
    });

</script>
