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
                                        <?=profileimage($student->photo,56)?>
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

                    <?php if(customCompute($parents)) { ?>
                        <div class="box-header bg-gray">
                            <h3>Parents</h3>
                        </div><!-- /.box-header -->
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                                $flag = 0;
                                foreach($parents as $parent) { ?>
                                <tr>
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?=profileimage($parent->photo,56)?>
                                    </td>
                                    <td><?=$parent->name?></td>
                                    <td><?=$parent->email?></td>
                                    <td><?=$parent->phone?></td>
                                    <td><?=$parent->address?></td>
                               </tr>
                            <?php $i++; } ?>
                        </tbody>
                    </table>
                    <?php } ?>

                    <?php if(customCompute($users)) { ?>
                        <div class="box-header bg-gray">
                            <h3>Users</h3>
                        </div><!-- /.box-header -->
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
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
                                foreach($users as $user) { ?>
                                <tr>
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?=profileimage($user->photo,56)?>
                                    </td>
                                    <td><?=$user->name?></td>
                                    <td><?=$user->dob?></td>
                                    <td><?=$user->sex?></td>
                                    <td><?=$user->email?></td>
                                    <td><?=$user->phone?></td>
                                    <td><?=$user->address?></td>
                               </tr>
                            <?php $i++; } ?>
                        </tbody>
                    </table>
                    <?php } ?>


                    <?php if(customCompute($systemadmins)) { ?>
                        <div class="box-header bg-gray">
                            <h3>System Admins</h3>
                        </div><!-- /.box-header -->
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
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
                                foreach($systemadmins as $systemadmin) { ?>
                                <tr>
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?=profileimage($systemadmin->photo,56)?>
                                    </td>
                                    <td><?=$systemadmin->name?></td>
                                    <td><?=$systemadmin->dob?></td>
                                    <td><?=$systemadmin->sex?></td>
                                    <td><?=$systemadmin->email?></td>
                                    <td><?=$systemadmin->phone?></td>
                                    <td><?=$systemadmin->address?></td>
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