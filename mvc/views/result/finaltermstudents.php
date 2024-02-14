<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-child"></i> My Children</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if(permissionChecker('exam_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('exam/add') ?>">
                            <i class="fa fa-plus"></i> 
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-lg-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-lg-3">Student Name</th>
                                <th class="col-lg-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($students)) {$i = 1; foreach($students as $student) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('exam_name')?>">
                                        <?php echo $student->name; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <a href="<?php echo base_url('result/finalreport/'.$examID.'/'.$student->studentID) ?>" class="btn btn-primary btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="View Result"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

            </div> <!-- col-sm-12 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->
