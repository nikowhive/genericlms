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

                    <?php if(customCompute($teachers)) { ?>
                        <div class="box-header bg-gray">
                            <h3>Teachers</h3>
                        </div><!-- /.box-header -->
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>DOB</th>
                                <th>Sex</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                                $flag = 0;
                                foreach($teachers as $teacher) { ?>
                                <tr>
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?=profileimage($teacher->photo,56)?>
                                    </td>
                                    <td><?=$teacher->name?></td>
                                    <td><?=$teacher->designation?></td>
                                    <td><?=$teacher->dob?></td>
                                    <td><?=$teacher->sex?></td>
                                    <td><?=$teacher->email?></td>
                                    <td><?=$teacher->phone?></td>
                                    <td><?=$teacher->address?></td>
                               </tr>
                            <?php $i++; } ?>
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