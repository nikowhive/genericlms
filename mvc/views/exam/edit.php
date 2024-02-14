<div class="container container--sm">

<header class="pg-header mt-4">
        <div>
            
            <h1 class="pg-title">
            
                <?=$this->lang->line('panel_title')?>
                </h1>
                <ol class="breadcrumb">
                <li><a href="<?=base_url("dashboard/index")?>"><?=$this->lang->line('menu_dashboard')?></a></li>
                <li><a href="<?=base_url("exam/index")?>"><?=$this->lang->line('menu_exam')?></a></li>
                <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_exam')?></li>
            </ol>
        </div>
</header>
    <div class="card card--spaced">
 
        <!-- form start -->
        <div class="card-body">
            
                    <form class="" role="form" method="post" id="edit_exam">
                    <?php
                            if(form_error('is_final_term'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                        ?>
                            <div class="md-form">
                            <?=$this->lang->line("exam_is_final_term")?>&nbsp;&nbsp;
                            <input type="checkbox" <?php echo $exam->is_final_term == 1?'checked':'' ?>  placeholder=" " class="" id="is_final_term" name="is_final_term">
                            <span class="text-danger error">
                                <?php echo form_error('is_final_term'); ?>
                            </span>
                        </div>
                        </div>
                        <?php
                            if(form_error('exam'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                        ?>
                            <div class="md-form">
                            <label for="exam" class="control-label">
                                <?=$this->lang->line("exam_name")?> <span class="text-red">*</span>
                            </label>
                                <input type="text" class="form-control" id="exam" name="exam" value="<?=set_value('exam', $exam->exam)?>" >
                            <span class="text-danger error">
                                <?php echo form_error('exam'); ?>
                            </span>
                            </div>
                        </div>
    
                        <?php
                            if(form_error('date'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                        ?>
                            <div class="md-form">
                            <label for="date" class="control-label">
                                <?=$this->lang->line("exam_date")?> <span class="text-red">*</span>
                            </label>
                                <input type="text" class="form-control" id="date" name="date" value="<?=set_value('date', date("d-m-Y", strtotime($exam->date)))?>" >
                            <span class="text-danger error">
                                <?php echo form_error('date'); ?>
                            </span>
                            </div>
                        </div>
    
                        <?php
                            if(form_error('note'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                        ?>
                            <div class="md-form">
                                <textarea style="resize:none;" class="form-control md-textarea" id="note" name="note"><?=set_value('note', $exam->note)?></textarea>
                                <label for="note" class="control-label">
                                    <?=$this->lang->line("exam_note")?>
                                </label>
                            <span class="text-danger error">
                                <?php echo form_error('note'); ?>
                            </span>
                            </div>
                        </div>
    
                        <?php
                            if(form_error('issue_date'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                        ?>
                            <div class="md-form">
                            <label for="issue_date" class="control-label">
                                <?=$this->lang->line("exam_issue_date")?> <span class="text-red">*</span>
                            </label>
                                <input type="text" class="form-control" placeholder=" " id="issue_date" name="issue_date" value="<?=set_value('issue_date', $exam->issue_date)?>" >
                            <span class="text-danger error">
                                <?php echo form_error('issue_date'); ?>
                            </span>
                            </div>
                        </div>  

                        <?php
                            if(form_error('order_no'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                        ?>
                            <div class="md-form">
                            <label for="issue_date" class=" control-label">
                                <?=$this->lang->line("exam_order_number")?> <span class="text-red">*</span>
                            </label>
                                <input type="number" class="form-control" id="order_no" name="order_no" placeholder=" " value="<?=set_value('order_no', $exam->order_no)?>" >
                            <span class="text-danger error">
                                <?php echo form_error('order_no'); ?>
                            </span>
                            </div>
                        </div>                      

                        <button type="submit" class="btn btn-success"><?=$this->lang->line("update_exam")?></button>
    
                        
    
                    </form>
                
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#date").datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearsessionobj->startingdate?>',
        endDate:'<?=$schoolyearsessionobj->endingdate?>',
    });


    $(function(){
        issue_date = $('#issue_date').val();
        if(issue_date != 0) {
            date = issue_date.split("-");
            console.log(date);
            converted_nepali_date = calendarFunctions.bsDateFormat("%y-%m-%d", parseInt(date[0]), parseInt(date[1]), parseInt(date[2]))
            $('#issue_date').val(converted_nepali_date);
        }

        $('#issue_date').nepaliDatePicker({
            dateFormat: "%y-%m-%d",
            closeOnDateSelect: true,
            // minDate: 'सोम, जेठ १०, २०७३',
            // maxDate: 'मंगल, जेठ ३२, २०७३'
        });
    });

    $('#edit_exam').one('submit', function(e) {
        e.preventDefault();
        issue_date = $('#issue_date').val();
        if(issue_date != 0) {
            parsed_date = calendarFunctions.parseFormattedBsDate("%y-%m-%d", issue_date)
            eng_date = parsed_date.bsYear+'-'+parsed_date.bsMonth+'-'+parsed_date.bsDate
            $('#issue_date').val(eng_date);
        }
        $(this).submit();
    });
</script>