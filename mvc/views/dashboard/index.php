<div class="row">
    <?php if(config_item('demo')) { ?>
        <div class="col-sm-12" id="resetDummyData">
            <div class="callout callout-danger">
                <h4>Reminder!</h4>
                <p>Dummy data will be reset in every <code>30</code> minutes</p>
            </div>
        </div>

        <script type="text/javascript"> 
            $(document).ready(function() {
                var count = 7;
                var countdown = setInterval(function(){
                    $("p.countdown").html(count + " seconds remaining!");
                    if (count == 0) {
                        clearInterval(countdown);
                        $('#resetDummyData').hide();
                    }
                    count--;
                }, 1000);
            });
        </script>
    <?php } ?>

    <?php if((config_item('demo') === FALSE) && ($siteinfos->auto_update_notification == 1) && ($versionChecking != 'none')) { ?>
        <?php if($this->session->userdata('updatestatus') === null) { ?>
            <!-- <div class="col-sm-12" id="updatenotify">
                <div class="callout callout-success">
                    <h4>Dear Admin</h4>
                    <p>INIlabs school management system has released a new update.</p>
                    <p>Do you want to update it now <?=config_item('ini_version')?> to <?=$versionChecking?> ?</p>
                    <a href="<?=base_url('dashboard/remind')?>" class="btn btn-danger">Remind me</a>
                    <a href="<?=base_url('dashboard/update')?>" class="btn btn-success">Update</a>
                </div>
            </div> -->
        <?php } ?> 
    <?php } ?>

    <?php
        $arrayColor = array(
            'bg-orange-dark',
            'bg-teal-light',
            'bg-pink-light',
            'bg-purple-light'
        );

        function allModuleArray($usertypeID='1', $dashboardWidget) {
          $userAllModuleArray = array(
            $usertypeID => array(
                'student'   => $dashboardWidget['students'],
                'classes'   => $dashboardWidget['classes'],
                'teacher'   => $dashboardWidget['teachers'],
                'parents'   => $dashboardWidget['parents'],
                'subject'   => $dashboardWidget['subjects'],
                'book'     => $dashboardWidget['books'],
                'feetypes'  => $dashboardWidget['feetypes'],
                'lmember'   => $dashboardWidget['lmembers'],
                'event'     => $dashboardWidget['events'],
                'issue'     => $dashboardWidget['issues'],
                'holiday'   => $dashboardWidget['holidays'],
                'invoice'   => $dashboardWidget['invoices'],
                'result'    => $dashboardWidget['results'],
            )
          );
          return $userAllModuleArray;
        }

        $userArray = array(
            '1' => array(
                'student'   => $dashboardWidget['students'],
                'teacher'   => $dashboardWidget['teachers'],
                'parents'   => $dashboardWidget['parents'],
                'subject'   => $dashboardWidget['subjects']
            ),
            '2' => array(
                'student'   => $dashboardWidget['students'],
                'teacher'   => $dashboardWidget['teachers'],
                'classes'   => $dashboardWidget['classes'],
                'subject'   => $dashboardWidget['subjects'],
            ),
            '3' => array(
                'teacher'   => $dashboardWidget['teachers'],
                'subject'   => $dashboardWidget['subjects'],
                'issue'     => $dashboardWidget['issues'],
                'invoice'    => $dashboardWidget['invoices'],
            ),
            '4' => array(
                'teacher'   => $dashboardWidget['teachers'],
                'book'     => $dashboardWidget['books'],
                'event'     => $dashboardWidget['events'],
                'invoice'   => $dashboardWidget['invoices'],
            ),
            '5' => array(
                'teacher'   => $dashboardWidget['teachers'],
                'parents'   => $dashboardWidget['parents'],
                'feetypes'  => $dashboardWidget['feetypes'],
                'invoice'   => $dashboardWidget['invoices'],
            ),
            '6' => array(
                'teacher'   => $dashboardWidget['teachers'],
                'lmember'   => $dashboardWidget['lmembers'],
                'book'      => $dashboardWidget['books'],
                'issue'     => $dashboardWidget['issues'],
            ),
            '7' => array(
                'teacher'       => $dashboardWidget['teachers'],
                'event'         => $dashboardWidget['events'],
                'holiday'       => $dashboardWidget['holidays'],
                'visitorinfo'  => $dashboardWidget['visitors'],
            ),
        );

        $generateBoxArray = array();
        $counter = 0;
        $getActiveUserID = $this->session->userdata('usertypeID');
        $getAllSessionDatas = $this->session->userdata('master_permission_set');
        foreach ($getAllSessionDatas as $getAllSessionDataKey => $getAllSessionData) {
            if($getAllSessionData == 'yes') {
                if(isset($userArray[$getActiveUserID][$getAllSessionDataKey])) {
                    if($counter == 4) {
                      break;
                    }

                    $generateBoxArray[$getAllSessionDataKey] = array(
                        'icon' => $dashboardWidget['allmenu'][$getAllSessionDataKey],
                        'color' => $arrayColor[$counter],
                        'link' => $getAllSessionDataKey,
                        'count' => $userArray[$getActiveUserID][$getAllSessionDataKey],
                        'menu' => $dashboardWidget['allmenulang'][$getAllSessionDataKey],
                    );
                    $counter++;
                }

            }
        }

        $icon = '';
        $menu = '';
        if($counter < 4) {
            $userArray = allModuleArray($getActiveUserID, $dashboardWidget);
            foreach ($getAllSessionDatas as $getAllSessionDataKey => $getAllSessionData) {
                if($getAllSessionData == 'yes') {
                    if(isset($userArray[$getActiveUserID][$getAllSessionDataKey])) {
                        if($counter == 4) {
                            break;
                        }

                        if(!isset($generateBoxArray[$getAllSessionDataKey])) {
                            $generateBoxArray[$getAllSessionDataKey] = array(
                                'icon' => $dashboardWidget['allmenu'][$getAllSessionDataKey],
                                'color' => $arrayColor[$counter],
                                'link' => $getAllSessionDataKey,
                                'count' => $userArray[$getActiveUserID][$getAllSessionDataKey],
                                'menu' => $dashboardWidget['allmenulang'][$getAllSessionDataKey]
                            );
                            $counter++;
                        }
                    }
                }
            }
        }

        if($this->session->userdata('usertypeID') == 1 || $this->session->userdata('usertypeID') == 2) {
        if(customCompute($generateBoxArray)) { foreach ($generateBoxArray as $generateBoxArrayKey => $generateBoxValue) { ?>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box ">
                    <a class="small-box-footer <?=$generateBoxValue['color']?>" href="<?=base_url($generateBoxValue['link'])?>">
                        <div class="icon <?=$generateBoxValue['color']?>" style="padding: 9.5px 18px 6px 18px;">
                            <i class="fa <?=$generateBoxValue['icon']?>"></i>
                        </div>
                        <div class="inner ">
                            <h3 class="text-white">
                                <?=$generateBoxValue['count']?>
                            </h3 class="text-white">
                            <p class="text-white">
                                <?=$this->lang->line('menu_'.$generateBoxValue['menu'])?>
                            </p>
                        </div>
                    </a>
                </div>
            </div>
    <?php } } } ?>
