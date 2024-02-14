<header class="pg-header mt-4">
    <div>
        <h1 class="pg-title">
            <?=$this->lang->line('panel_title')?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"> Add <?=$this->lang->line('menu_classdetail')?></li>
        </ol>
    </div>
</header>
<div class="card card--spaced">
    <!-- form start -->
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="<?php echo form_error('classgroupID') ? 'form-group has-error' : 'form-group'; ?>" >
                            <div class="md-form md-form--select">
                                            <?php
                                                $array = array("0" => $this->lang->line("classdetail_please_select"));
                                                foreach ($classgroups as $classgroup) {
                                                    $array[$classgroup->classgroupID] = $classgroup->group;
                                                }
                                                echo form_dropdown("classgroupID", $array, set_value("classgroupID"), "id='classgroupID' class='mdb-select classgroupID'");
                                            ?>
                                            <label for="classesID" class="mdb-main-label">
                                                <?=$this->lang->line('classdetail_class_group')?>  <span class="text-red"> *</span>

                                            </label>
                                            <span class="text-red"> <?php echo form_error('classgroupID'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                                    <input type="hidden" id="classSelect" value="0"/>
                                    <div class="<?php echo form_error('classID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        
                                        <div class="md-form md-form--select">
                                            <?php
                                                $array = array("0" => $this->lang->line("classdetail_please_select"));
                                                foreach ($classes as $class) {
                                                    $array[$class->classesID] = $class->classes;
                                                }
                                                echo form_dropdown("classID", $array, set_value("classID"), "id='classID' class='mdb-select classID'");
                                            ?>
                                            <label for="classesID" class="mdb-main-label">
                                                <?=$this->lang->line('classdetail_class')?>  <span class="text-red"> *</span>

                                            </label>
                                            <span class="text-red"> <?php echo form_error('classID'); ?></span>
                                        </div>
                                    </div>
                    </div>
                </div>

                <!-- <div class="list-inline">
                <button type="submit" id="add_details" class="btn btn-success"><?=$this->lang->line('classdetail_submit')?></button>
                </div> -->
            </div>
        </div>
    </div>
</div>
<div class="contentWrapper">

</div>

<script type="text/javascript">

    $('.select2').select2();

    $(document).on('change', "#classgroupID", function() {

        var val = $(this).val();

        $.ajax({
            type: 'POST',
            url: "<?=base_url('classdetail/getclasses')?>",
            data: {'classgroupID' : val},
            dataType: "html",
            success: function(data) {
               $('#classID').html(data);
               $('.mdb-select').material_select('destroy');
               $('.mdb-select').material_select();
            }
        });
    });

    $(document).on('change', "#classID", function() {

        
        var classesID = $(this).val();  
       
        if(classesID == 0){
            showToastError('Please select class.');
            return false;
        }

        // var result = confirm("Are you sure you want to select class?");
        // if(result){
            $.ajax({
                type: 'POST',
                url: "<?=base_url('classdetail/detailsTemplate')?>",
                data: {'classesID' : classesID},
                dataType: "html",
                success: function(data) {
                  $('#classSelect').val(classesID);  
                  $('.contentWrapper').html(data);
                }
            });
        // }else{
        //     $('#classID').val($('#classSelect').val());
        //     $('#classID').material_select();
        // }
    });

</script>