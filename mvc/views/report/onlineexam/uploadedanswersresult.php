<div id="printablediv">
    <div class="box">
        <div class="box-header bg-gray">
            <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('onlineexamreport_report_for')?> <?=$this->lang->line('onlineexamreport_onlineexam')?></h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <h5 class="pull-left"><?=$this->lang->line('onlineexamreport_classes');?> : <?=customCompute($classes) ? $classes->classes : $this->lang->line('onlineexamreport_select_all_classes') ?></h5>

                    <?php if($sectionID == 0) {?>
                        <h5 class="pull-right"><?=$this->lang->line('onlineexamreport_section')?> : <?=$this->lang->line('onlineexamreport_select_all_section');?></h5>
                    <?php } else { ?>
                        <h5 class="pull-right"><?=$this->lang->line('onlineexamreport_section')?> : <?=customCompute($section) ? $section->section : '' ?></h5>
                    <?php } ?>
                    
                </div>
                <div class="col-sm-12">
                    <?php if(customCompute($result)) { ?>
	                    <div id="hide-table">
	                        <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
	                            <thead>
	                                <tr>
	                                    <th>#</th>
	                                    <th><?=$this->lang->line('onlineexamreport_photo')?></th>
	                                    <th><?=$this->lang->line('onlineexamreport_name')?></th>
	                                    <?php  if($sectionID == 0 ) { ?>
	                                        <th><?=$this->lang->line('onlineexamreport_section')?></th>
	                                    <?php } ?>
	                                    <?php if($studentID > 0) { ?>
		                                    <th>
		                                    	<?=$this->lang->line('onlineexamreport_subject')?>
		                                    </td>
	                                    <?php } ?>
	                                    <th><?=$this->lang->line('onlineexamreport_datetime')?></th>
	                                    <th>Asset</th>
	                                    <th><?=$this->lang->line('action')?></th>
	                                </tr>
	                            </thead>



	                            <?php $i = 1; foreach($result as $onlineexam_user_status)  { ?>
                                    <tr>
                                        <td data-title="#">
                                            <?php echo $i; ?>
                                        </td>

                                        <td data-title="<?=$this->lang->line('onlineexamreport_photo')?>">
                                            <?php
                                            	if(isset($students[$onlineexam_user_status->student_id])) {
	                                             	$array = array(
	                                                    "src" => base_url('uploads/images/'.$students[$onlineexam_user_status->student_id]->photo),
	                                                    'width' => '35px',
	                                                    'height' => '35px',
	                                                    'class' => 'img-rounded'
	                                                );
                                            	} else {
                                            		$array = array(
	                                                    "src" => base_url('uploads/images/default.png'),
	                                                    'width' => '35px',
	                                                    'height' => '35px',
	                                                    'class' => 'img-rounded'
	                                                );
                                            	}
                                                echo img($array);
                                            ?>
                                        </td>

                                        <td data-title="<?=$this->lang->line('onlineexamreport_name')?>">
                                            <?=isset($students[$onlineexam_user_status->student_id]) ? $students[$onlineexam_user_status->student_id]->name : '' ?>
                                        </td>

                                        <?php  if($sectionID == 0 ) { ?>
	                                        <td data-title="<?=$this->lang->line('onlineexamreport_section')?>">
	                                            <?php
	                                            	if(isset($students[$onlineexam_user_status->student_id])) {
	                                            		if(isset($sections[$students[$onlineexam_user_status->student_id]->sectionID])) {
	                                            			echo $sections[$students[$onlineexam_user_status->student_id]->sectionID]->section;
	                                            		}
	                                            	}
	                                            ?>
	                                        </td>
	                                    <?php } ?>

	                                    <?php if($studentID > 0) { ?>
		                                    <td data-title="<?=$this->lang->line('onlineexamreport_subject')?>">
		                                    	<?php 
		                                    		if(isset($onlineexams[$onlineexam_user_status->exam_id])) {
		                                    			
		                                    			if(isset($subjects[$onlineexams[$onlineexam_user_status->exam_id]->subjectID])) {
		                                    				echo $subjects[$onlineexams[$onlineexam_user_status->exam_id]->subjectID]->subject;
		                                    			}
		                                    		}
		                                    	?>
		                                    </td>
	                                    <?php } ?>

	                                    <td data-title="<?=$this->lang->line('onlineexamreport_datetime')?>">
	                                    	<?=date('d M Y - h:i:s', strtotime($onlineexam_user_status->uploaded_date))?>
	                                    </td>
	                                    <td data-title="asset"><?php echo $onlineexam_user_status->asset; ?></td>
	                                    <td data-title="<?=$this->lang->line('action')?>">
                                    		<a class="btn btn-success btn-sm" target="_blank" href="<?php echo base_url(); ?>assets/answer-pdf/<?php echo $onlineexam_user_status->asset; ?>">Download</a>
	                                    </td>

                                        
                                   </tr>
                                <?php $i++; } ?>
                                

	                        </table>
	                    </div>
                    <?php } else { ?>
	                    <div class="callout callout-danger">
	                        <p><b class="text-info"><?=$this->lang->line('onlineexamreport_data_not_found')?></b></p>
	                    </div>
	                <?php } ?>

                </div>

            </div>
        </div>


    </div>
</div>
