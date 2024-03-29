
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-issue"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("issue/index/$set")?>"><?=$this->lang->line('menu_issue')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_issue')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">

                    <?php 
                        if(form_error('lid')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="lid" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_lid")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="lid" name="lid" value="<?=set_value('lid', $issue->lID)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('lid'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('student_name')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="student_name" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_student")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="student_name" name="student_name" readonly="readonly" value="<?=set_value('student_name', $library_member->name)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('student_name'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('student_roll_no')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="student_roll_no" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_student_roll_no")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="student_roll_no" name="student_roll_no" readonly="readonly" value="<?=set_value('student_roll_no', $library_member->roll)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('student_roll_no'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('student_class')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="student_class" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_student_class")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="student_class" name="student_class" readonly="readonly" value="<?=set_value('student_class', $library_member->classes)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('student_class'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('student_section')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="student_section" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_student_section")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="student_section" name="student_section" readonly="readonly" value="<?=set_value('student_section', $library_member->section)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('student_section'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('book')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="book" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_book")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                            foreach ($books as $book) {
                                    if($issue->bookID == $book->bookID) {
                                        $array[$book->bookID] = $book->book;
                                    }
                                }
                                echo form_dropdown("book", $array, set_value("book", $issue->bookID), "id='book' readonly class='form-control select2'");
                            ?>
                            
                        </div>

                        <span class="col-sm-4 control-label">
                            <?php echo form_error('book'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('author')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="author" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_author")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" readonly class="form-control" id="author" name="author" value="<?=set_value('author', $issue->author)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('author'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('subject_code')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subject_code" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_subject_code")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" readonly class="form-control" id="subject_code" name="subject_code" value="<?=set_value('subject_code', $bookinfo->subject_code)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subject_code'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('serial_no')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="serial_no" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_serial_no")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="serial_no" name="serial_no" value="<?=set_value('serial_no', $issue->serial_no)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('serial_no'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('issue_date')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="issue_date" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_issue_date")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                           <input type="text" class="form-control" id="issue_date" name="issue_date" value="<?=set_value('issue_date',$issue->issue_date)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('issue_date'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('due_date')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="due_date" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_due_date")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                           <input type="hidden" class="form-control" id="due_date" name="due_date" value="<?=set_value('due_date',$issue->due_date)?>" >
                            <input type="text" class="form-control" id="due_date_in_bs" name="due_date_in_bs" value="<?=set_value('due_date_in_bs',$issue->due_date_in_bs)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('due_date'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('note')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("issue_note")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="note" name="note" value="<?=set_value('note', $issue->note)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('note'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_issue")?>" >
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/neplaidatepicker/nepaliDatePicker.js'); ?>"></script>
<script src="<?php echo base_url('assets/nepalidatepicker/customNepaliDatepicker.js'); ?>"></script>

<script type="text/javascript">
$('#lid').change(function() {
    var library_memberID = $(this).val();
    if(library_memberID === '0') {
        $(this).val(0);
        $('#student_name').val(' ');
        $('#student_roll_no').val(' ');
        $('#student_class').val(' ');
        $('#student_section').val(' ');
    } else {
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "<?=base_url('issue/studentIDcall')?>",
            data: "library_memberID=" + library_memberID,
            dataType: "html",
            success: function(data) {
                var response = jQuery.parseJSON(data);
                if(response != "") {
                    $('#student_name').val(response.name);
                    $('#student_roll_no').val(response.roll);
                    $('#student_class').val(response.class);
                    $('#student_section').val(response.section);
                } else {
                    $('#student_name').val(' ');
                    $('#student_roll_no').val(' ');
                    $('#student_class').val(' ');
                    $('#student_section').val(' ');
                }
            }
        });
    }
});

</script>