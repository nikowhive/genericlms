<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-sitemap"></i> Add Unit</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">

                    <?php
                    if (form_error('unit_id'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                    <label for="unit_id" class="col-sm-2 control-label">
                        Unit Name <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">

                        <?php
                        $array = array();
                        $array[0] = 'Unit Name';

                        foreach ($units as $unit) {
                            $array[$unit->id] = $unit->unit_name;
                        }
                        echo form_dropdown("unit_id", $array, set_value("unit_id"), "id='unit_id' class='form-control select2'");
                        ?>
                    </div>
                    <span class="col-sm-4 control-label">
                        <?php echo form_error('unit_id'); ?>
                    </span>
            </div>

            <?php
            if (form_error('chapter_id'))
                echo "<div class='form-group has-error' >";
            else
                echo "<div class='form-group' >";
            ?>
            <label for="chapter_id" class="col-sm-2 control-label">
                Chapter Name <span class="text-red">*</span>
            </label>
            <div class="col-sm-6">

                <?php
                $array = array();
                $array[0] = 'Chapter Name';
                echo form_dropdown("chapter_id", $array, set_value("subject_id"), "id='chapter_id' class='form-control select2'");
                ?>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('chapter_id'); ?>
            </span>
        </div>


        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
                <input type="submit" class="btn btn-success" value="Add Units">
            </div>
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

    $(document).ready(function() {
        $("#unit_id").change(function() {
            var id = $(this).val();
            if (parseInt(id)) {
                if (id === '0') {
                    $('#chapter_id').val(0);
                } else {
                    $.ajax({
                        type: 'POST',
                        url: "<?= base_url('courses/getChapters') ?>",
                        data: {
                            "id": id
                        },
                        dataType: "html",
                        success: function(data) {
                            $('#chapter_id').html(data);
                        }
                    });
                }
            }
        });
    });
</script>