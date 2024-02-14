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
            <?php if(customCompute($students)) { ?>
                <div class="box-header bg-gray">
                    <h3>Students</h3>
                </div><!-- /.box-header -->
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>RegisterNO</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Roll</th>
                                <th>Blood Group</th>
                                <th>Country</th>
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
                                foreach($students as $student) { ?>
                                <tr>
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?=profileimage($student->photo,256)?>
                                    </td>
                                    <td><?=$student->name?></td>
                                    <td><?=$student->registerNO?></td>
                                    <td><?=$student->classes?></td>
                                    <td><?=$student->section?></td>
                                    <td><?=$student->roll?></td>
                                    <td><?=$student->bloodgroup?></td>
                                    <td><?=$student->country?></td>
                                    <td><?=$student->dob?></td>
                                    <td><?=$student->sex?></td>
                                    <td><?=$student->email?></td>
                                    <td><?=$student->phone?></td>
                                    <td><?=$student->address?></td>
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