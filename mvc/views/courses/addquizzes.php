<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-sitemap"></i> Add Quiz</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
            
                    <?php
                    if (form_error('quiz_name'))
                        echo "<div class='form-group mb-0 has-error' >";
                    else
                        echo "<div class='form-group mb-0' >";
                    ?>
                    <div class="col-sm-8">
                        <div class="md-form">
                            <label for="quiz_name">Title</label>
                            <input type="text" class="form-control" id="quiz_name" name="quiz_name" value="<?= set_value('quiz_name'); ?>">
                            <span class="text-danger error">
                                <?php echo form_error('quiz_name'); ?>
                            </span>
                        </div>
                    </div>
            </div>

            <?php
            if (form_error('percentage_coverage'))
                echo "<div class='form-group has-error' >";
            else
                echo "<div class='form-group' >";
            ?>

            <div class="col-sm-8">
                <div class="md-form">
                    <label for="percentage_coverage"> Percentage coverage </label>
                    <input type="number" class="form-control" id="percentage_coverage" name="percentage_coverage" value="<?= set_value('percentage_coverage'); ?>">

                    <span class="text-danger error">
                        <?php echo form_error('percentage_coverage'); ?>
                    </span>
                </div>
            </div>
        </div>
        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>"/>
        <input type="hidden" name="chapter_id" value="<?php echo $coursechapter_id; ?>"/>
        <input type="submit" class="btn btn-success" value="Add">
    </div>
    </form>

</div>
</div>
</div>
</div>

<script>
    $(".select2").select2({
        placeholder: "",
        maximumSelectionSize: 6
    });
</script>