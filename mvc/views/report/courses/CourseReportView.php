<style>

.panned-icon.in.sortable-block--shown {
    opacity: 0;
}

.panned-icon.in.sortable-block--shown:hover {
    opacity: 1;
}

.chapterTitle {
    font-size: 16px;
    color: #2e2e2e;
    font-weight: 600;
} 

.breadcrumb li.active {
    color: #fff !important;
}

.breadcrumb li+li:before {
    color: #fff !important;
}


</style>


<!-- <div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-library"></i> Course Report</h3>
        <ol class="breadcrumb">
            <li><a href="<?//=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i>
             <?//=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active" style="color:#fff;">Course Report</li>
        </ol>
    </div>
</div> -->


<div class="row">
    <div class="content">
        <div class="col-sm-8" id="load_coursereport">
            <div class="card">
                <div class="card-body">
                    <section class="mt-4 mb-5 pb-5" id="logList">
                        Please filter to view Course Report.
                    </section>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <div class="form-group col-sm-12" id="classesDiv">
                        <label for="classesID"><?=$this->lang->line("onlineexamreport_classes")?></label> <span class="text-red">*</span>
                        <?php
                            $array = array("0" => $this->lang->line("onlineexamreport_please_select"));
                            if(customCompute($classes)) {
                                foreach ($classes as $classa) {
                                    $array[$classa->classesID] = $classa->classes;
                                }
                            }
                            echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control select2'");
                        ?>
                    </div>

                    <div class="form-group col-sm-12" id="subjectDiv">
                        <label for="subjectID"><?=$this->lang->line("onlineexamreport_subject")?></label>
                        <select id="subjectID" name="subjectID" class="form-control select2">
                            <option value=""><?php echo $this->lang->line("onlineexamreport_please_select"); ?></option>
                        </select>
                    </div>


                    <div class="form-group col-sm-12" id="sectionDiv">
                        <label for="sectionID"><?=$this->lang->line("onlineexamreport_section")?></label>
                        <select id="sectionID" name="sectionID" class="form-control select2">
                            <option value=""><?php echo $this->lang->line("onlineexamreport_please_select"); ?></option>
                        </select>
                    </div>

                    <div class="form-group col-sm-12" id="studentDiv">
                        <label for="studentID"><?=$this->lang->line("onlineexamreport_student")?></label></label> <span class="text-red">*</span>
                        <select id="studentID" name="studentID" class="form-control select2">
                            <option value="0"><?php echo $this->lang->line("onlineexamreport_please_select"); ?></option>
                        </select>
                    </div>

                    <div class="form-group col-sm-12" id="courseDiv">
                        <label for="courseID"><?=$this->lang->line("onlineexamreport_course")?></label></label> <span class="text-red">*</span>
                        <select id="courseID" name="courseID" class="form-control select2">
                            <option value="0"><?php echo $this->lang->line("select_course"); ?></option>
                        </select>
                    </div>

                    <div class="col-sm-12">
                        <button id="get_onlineexam" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("onlineexamreport_submit")?></button>
                    </div>
                        </div>
                        </div>
            </div>
        
    </div>


</div>




<script type="text/javascript">

    $('.select2').select2();
    
    function divHide(){
        $('#sectionDiv').hide('slow');  
        $('#studentDiv').hide('slow');   
        $('#courseDiv').hide('slow');   
        $('#subjectDiv').hide('slow');   
    }

    function divShow(){ 
        $('#sectionDiv').show('slow');  
        $('#studentDiv').show('slow');  
        $('#courseDiv').show('slow');   
        $('#subjectDiv').show('slow');   
    }

    $(document).ready(function() {
        divHide();
    });

    $(document).on('change', "#classesID", function() {
        var id = $(this).val();
        if(id != '0') {
            divShow()
        }
    })

 
    $(document).on('change', "#classesID", function() {
        var classesID = $(this).val();
        if(classesID == '0') {
            $('#sectionID').html('<option value="">'+"<?=$this->lang->line("onlineexamreport_please_select")?>"+'</option>');
            $('#sectionID').val('');

            $('#studentID').html('<option value="0">'+"<?=$this->lang->line("onlineexamreport_please_select")?>"+'</option>');
            $('#studentID').val(0);

            $('#courseID').html('<option value="0">'+"<?=$this->lang->line("onlineexamreport_please_select")?>"+'</option>');
            $('#courseID').val(0);
    
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('onlineexamreport/getSection')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?=base_url('onlineexamreport/getStudent')?>",
                data: {'classesID' : classesID, 'sectionID' : 0},
                dataType: "html",
                success: function(data) {
                   $('#studentID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?=base_url('courses/getCourses')?>",
                data: {'classesID' : classesID},
                dataType: "html",
                success: function(data) {
                   $('#courseID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?=base_url('courses/getSubject')?>",
                data: {'classesID' : classesID},
                dataType: "html",
                success: function(data) {
                   $('#subjectID').html(data);
                }
            });
        }
    });

    $(document).on('change', "#subjectID", function() {
        var classesID = $('#classesID').val();
        var subjectID = $(this).val();

        $.ajax({
            type: 'POST',
            url: "<?=base_url('courses/getCourses')?>",
            data: {'classesID' : classesID, 'subjectID': subjectID},
            dataType: "html",
            success: function(data) {
                $('#courseID').html(data);
            }
        });
    });

    $(document).on('change', "#sectionID", function() {
        var classesID = $('#classesID').val();
        var sectionID = $('#sectionID').val();

        $.ajax({
            type: 'POST',
            url: "<?=base_url('onlineexamreport/getStudent')?>",
            data: {'classesID' : classesID, 'sectionID' : sectionID},
            dataType: "html",
            success: function(data) {
               $('#studentID').html(data);
            }
        });
    });

    $(document).on('click', "#get_onlineexam", function() {
      
        var error = 0 ;
        var field = {
            'classesID'     : $('#classesID').val(), 
            'courseID'     : $('#courseID').val(), 
            'sectionID'     : $('#sectionID').val(), 
            'studentID'     : $('#studentID').val(), 
            'statusID'      : $('#statusID').val(),  
        }

        error = validation_checker(field, error);

        if(error === 0) {
            makingPostDataPreviousofAjaxCall(field);
        }
    });

    function validation_checker(field, error) {
        if(field['classesID'] == 0) {
            $('#classesDiv').addClass('has-error');
            error++;
        } else {
            $('#onlineexamDiv').removeClass('has-error');
            $('#classesDiv').removeClass('has-error');
        }

        if (field['statusID'] == 0) {
            $('#statusDiv').addClass('has-error');
            error++;
        } else {
            $('#statusDiv').removeClass('has-error');
        }

        if (field['studentID'] == 0) {
            $('#studentDiv').addClass('has-error');
            error++;
        } else {
            $('#studentDiv').removeClass('has-error');
        }

        if (field['courseID'] == 0) {
            $('#courseDiv').addClass('has-error');
            error++;
        } else {
            $('#courseDiv').removeClass('has-error');
        }

        return error;
    }

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('coursesreport/getCourseList')?>",
            data: passData,
            dataType: "html",
            success: function(data) {
                var response = JSON.parse(data);
                renderLoder(response, passData);
            }
        });
    }

    function renderLoder(response, passData) {
        if(response.status) {
            $('#load_coursereport').html(response.render);
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
</script>
