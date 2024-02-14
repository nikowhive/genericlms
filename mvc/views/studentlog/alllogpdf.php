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
                        <h3>All Student Logs</h3>
                    </div><!-- /.box-header -->
                    <?php foreach($logs as $studentlogs){
                        echo '<p><b> Name: </b>'.$studentlogs[0]->name.'<b> Class: </b>'.$studentlogs[0]->classes.'</p>';
                        echo '<br>';
                        ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Event</th>
                                <th>Second Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                                $total = 0;
                                foreach($studentlogs as $log) { ?>
                                <tr>
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td><?=$log->event?></td>
                                    <td><?=$log->second_spent?></td>
                               </tr>
                            <?php $i++; 
                            $total = $total + $log->second_spent;
                        } ?>
                        <tr>
                            <td colspan="2" style="text-align:right;">Total</td>
                            <td><?php echo $total; ?></td>
                        </tr>
                        </tbody>
                    </table>
                    <?php 
                 echo '<br>';
                } ?>
                <?php } ?>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
   
    <div class="col-sm-12">
        <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
    </div>
       
</body>
</html>