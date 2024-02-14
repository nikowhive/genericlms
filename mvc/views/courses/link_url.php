
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-sitemap"></i> Add Link</h3>


    
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <?php
                        if(form_error('bbb_url'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="bbb_url" class="col-sm-2 control-label">
                            <?=$this->lang->line("bbb_url")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="bbb_url" name="bbb_url" value="<?=set_value('bbb_url')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('bbb_url'); ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="Add Link" >
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
