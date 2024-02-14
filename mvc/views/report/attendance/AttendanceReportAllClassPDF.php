
<div class="box">
    <!-- form start -->
   
    <div id="printablediv">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <?= reportheader($siteinfos, $schoolyearsessionobj,true) ?>
                </div>
                <div class="box-header bg-gray">
                    <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?= $this->lang->line('attendancereport_report_for') ?> <?= $this->lang->line('attendancereport_attendance') ?> ( <?=date('d M Y',strtotime($date))?> )  </h3>
                </div><!-- /.box-header -->
                <div class="col-sm-12">
                    <br></br>
                    <div class="row">
                        <h5 class="pull-left" style="width:25%">Total Student: <?=$totalStudent; ?></h5>
                         <h5 class="pull-left" style="width:25%">Total Present: <?=$totalPresents; ?></h5>
                         <h5 class="pull-left" style="width:25%">Total Absent: <?=$totalAbsents; ?></h5>
                         <h5 class="pull-left" style="width:25%">Total Leave: <?=$totalLeaves; ?></h5>
                    </div>
                    <br></br>
                    <div class="row">
                          <?php if(customCompute($results)){ ?>
                                <?php foreach($results as $classname=>$sections){ ?>
                                      <div class="classWrapper" style="padding-bottom: 20px;border-bottom:1px solid #ccc;padding-top:20px;"> 
                                        <?php echo '<div class="col-sm-12"><h4 class="pull-left"><b>Class : '.$classname.'</b></h4></div>';
                                        foreach($sections as $sectionName=>$attendanceTypes){
                                            echo '<h5 class="pull-left" style="width:30%">Section : '.$sectionName.'</h5><h5 class="pull-left" style="width:50%">Total Student : '.current($attendanceTypes)['totalStudent'].'</h5>';
                                            foreach($attendanceTypes as $key=>$attendanceType){
                                                echo '<h5 class="pull-left" style="width:30%">Total '.$key.' : '.$attendanceType['value'].'</h5>';
                                            }
                                        } ?>
                                      </div>
                               <?php }
                              ?>
                          <?php } ?>
                    </div>
                </div>
                <div class="col-sm-12 text-center footerAll">
                    <?= reportfooter($siteinfos, $schoolyearsessionobj) ?>
                </div>
            </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>