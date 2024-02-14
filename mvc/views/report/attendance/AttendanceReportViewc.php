
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-attendancereport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_attendancereport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">
                
                <div class="form-group col-sm-3" id="classesDiv">
                    <label><?=$this->lang->line("attendancereport_class")?><span class="text-red"> * </span></label>
                    <?php
                        $array = array("0" => $this->lang->line("attendancereport_please_select"));
                        if(customCompute($classes)) {
                            foreach ($classes as $classa) {
                                 $array[$classa->classesID] = $classa->classes;
                            }
                        }
                        echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-3" id="sectionDiv">
                    <label><?=$this->lang->line("attendancereport_section")?></label>
                    <select id="sectionID" name="sectionID" class="form-control select2">
                        <option value=""><?php echo $this->lang->line("attendancereport_please_select"); ?></option>
                    </select>
                </div>

                <div class="form-group col-sm-3" id="dateDivfrom">
                    <label>Date From<span class="text-red"> * </span></label>
                    <input class="form-control" name="datefrom" id="datefrom" value="" type="text">
                </div>
                <div class="form-group col-sm-3" id="dateDivto">
                    <label>Date To<span class="text-red"> * </span></label>
                    <input class="form-control" name="dateto" id="dateto" value="" type="text">
                </div>

                <div class="col-sm-4">
                    <button id="get_attendancereport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("attendancereport_submit")?></button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_attendance_report"></div>

<script type="text/javascript">
    $('.select2').select2();
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('attendancereport_hotline')?> : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

    
    function divHide() {
        $("#classesDiv").show("slow");
        $("#sectionDiv").show("slow");
        $("#dateDivfrom").show("slow");
        $("#dateDivto").show("slow");
        $("#subjectDiv").hide("slow");
    }

    function divShow() {
        $("#classesDiv").show("slow");
        $("#sectionDiv").show("slow");
        $("#dateDivfrom").show("slow");
        $("#dateDivto").show("slow");
    }

    $(document).ready(function() {
        $("#attendancetype").val('0');
        $("#classesID").val(0);
        $("#sectionID").val('');
        divHide();
    });

    $(document).bind('click', function() {
        $('#datefrom').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate:'<?=$schoolyearsessionobj->startingdate?>',
            endDate:'<?=$schoolyearsessionobj->endingdate?>',
            daysOfWeekDisabled: "<?=$siteinfos->weekends?>",
            datesDisabled: ["<?=$get_all_holidays;?>"], 
        }); 
    });
    $(document).bind('click', function() {
        $('#dateto').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate:'<?=$schoolyearsessionobj->startingdate?>',
            endDate:'<?=$schoolyearsessionobj->endingdate?>',
            daysOfWeekDisabled: "<?=$siteinfos->weekends?>",
            datesDisabled: ["<?=$get_all_holidays;?>"], 
        }); 
    });

    // $(document).on('change','#attendancetype', function() {
    //     $('#load_attendance_report').html('');
    //     var type = $(this).val();
    //     if(type != 0) {
    //         divShow();
    //     } else {
    //         $('#classesID').val(0);
    //         divHide();
    //     }
    // });

    $(document).on('change', '#classesID', function() {
        $('#load_attendance_report').html('');
        var id = $(this).val();
        if(id == 0) {
            $('#sectionID').html('<option value="">'+"<?=$this->lang->line("attendancereport_please_select")?>"+'</option>');
            $('#sectionID').val('');

            $('#subjectID').html('<option value="">'+"<?=$this->lang->line("attendancereport_please_select")?>"+'</option>');
            $('#subjectID').val('');
            
            $('#datefrom').val('');
            $('#dateto').val('');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendancereport/getSection')?>",
                data: {"id" : id},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendancereport/getSubject')?>",
                data: {"classID" : id},
                dataType: "html",
                success: function(data) {
                   $('#subjectID').html(data);
                }
            });
        }
    });

    $(document).on('change', '#sectionID', function() {
        $('#load_attendance_report').html('');
    });

    $(document).on('change', '#subjectID', function() {
        $('#load_attendance_report').html('');
    });
    
    $(document).on('change', '#datefrom', function() {
        $('#load_attendance_report').html('');
    });

    $(document).on('change', '#dateto', function() {
        $('#load_attendance_report').html('');
    });

    $(document).on('click', '#get_attendancereport', function() {
        $('#load_attendance_report').html('');
        var error = 0;
        var field = {
            'classesID' : $('#classesID').val(),
            'sectionID' : $('#sectionID').val(),
            'datefrom' : $('#datefrom').val(),
            'dateto' : $('#dateto').val(),
            'subjectID' : $('#subjectID').val(),
        };

        error = validation_checker(field, error);

        if(error === 0) {
            makingPostDataPreviousofAjaxCall(field);
        }

    });

    function validation_checker(field, error){
        if (field['classesID'] == 0) {
            $('#classesDiv').addClass('has-error');
            error++;
        } else {
            $('#classesDiv').removeClass('has-error');
        }

        if (field['datefrom'] == '') {
            $('#dateDivfrom').addClass('has-error');
            error++;
        } else {
            $('#dateDivfrom').removeClass('has-error');
        }

        if (field['dateto'] == '') {
            $('#dateDivto').addClass('has-error');
            error++;
        } else {
            $('#dateDivto').removeClass('has-error');
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
            url: "<?=base_url('attendancereport/getAttendacnecReport')?>",
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
            $('#load_attendance_report').html(response.render);
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
