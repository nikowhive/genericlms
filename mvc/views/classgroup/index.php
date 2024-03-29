<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-object-group"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_classgroup')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                   if(permissionChecker('classgroup_add')) {
                ?>
                <h5 class="page-header">
                    <a href="<?php echo base_url('classgroup/add') ?>">
                        <i class="fa fa-plus"></i>
                        <?=$this->lang->line('add_title')?>
                    </a>
                </h5>
                <?php } ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('classgroup_group')?></th>
                                <?php if(permissionChecker('classgroup_edit') || permissionChecker('classgroup_delete')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($classgroups)) {$i = 1; foreach($classgroups as $classgroup) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('classgroup_group')?>">
                                        <?=$classgroup->group?>
                                    </td>
                                        <?php if(permissionChecker('classgroup_edit') || permissionChecker('classgroup_delete')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit('classgroup/edit/'.$classgroup->classgroupID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('classgroup/delete/'.$classgroup->classgroupID, $this->lang->line('delete')) ?>
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