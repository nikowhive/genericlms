<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-terminalreport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"> <?=$this->lang->line('menu_terminalreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="classesDiv">
                    <label><?=$this->lang->line("terminalreport_class")?><span class="text-red"> * </span></label>
                    <?php
                        $classesArray['0'] = $this->lang->line("terminalreport_please_select");
                        if(customCompute($classes)) {
                            foreach ($classes as $classaKey => $classa) {
                                $classesArray[$classa->classesID] = $classa->classes;
                            }
                        }
                        echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                     ?>
                </div>
                <div class="form-group col-sm-4" id="examDiv">
                    <label><?=$this->lang->line("terminalreport_exam")?><span class="text-red"> * </span></label>
                    <?php
                        $examsArray['0'] = $this->lang->line("terminalreport_please_select");
                        if(customCompute($exams)) {
                            foreach ($exams as $exam) {
                                $examsArray[$exam->examID] = $exam->exam;
                            }
                        }
                        echo form_dropdown("examID", $examsArray, set_value("examID"), "id='examID' class='form-control select2'");
                     ?>
                </div>
                <div class="form-group col-sm-4" id="sectionDiv">
                    <label><?=$this->lang->line("terminalreport_section")?></label>
                    <?php
                        $sectionArray[0] = $this->lang->line("terminalreport_please_select");
                        echo form_dropdown("sectionID", $sectionArray, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                     ?>
                </div>
                <div class="form-group col-sm-4" id="studentDiv">
                    <label><?=$this->lang->line("terminalreport_student")?></label>
                    <?php
                        $studentArray[0] = $this->lang->line("terminalreport_please_select");
                        echo form_dropdown("studentID", $studentArray, set_value("studentID"), "id='studentID' class='form-control select2'");
                     ?>
                </div>
                <div class="col-sm-4" id="dateDiv">
                    <div class="<?php echo form_error('date') ? 'form-group has-error' : 'form-group'; ?>" >
                        <label class="control-label"><?=$this->lang->line('terminalreport_date')?>
                        <input type="text" class="form-control date-picker" name="date" id="date" placeholder="Select Date of Issue">
                    </div>
                </div>
                <div id="classTeacherDiv" class="col-sm-4 form-group <?=form_error('class_teacher') ? 'has-error' : ''?>">
                    <div class="col-sm-12">
                        <label class="control-label" for="class_teacher">Class Teacher Signature&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set class teacher signature here"></i></label>
                        </label>
                        <div class="input-group image-preview class_teacher-preview">
                            <input type="text" class="form-control image-preview-filename class_teacher-preview-filename" disabled="disabled">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default image-preview-clear class_teacher-preview-clear" style="display:none;">
                                    <span class="fa fa-remove"></span>
                                    Clear
                                </button>
                                <div class="btn btn-success image-preview-input class_teacher-preview-input">
                                    <span class="fa fa-repeat"></span>
                                    <span class="image-preview-input-title class_teacher-preview-input-title">
                                    File Browser</span>
                                    <input type="file" accept="image/png, image/jpeg, image/gif" name="class_teacher" id="class_teacher" />
                                </div>
                            </span>
                        </div>
                        <span class="control-label">
                            <?=form_error('class_teacher'); ?>
                        </span>
                    </div>
                </div>
                <div id="inchargeDiv" class="col-sm-4 form-group <?=form_error('incharge') ? 'has-error' : ''?>">
                    <div class="col-sm-12">
                        <label class="control-label" for="incharge">Incharge Signature&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set incharge signature here"></i></label>
                        </label>
                        <div class="input-group image-preview incharge-preview">
                            <input type="text" class="form-control image-preview-filename incharge-preview-filename" disabled="disabled">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default image-preview-clear incharge-preview-clear" style="display:none;">
                                    <span class="fa fa-remove"></span>
                                    Clear
                                </button>
                                <div class="btn btn-success image-preview-input incharge-preview-input">
                                    <span class="fa fa-repeat"></span>
                                    <span class="image-preview-input-title incharge-preview-input-title">
                                    File Browser</span>
                                    <input type="file" accept="image/png, image/jpeg, image/gif" name="incharge" id="incharge"/>
                                </div>
                            </span>
                        </div>
                        <span class="control-label">
                            <?=form_error('incharge'); ?>
                        </span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <button id="get_terminalreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("terminalreport_submit")?></button>
                </div>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_terminalreport"></div>


<script type="text/javascript">
    $('.select2').select2();
    
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        var divElements = document.getElementById(divID).innerHTML;
        document.body.innerHTML = "<html><head><title></title></head><body>" + divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }


    $(function(){
        $("#examID").val(0);
        $("#classesID").val(0);
        $("#sectionID").val(0);
        $("#studentID").val(0);

        $('#classesDiv').show('slow');
        $('#examDiv').hide('slow');
        $('#sectionDiv').hide('slow');
        $('#studentDiv').hide('slow');
    });

    $(document).on('change',"#classesID", function() {
        $('#load_terminalreport').html("");
        $('#examDiv').show('slow');
        $('#sectionDiv').show('slow');
        var classesID = $(this).val();
        if(classesID == '0') {
            $('#examDiv').hide('slow');
            $('#sectionDiv').hide('slow');
            $('#studentDiv').hide('slow');
            $('#examID').html('<option value="0">'+"<?=$this->lang->line("terminalreport_please_select")?>"+'</option>');
            $('#examID').val('0');
            $('#sectionID').html('<option value="0">'+"<?=$this->lang->line("terminalreport_please_select")?>"+'</option>');
            $('#sectionID').val('0');
            $('#studentID').html('<option value="0">'+"<?=$this->lang->line("terminalreport_please_select")?>"+'</option>');
            $('#studentID').val('0');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('terminalreport2/getExam')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#examID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?=base_url('terminalreport2/getSection')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });
        }
    });


    $(document).on('change',"#sectionID", function() {
        $('#load_terminalreport').html("");
        $('#studentDiv').show('slow');
        var classesID = $('#classesID').val();
        var sectionID = $('#sectionID').val();

        if(sectionID == '0') {
            $('#studentDiv').hide('slow');
            $('#studentID').html('<option value="0">'+"<?=$this->lang->line("terminalreport_please_select")?>"+'</option>');
            $('#studentID').val('0');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('terminalreport2/getStudent')?>",
                data: {"classesID" : classesID,"sectionID" : sectionID},
                dataType: "html",
                success: function(data) {
                   $('#studentID').html(data);
                }
            });
        }
    });

    $(document).on('click','#get_terminalreport', function() {
        $('#load_terminalreport').html("");
        var error = 0;
        var converted_date = 0;
        if ($('#date').val() != 0) {
            $('#dateDiv').removeClass('has-error');
            english_date = calendarFunctions.parseFormattedBsDate("%y-%m-%d", $('#date').val());
            converted_date = english_date.bsYear+'-'+english_date.bsMonth+'-'+english_date.bsDate
        }

        var data = new FormData();
        $('input[type="file"]').each(function($i){
            data.append($(this).prop("id"), $(this)[0].files[0]);
        });
        var field = {
            'examID'      : $('#examID').val(), 
            'classesID'   : $('#classesID').val(), 
            'sectionID'   : $('#sectionID').val(), 
            'studentID'   : $('#studentID').val(), 
            'date'        : converted_date,
        };
        $.each(field,function(key,input){
            data.append(key,input);
        });



        if (field['examID'] == 0) {
            $('#examDiv').addClass('has-error');
            error++;
        } else {
            $('#examDiv').removeClass('has-error');
        }

        if (field['classesID'] == 0) {
            $('#classesDiv').addClass('has-error');
            error++;
        } else {
            $('#classesDiv').removeClass('has-error');
        }

        if (error == 0) {
            makingPostDataPreviousofAjaxCall(data);
        }
    });

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('terminalreport2/getTerminalreport')?>",
            data: passData,
            dataType: "html",
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                var response = JSON.parse(data);
                renderLoder(response, passData);
            }
        });
    }

    function renderLoder(response, passData) {
        if(response.status) {
            $('#load_terminalreport').html(response.render);
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#'+key).parent().removeClass('has-error');
                }
            }
        } else {
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#'+key).parent().removeClass('has-error');
                }
            }

            for (var key in response) {
                if (response.hasOwnProperty(key)) {
                    $('#'+key).parent().addClass('has-error');
                }
            }
        }
    }

    $('#date').nepaliDatePicker({
        dateFormat: "%y-%m-%d",
        closeOnDateSelect: true,
        // minDate: 'सोम, जेठ १०, २०७३',
        // maxDate: 'मंगल, जेठ ३२, २०७३'
    });


    $(document).on('click', '#close-preview', function(){ 

        $('.incharge-preview').popover('hide');
        // Hover befor close the preview
        $('.incharge-preview').hover(
            function () {
               $('.incharge-preview').popover('show');
               $('.content').css('padding-bottom', '120px');
            }, 
             function () {
               $('.incharge-preview').popover('hide');
               $('.content').css('padding-bottom', '20px');
            }
        );   
        
        $('.class_teacher-preview').popover('hide');
        // Hover befor close the preview
        $('.class_teacher-preview').hover(
            function () {
               $('.class_teacher-preview').popover('show');
               $('.content').css('padding-bottom', '120px');
            }, 
             function () {
               $('.class_teacher-preview').popover('hide');
               $('.content').css('padding-bottom', '20px');
            }
        );   
    });

    $(function() {
        // Create the close button
        var closebtn = $('<button/>', {
            type:"button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class","close pull-right");
        // Set the popover default content
        $('.photo-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        $('.incharge-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        $('.class_teacher-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });

        // Clear event
        $('.incharge-preview-clear').click(function(){
            $('.incharge-preview').attr("data-content","").popover('hide');
            $('.incharge-preview-filename').val("");
            $('.incharge-preview-clear').hide();
            $('.incharge-preview-input input:file').val("");
            $(".incharge-preview-input-title").text("<?=$this->lang->line('setting_file_browse')?>"); 
        }); 
        $('.class_teacher-preview-clear').click(function(){
            $('.class_teacher-preview').attr("data-content","").popover('hide');
            $('.class_teacher-preview-filename').val("");
            $('.class_teacher-preview-clear').hide();
            $('.class_teacher-preview-input input:file').val("");
            $(".class_teacher-preview-input-title").text("<?=$this->lang->line('setting_file_browse')?>"); 
        }); 
        
        // Create the preview image
        $(".incharge-preview-input input:file").change(function (){     
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200,
                overflow:'hidden'
            });      
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".incharge-preview-input-title").text("<?=$this->lang->line('setting_clear')?>");
                $(".incharge-preview-clear").show();
                $(".incharge-preview-filename").val(file.name);            
                img.attr('src', e.target.result);
                $(".incharge-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $('.content').css('padding-bottom', '120px');
            }        
            reader.readAsDataURL(file);
        });  
        $(".class_teacher-preview-input input:file").change(function (){     
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200,
                overflow:'hidden'
            });      
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".class_teacher-preview-input-title").text("<?=$this->lang->line('setting_clear')?>");
                $(".class_teacher-preview-clear").show();
                $(".class_teacher-preview-filename").val(file.name);            
                img.attr('src', e.target.result);
                $(".class_teacher-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $('.content').css('padding-bottom', '120px');
            }        
            reader.readAsDataURL(file);
        });  
    });


</script>


