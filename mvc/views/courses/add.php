
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-sitemap"></i> <?=$this->lang->line('panel_title')?></h3>


    
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    
                    <?php
                        if(form_error('class_id'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="class_id" class="col-sm-2 control-label">
                            <?=$this->lang->line("classes_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">

                            <?php
                                $array = array();
                                $array[0] = $this->lang->line("classes_name");

                                foreach ($classes as $class) {
                                    $array[$class->classesID] = $class->classes;
                                }   
                                echo form_dropdown("class_id", $array, set_value("class_id"), "id='class_id' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('class_id'); ?>
                        </span>
                        </div>


                        <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8" id="section">
                            
                        </div>
                        </div>
                        
                        <?php
                        if(form_error('subject_id'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subject_id" class="col-sm-2 control-label">
                            <?=$this->lang->line("subject_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">

                            <?php
                                $array = array(); 
                                echo form_dropdown("subject_id", $array, set_value("subject_id"), "id='subject_id' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subject_id'); ?>
                        </span>
                        </div>
                    

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="Add Courses" >
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>


$( ".select2" ).select2( { placeholder: "", maximumSelectionSize: 6 } );

$(document).ready(function() {
    $("#class_id").change(function() {
        var id = $(this).val();
        if(parseInt(id)) {
            if(id === '0') {
                $('#sectionID').val(0);
            } else {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('online_exam/getSections')?>",
                    data: {"id" : id},
                    dataType: "html",
                    success: function(data) {
                        $('#section').html(data);
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('online_exam/getSubject')?>",
                    data: {"classID" : id},
                    dataType: "html",
                    success: function(data) {
                        $('#subject_id').html(data);
                    }
                });
            }
        }
    });
});
</script>
