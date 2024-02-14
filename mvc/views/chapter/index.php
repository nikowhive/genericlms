
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-subject"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_chapter')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <h5 class="page-header">
                    <?php if(permissionChecker('chapter_add')) { ?>
                        <a href="<?php echo base_url('chapter/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?= $this->lang->line('add_title')?>
                         </a>
                    <?php } ?>

                    <?php if($this->session->userdata('usertypeID') != 3) { ?>
                        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                            <?php
                                $array = array("0" => $this->lang->line("chapter_select_class"));
                                if(customCompute($classes)) {
                                    foreach ($classes as $classa) {
                                        $array[$classa->classesID] = $classa->classes;
                                    }
                                }
                                echo form_dropdown("classesID", $array, set_value("classesID", $set), "id='classesID' class='form-control select2'");
                            ?>
                        </div>
                    <?php } ?>
                </h5>

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('chapter_subject_name')?></th>
                                <th><?=$this->lang->line('chapter_unit')?></th>
                                <th><?=$this->lang->line('chapter_code')?></th>
                                <th><?=$this->lang->line('chapter_name')?></th>
                                <?php if(permissionChecker('chapter_edit') || permissionChecker('chapter_delete')) { ?>
                                <th><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if(customCompute($chapters)) {$i = 1; foreach($chapters as $chapter) { ?>
                                <tr>

                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('chapter_subject_name')?>">
                                        <?php echo $chapter->subject; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('chapter_unit')?>">
                                        <?php echo $chapter->unit; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('chapter_code')?>">
                                        <?php echo isset($chapter->chapter_code) && !empty($chapter->chapter_code)?$chapter->chapter_code:''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('chapter_name')?>">
                                        <?php echo $chapter->chapter_name; ?>
                                    </td>
                                    
                                    <?php if(permissionChecker('chapter_edit') || permissionChecker('chapter_delete')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <a href="<?php echo base_url(); ?>chapter/edit/<?php echo $chapter->id ?>" class="btn btn-warning btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>

                                       <a href="<?php echo base_url(); ?>chapter/delete/<?php echo $chapter->id ?>" onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" class="btn btn-danger btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
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


<script type="text/javascript">
    $('.select2').select2();
    $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('chapter/chapter_list')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>
