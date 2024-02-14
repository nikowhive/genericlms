<?php
$this->load->model("sattendance_m");
?>
<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
             $pdfurl = 'attendancereport/pdfAllClass/' . $typeSortForm . '/' . strtotime($date);
        //   $xmlurl = 'attendancereport/xlsx/' . $typeSortForm . '/' . $classesID . '/' . $sectionID . '/' . strtotime($date);

         $pdf_preview_uri = base_url($pdfurl);
        //  $xml_preview_uri = base_url($xmlurl);

        echo btn_printReport('attendancereport', $this->lang->line('report_print'), 'printablediv');
        echo btn_pdfPreviewReport('attendancereport', $pdf_preview_uri, $this->lang->line('report_pdf_preview'));
        // echo btn_xmlReport('attendancereport', $xml_preview_uri, $this->lang->line('report_xlsx'));
        // echo btn_sentToMailReport('attendancereport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>

<div class="box">
    <!-- form start -->
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?= $this->lang->line('attendancereport_report_for') ?> <?= $this->lang->line('attendancereport_attendance') ?> ( <?= date('d M Y', strtotime($date)) ?> ) </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <?= reportheader($siteinfos, $schoolyearsessionobj) ?>
                </div>
                <div class="col-sm-12">
                    <br></br>
                    <div class="row">
                         <div class="col-md-3"><h5>Total Student: <?=$totalStudent; ?></h5></div>
                         <div class="col-md-3"><h5>Total Present: <?=$totalPresents; ?></h5></div>
                         <div class="col-md-3"><h5>Total Absent: <?=$totalAbsents; ?></h5></div>
                         <div class="col-md-3"><h5>Total Leave: <?=$totalLeaves; ?></h5></div>
                    </div>
                    <br></br>
                    <div class="row">
                          <?php if(customCompute($results)){ ?>
                                <?php foreach($results as $classname=>$sections){ ?>
                                      <div class="classWrapper" style="padding-bottom: 70px;border-bottom:1px solid #ccc;padding-top:20px;"> 
                                        <?php echo '<div class="col-sm-12"><h4 class="pull-left"><b>Class : '.$classname.'</b></h4></div>';
                                        foreach($sections as $sectionName=>$attendanceTypes){
                                            echo '<div class="col-sm-3"><h5 class="pull-left">Section : '.$sectionName.'</h5></div><div class="col-sm-3"><h5 class="pull-left">Total Student : '.current($attendanceTypes)['totalStudent'].'</h5></div>';
                                            foreach($attendanceTypes as $key=>$attendanceType){
                                                echo '<div class="col-sm-2"><h5 class="pull-left">Total '.$key.' : '.$attendanceType['value'].'</h5></div>';
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


