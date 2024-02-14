

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-lbooks"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_books')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                    if(permissionChecker('book_add')) {
                ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('book/add') ?>">
                            <i class="fa fa-plus"></i> 
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>

                <div id="hide-table">
                    <table id="bookListTable" style="width:100%" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('book_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('book_author')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('book_call')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('book_publisher')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('book_edition')?></th>

                                <!-- <th class="col-sm-2"><?=$this->lang->line('book_subject_code')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('book_price')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('book_quantity')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('book_rack_no')?></th> -->
                                <th class="col-sm-1"><?=$this->lang->line('book_status')?></th>
                                <?php if(permissionChecker('book_edit') || permissionChecker('book_delete')) { ?>
                                <th class="col-sm-1"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript"> 
$(document).ready(function(){
    $('#bookListTable').DataTable({
       // Processing indicator
        "processing": true,
        // DataTables server-side processing mode
        "serverSide": true,
        // Initial no order.
        "order": [],
        // Load data from an Ajax source
        "ajax": {
            "url": "<?php echo base_url('book/getBooks'); ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false
        }],
        dom : 'Bfrtip',
        buttons : [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
              ],
    });
});
</script>
