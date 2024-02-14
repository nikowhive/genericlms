<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-pencil"></i> <?=$this->lang->line('panel_title')?></h3>
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
                                <th class="col-lg-3"><?=$this->lang->line('exam_name')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('exam_date')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('exam_note')?></th>
                                <th class="col-lg-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($exams)) {$i = 1; foreach($exams as $exam) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('exam_name')?>">
                                        <?php echo $exam->exam; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('exam_date')?>">
                                        <?php echo date("d M Y", strtotime($exam->date)); ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('exam_note')?>">
                                        <?php echo $exam->note; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php if($this->session->userdata('usertypeID') == 3) { ?>
                                            <a href="studentresult/view/<?=$exam->examID ?>" class="btn btn-primary btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="View Result"><i class="fa fa-eye"></i></a>
                                        <?php } else if($this->session->userdata('usertypeID') == 4) { ?>
                                            <a href="studentresult/students/<?=$exam->examID ?>" class="btn btn-primary btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="View Children"><i class="fa fa-child"></i></a>
                                        <?php } ?>
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