</div>

<?php if($getActiveUserID == 1 || $getActiveUserID == 5) { ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-body" style="padding: 0px;">
                    <div id="earningGraph"></div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if($getActiveUserID == 1 || $getActiveUserID == 5) { ?>
    <div class="row">
        <div class="col-sm-4">
            <?php $this->load->view('dashboard/ProfileBox'); ?>
        </div>
        <div class="col-sm-8">
            <div class="box">
                <div class="box-body" style="padding: 0px;">
                    <div id="attendanceGraph"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?php if(permissionChecker('notice')) { ?>
        <div class="col-sm-6">
            <?php $this->load->view('dashboard/NoticeBoard', array('val' => 5, 'length' => 15, 'maxlength' => 45)); ?>
        </div>
        <?php } ?>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-body" style="padding: 0px;">
                    <div id="visitor"></div>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <?php if($getActiveUserID == 2) { ?>
    <div class="row">
        <div class="col-sm-4">
            <?php $this->load->view('dashboard/ProfileBox'); ?>
        </div>
        <?php if(permissionChecker('notice')) { ?>
        <div class="col-sm-8">
            <div class="box">
                <div class="box-body" style="padding: 0px;height: 320px">
                    <?php $this->load->view('dashboard/NoticeBoard', array('val' => 5, 'length' => 20, 'maxlength' => 70)); ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
<?php } else { ?>
    <div class="row">
        <div class="col-sm-4">
            <?php $this->load->view('dashboard/ResultBox'); ?>
        </div>
        <?php if(permissionChecker('notice')) { ?>
        <div class="col-sm-8">
            <div class="box">
                <div class="box-body" style="padding: 0px;height: 320px">
                    <?php $this->load->view('dashboard/NoticeBoard', array('val' => 5, 'length' => 20, 'maxlength' => 70)); ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
<?php }} ?>

<div class="row">
  <div class="col-sm-12">
      <div class="box">
          <div class="box-body">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
          </div>
      </div>
  </div>
</div><!-- /.row -->
<?php if($getActiveUserID == 3) { ?>
    <div class="row">
        <div class="col-sm-6">
            <div class="box">
                <div class="box-body" style="padding: 0px;">
                    <figure class="highcharts-figure">
                        <div id="container1"></div>
                        <p class="highcharts-description">
                           <!--  Basic line chart showing trends in a dataset. This chart includes the
                            <code>series-label</code> module, which adds a label to each line for
                            enhanced readability. -->
                        </p>
                    </figure>
                    <script>
                    Highcharts.chart('container1', {

                        title: {
                            text: 'Name:<?=$studentName?>  Roll No.:<?=$roll_no?> Class:<?=$classesName?> <?=$sectionName?>'
                        },

                        // subtitle: {
                        //     text: 'Source: thesolarfoundation.com'
                        // },

                        yAxis: {
                            min: 0,
                            max: 4,
                            title: {
                                text: 'CGPA'
                            }
                        },

                        xAxis: {
                            // accessibility: {
                            //     rangeDescription: 'Range: 2010 to 2017'
                            // }
                            categories: <?=$exams?>
                        },

                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'middle'
                        },

                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: true
                                },
                                enableMouseTracking: false
                            }
                        },

                        series: <?=$gpaarray1?>,

                        responsive: {
                            rules: [{
                                condition: {
                                    maxWidth: 500
                                },
                                chartOptions: {
                                    legend: {
                                        layout: 'horizontal',
                                        align: 'center',
                                        verticalAlign: 'bottom'
                                    }
                                }
                            }]
                        }

                    });
                    </script>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-body" style="padding: 0px;">
                    <figure class="highcharts-figure">
                    <div id="container"></div>
                    <p class="highcharts-description">
                       
                    </p>
                </figure>

                <script>
                    Highcharts.chart('container', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Name:<?=$studentName?>  Roll No.:<?=$roll_no?> Class:<?=$classesName?> <?=$sectionName?>'
                    },
                    // subtitle: {
                    //     text: 'Source: <a href="https://en.wikipedia.org/wiki/World_population">Wikipedia.org</a>'
                    // },
                    xAxis: {
                        categories: <?=$subject?>,
                        title: {
                            text: null
                        }
                    },
                    yAxis: {
                        min: 0,
                        max: 4,
                        title: {
                            text: 'Gpa',
                            align: 'high'
                        },
                        labels: {
                            overflow: 'justify'
                        }
                    },
                    tooltip: {
                        valueSuffix: ' Gpa'
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                enabled: true
                            }
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'top',
                        x: -40,
                        y: 80,
                        floating: true,
                        borderWidth: 1,
                        backgroundColor:
                            Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                        shadow: true
                    },
                    credits: {
                        enabled: false
                    },
                    series: <?=$gpaarray?>
                });
                </script>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php
    if($attendanceSystem != 'subject') {
        $this->load->view("dashboard/AttendanceHighChartJavascript");
    } else {
        $this->load->view("dashboard/SubjectWiseAttendanceHighChartJavascript");
    }
?>
<?php $this->load->view("dashboard/EarningHighChartJavascript.php"); ?>
<?php $this->load->view("dashboard/CalenderJavascript"); ?>
<?php $this->load->view("dashboard/VisitorHighChartJavascript"); ?>


<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p>We'd like to send you notifications for the notices and events.</p>
                <button type="submit" class="btn btn-primary" id="push-subscription-button">Allow</button>
                <button class="btn btn-default" data-dismiss="modal"  id="cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
     $("#cancel").click(function(){
        localStorage.setItem("cancel", 1);
    });

$('#push-subscription-button').submit(function() {
    $('#push-subscription-button').modal('hide');
    return false;
});
</script>