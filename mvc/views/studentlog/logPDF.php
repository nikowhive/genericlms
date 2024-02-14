<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div class="col-sm-12">
        <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
    </div>
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
            <?php if(customCompute($logs)) { ?>
                <div class="box-header bg-gray">
                    <h3>Student Logs</h3>
                </div><!-- /.box-header -->
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Student</th>
                                <th>Event</th>
                                <th>Remarks</th>
                                <th>Start Datetime</th>
                                <th>End Datetime</th>
                                <th>Time Spent</th>
                                <th>Second Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                                $flag = 0;
                                $total = 0;
                                foreach($logs as $log) { ?>
                                <tr>
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td><?=$log->name?></td>
                                    <td><?=$log->event?></td>
                                    <td><?=$log->remarks?></td>
                                    <td><?=$log->start_datetime?></td>
                                    <td><?=$log->end_datetime?></td>
                                    <td><?=$log->time_spent?></td>
                                    <td><?=$log->second_spent?></td>
                               </tr>
                            <?php $i++; 
                             $total = $total + $log->second_spent;
                            } ?>
                            <tr>
                                <td colspan="7" style="text-align:right;">Total</td>
                                <td><?php echo $total; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php } ?>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
   
    <div class="col-sm-12">
        <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
    </div>
       
</body>
</html>