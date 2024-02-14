<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-onlineexamreport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_onlineexamreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <form name="frm-marksheet" method="post">
    <input type="hidden" name="main_id" value="<?php echo $row->id;?>">
    <div class="box-body">
    <div class="row">
        <div class="form-group col-sm-4">
                    <label for="classesID">Title</label>
                    <span class="text-red">*</span>
                    <input name="title" class="form form-control" value="<?php echo $row->title;?>" type="text" required>
        </div>
    </div>

        <div class="row">
        <div class="form-group col-sm-4">
                    <label for="classesID"><?=$this->lang->line("onlineexamreport_classes")?></label>
                    <span class="text-red">*</span>
                    <?php
                        $array = array("0" => $this->lang->line("onlineexamreport_please_select"));
                        if(customCompute($classes)) {
                            foreach ($classes as $classa) {
                                 $array[$classa->classesID] = $classa->classes;
                            }
                        }
                        echo form_dropdown("classesID", $array, set_value("classesID", $row->class_id), "id='classesID' class='form-control select2'");
                     ?>
        </div>
    </div>
    <div class="row">
    <div class="form-group col-sm-4">
    <label for="sectionID">Select Subject</label>
    <div id="sectionDiv">
        <?php
        $md = [];
        if(!empty($mdetails))
        {
            foreach($mdetails as $mdetailsval)
            {
              $md[]= $mdetailsval->terminal_id;
            }
        }
        $returnval = '<ul style="list-style-type:none;">';
		$i = 1;
		if(!empty($subjects))
		{
		foreach ($subjects as $subject) {
            if (in_array($subject->onlineExamID,$md))
                {
                  $checked = "checked";
                }
           else
                {
                    $checked = '';
                }   
			$returnval .= '<li>'.$i.') <input type="checkbox" id="sub'.$subject->onlineExamID.'"  name="subject[]" value="'.$subject->onlineExamID.'" '.$checked.'>
			<label for="sub'.$subject->onlineExamID.'">'.$subject->name.'</label></li>';
           
			$i++;
		}
		}
		else
		{
			$returnval .= '<li>Subject not found</li>';
		}
		$returnval .='</ul>';
		echo $returnval;
        ?>
    </div>                
    </div>
</div>
</div>
<div class="row">
<div class="form-group col-sm-4">
<input type="submit" class="btn btn-success" style="margin-left:20px;" name="sbt-from" value="Save">
</div>
</div>
</div><!-- Body -->
</form>
<input type="hidden" id="ajax-get-terminal-url" value="<?php echo base_url() ?>chapter/ajaxTerminalFromClassId">
</div><!-- /.box -->
<script>

$(document).on('change', '#classesID', function() {
    let class_id = $(this).val();
    let url = $('#ajax-get-terminal-url').val()
    $('#subject_id').trigger('change');
    $.ajax({
      url: url + "?class_id=" + class_id,
    }).done(function( data ) {
       $('#sectionDiv').html(data);
    });
})
</script>