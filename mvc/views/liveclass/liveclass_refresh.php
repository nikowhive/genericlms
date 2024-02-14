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
               

            