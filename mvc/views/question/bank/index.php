<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-qrcode"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                   if(permissionChecker('question_bank_add')) {
                ?>
                <h5 class="page-header">
                    <a href="<?php echo base_url('question_bank/add') ?>">
                        <i class="fa fa-plus"></i>
                        <?=$this->lang->line('add_title')?>
                    </a>
                    <a href="#" style="margin-left: 20px;" data-toggle="modal" data-target="#instruction">
                        <i class="fa fa-download"></i> Instructions for Bulk Upload</a>
                </h5>
                <p style="font-size:15px;">Select the class and subject to download the template.</p>

                <?php } ?>
                <form action="<?php echo base_url() ?>question_bank/download_excel">
                    <div class="row">
                        <div class="col-md-4">
                            <?php 
                                $array = array(NULL => "Select Class");
                                foreach ($classes as $group) {
                                    $array[$group->classesID] = $group->classes;
                                }
                                echo form_dropdown("class_id", $array, set_value("class_id"), "id='class_id' class='form-control select2' required");
                            ?>
                        </div>

                        <div class="col-md-4" id="ajax-get-subjects">
                            <?php
                                $array = array(NULL => 'Select Subject');
                                foreach ($subjects as $group) {
                                    $array[$group->subjectID] = $group->subject;
                                }
                                echo form_dropdown("subject_id", $array, set_value("subject_id"), "id='subject_id' class='form-control select2' required");
                            ?>
                        </div>

                        <div class="col-md-4">
                            <input type="submit" class="btn btn-success" value="Download">
                            <!-- <a href="#" style="margin-left: 20px;" download>
                                <i class="fa fa-download"></i> Download a Sample Excel file
                            </a> -->
                        </div>
                    </div>
                </form>
                <input type="hidden" id="ajax-get-subjects-url" value="<?php echo base_url() ?>subject/ajaxGetSubjectsFromClassId">

                    <form action="<?php echo base_url().'question_bank/upload_excel' ?>" style="font-size: 14px; margin-top: 40px;" method="post" enctype="multipart/form-data" id="question_upload">
                         <p  style="font-size:15px;">Upload Questions</p>
			 <label for="file" style="border: 1px solid;padding: 4px;cursor:pointer;border-radius: 3px;padding-bottom: 6px;">
                            <input type="file" id="file" name="excel" accept=".xls,.xlsx" required>
                        </label>
                        <input type="submit" class="btn btn-success single-click" value="Upload Questions">
                    </form>
                <?php if($this->session->prabal_error) { ?>
                    <p style="color: red;"><?php echo $this->session->prabal_error; ?></p>
                <?php } ?>


		</br>
		</br>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('question_bank_level')?></th>
                                <th class="col-sm-3"><?=$this->lang->line('question_bank_question')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('question_bank_group')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('question_bank_type')?></th>
                                <?php if(permissionChecker('question_bank_edit') || permissionChecker('question_bank_delete') || permissionChecker('question_bank_view')) { ?>
                                    <th class="col-sm-1"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($question_banks)) {$i = 1; foreach($question_banks as $question_bank) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('question_bank_level')?>">
                                        <?=isset($levels[$question_bank->levelID]) ? $levels[$question_bank->levelID]->name : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('question_bank_question')?>">
                                        <?php
                                            if(strlen($question_bank->question) > 60)
                                                echo substr(strip_tags($question_bank->question), 0, 60)."...";
                                            else
                                                echo strip_tags($question_bank->question);
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('question_bank_group')?>">
                                        <?=isset($groups[$question_bank->groupID]) ? $groups[$question_bank->groupID]->title : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('question_bank_type')?>">
                                        <?=isset($types[$question_bank->type_id]) ? $types[$question_bank->type_id]->name : ''; ?>
                                    </td>
                                    <?php if(permissionChecker('question_bank_edit') || permissionChecker('question_bank_delete') || permissionChecker('question_bank_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_view('question_bank/view/'.$question_bank->questionBankID, $this->lang->line('view')) ?>
                                            <?php echo btn_edit('question_bank/edit/'.$question_bank->questionBankID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('question_bank/delete/'.$question_bank->questionBankID, $this->lang->line('delete')) ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- The Modal -->
<div id="instruction" class="modal fade" tabindex="-1" role="dialog">

    <!-- Modal content -->
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Add new course</h3>
            </div>
            <div class="modal-body">
                <h3>Instructions</h3>

                <p style="font-size:18px">Data Validation Rules</p>

                <ul style="font-size:15px;">
                <li>Chapter are listed in the Rule of the following excel sheet.</li>
                <li>Question Group are listed in the Rule of this excel sheet.</li>
                <li>Difficulty Level are listed in the Rule of this excel sheet.</li>
                <li>Question Type are listed in the Rule of this excel sheet.</li>
                <li>Chapter, Question Group, Difficulty Level and Question Type are case sensitive.</li>
                </ul>

                <p style="font-size:18px">Correct Answer Rules</p>
                <ul style="font-size:15px;">
                <li>Correct Answer is the option number, if Option 1 is correct then type 1, if Option 5 is correct then type 5 in the Correct Answer column.</li>
                <li>For True False, if the answer is true, just type 't' as the correct answer as in example above, default is always false.</li>
                <li>For single right Answer, if single answer to be right, type just correct option number.</li>
                <li>For multiple right Answer, separate the answer with ';'.</li>
                </ul>
                <p style="color: red; font-size:15px;">*Don't update "Rules" sheet in generated excel file.</p>
                <p style="color: red; font-size:15px;">*Use following document as reference only. Download the template to add questions by choosing the subject and class.</p>
                <!-- <p style="font-size: 15px; text-decoration: underline;"><a href="/assets/excel/excel_demo.xlsx" target="_blank" download>Download a Sample Excel file</a></p> -->
            </div>
        </div>
    </div>

</div>

<script>
$(document).on('change', '#class_id', function() {
    let class_id = $(this).val();
    let url = $('#ajax-get-subjects-url').val()
    $('#subject_id').trigger('change');
    $.ajax({
      url: url + "?class_id=" + class_id,
    }).done(function( data ) {
        data = JSON.parse(data);
        $('#ajax-get-subjects').html(data.form);
    });
})

$('#question_upload').submit(function(){

// Disable the submit button      
$('.single-click').attr('disabled', true);

});
</script>