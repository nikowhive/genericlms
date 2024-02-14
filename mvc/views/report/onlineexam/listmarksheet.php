<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-qrcode"></i> Marksheet</h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Marksheet</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-header">
                    <a href="<?php echo base_url('onlineexamreport/marksheet') ?>">
                        <i class="fa fa-plus"></i>
                        Add New
                        </a>
                </h5>
         <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-1">Title</th>
                                <th class="col-sm-1">Class</th>
                                <th class="col-sm-3">Subject</th>
                                <th class="col-sm-1"><?=$this->lang->line('action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($lists)) {$i = 1; foreach($lists as $listsval) 
                            {
                                $terminalval = '';
                                $terminal = $this->online_exam_m->getsubject($listsval->id);
                                if(!empty($terminal))
                                {
                                    foreach($terminal as $terminalv)
                                    {
                                        $terminalval .=','.$terminalv->name;
                                    }

                                    $terminalval =  substr($terminalval,1);
                                }
                            ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>" style="width:4%">
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?=$listsval->title?>
                                    </td>
                                    <td>
                                      <?=$listsval->classes?>
                                    </td>
                                    <td>
                                       <?=$terminalval?>
                                    </td>
                                    <td>
                                    <a href="<?php echo base_url().'onlineexamreport/marksheetedit/'.$listsval->id?>" class="btn btn-warning btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>    
                                    <a href="<?php echo base_url().'onlineexamreport/marksheetdelete/'.$listsval->id?>" onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" class="btn btn-danger btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                                    <a href="<?php echo base_url().'onlineexamreport/generatemarksheet/'.$listsval->id?>" class="btn btn-success btn-xs mrg" target="_blank">Generate Marksheet</a>
                                    </td>
                                   
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>