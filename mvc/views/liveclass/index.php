<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-video-camera"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_liveclass')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if((($siteinfos->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1))) { ?>
                    <h5 class="page-header">
                        <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) { ?>
                            <?php if(permissionChecker('liveclass_add')) { ?>
                                <a href="<?=base_url('liveclass/add')?>">
                                    <i class="fa fa-plus"></i> 
                                    <?=$this->lang->line('add_title')?>
                                </a>
                            <?php } ?>
                        <?php } ?>
                    </h5>
                <?php } ?>

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-3"><?=$this->lang->line('liveclass_title')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('liveclass_date')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('liveclass_duration')?></th>
                                <th><?=$this->lang->line('liveclass_classes')?></th>
                                <th><?=$this->lang->line('liveclass_section')?></th>
                                <th><?=$this->lang->line('liveclass_subject')?></th>
                                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) { ?>
                                    <?php if(permissionChecker('liveclass_edit') || permissionChecker('liveclass_delete') || permissionChecker('liveclass_view')) { ?>
                                        <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody id="tableData">
                        <?php
    $time = (function($time) {
        if($time > 60) {
            $hours  = (int)($time/60);
            $minute = ($time%60);
            return lzero($hours) . ':' .lzero($minute) .' M';
        }
        return lzero($time) .' M';
    });


    $replace = (function($url) {
        return str_replace('http:', 'https:', $url);
    });
?>
                            <?php if(customCompute($liveclass)) { $i = 1; foreach($liveclass as $liveclassa) { 
                                if(strtotime($liveclassa->date) > strtotime(date('Y-m-d H:i:s', strtotime('- '.$liveclassa->duration.' minutes', strtotime(date('Y-m-d H:i:s')))))) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                     <?=$i?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('liveclass_title')?>">
                                        <?=$liveclassa->title?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('liveclass_date')?>">
                                        <?=date("d M Y h:i A", strtotime($liveclassa->date))?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('liveclass_duration')?>">
                                        <?=$time($liveclassa->duration)?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('liveclass_classes')?>">
                                        <?=isset($class[$liveclassa->classes_id]) ? $class[$liveclassa->classes_id] : ''?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('liveclass_section')?>">
                                        <?=isset($section[$liveclassa->section_id]) ? $section[$liveclassa->section_id] : ''?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('liveclass_subject')?>">
                                        <?=isset($subject[$liveclassa->subject_id]) ? $subject[$liveclassa->subject_id] : ''?>
                                    </td>
                                    <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) { ?>
                                        <?php if(permissionChecker('liveclass_edit') || permissionChecker('liveclass_delete') || permissionChecker('liveclass_view')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?=permissionChecker('liveclass_view') ? ((strtotime(date('Y-m-d H:i:s', strtotime('+ 10 minutes', strtotime(date('Y-m-d H:i:s'))))) >= strtotime($liveclassa->date)) ? $replace(btn_sm_global_new_tab($liveclassa->teacher_join_url, $this->lang->line('join'), 'fa fa-video-camera')) : '') : ''?>
                                            <?=btn_edit('liveclass/edit/'.$liveclassa->id, $this->lang->line('edit')) ?>
                                            <?=btn_delete('liveclass/delete/'.$liveclassa->id, $this->lang->line('delete')) ?>
                                        </td>
                                        <?php } ?>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }}} ?>
               
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        refreshData();
        function refreshData(){
            var searchValue = $('.input-sm').val();
            $.ajax({
                  type : 'GET',
                  url : "<?=base_url('liveclass/refreshliveclass')?>",
                  data: {'searchValue':searchValue},
                  success : function (data) {
                    $("#tableData").html(data);
                  }
            });
        }
        
        setInterval(function(){
            refreshData() // this will run after every 5 seconds
        }, 5000);

    });

</script>

