<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-sitemap"></i> Edit Quiz</h3>


    
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <?php
                        if(form_error('quiz_name'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="quiz_name" class="col-sm-2 control-label">
                            Percentage coverage <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="quiz_name" name="quiz_name" value="<?=set_value('quiz_name', $quiz_name)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('quiz_name'); ?>
                        </span>
                    </div>
                    <?php
                        if(form_error('percentage_coverage'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="percentage_coverage" class="col-sm-2 control-label">
                            Percentage coverage <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="percentage_coverage" name="percentage_coverage" value="<?=set_value('percentage_coverage', $percentage_coverage)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('percentage_coverage'); ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="Update" >
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
$( ".select2" ).select2( { placeholder: "", maximumSelectionSize: 6 } );
</script>
