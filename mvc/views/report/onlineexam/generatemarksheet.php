<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<?php if(!empty($rows))
{
    foreach($rows as $row)
    {
        $getExamid = $this->online_exam_user_status_m->getexamid($row->marksheet_id);
        
        $marksheetval = $this->online_exam_user_status_m->getmarks($row->userID,$row->classesID,$getExamid->tid);
?>  
    <div class="mainpdf" style="height: 822px;">
        <div style="border:1px solid #000;">
        <div class="header_info" style="margin-bottom: 25px;">
        <table cellpadding="0" cellspacing="0" width="100%">
                        <tbody>
                            <tr>
                                <td width="100" valign="top" align="center" style="padding-left: 0px;">
                                    <img src="<?=base_url('uploads/images/'.$siteinfos->photo)?>" width="100" height="100">
                                </td>

                                <td valign="top">
                                    <table cellpadding="0" cellspacing="0" width="100%">

                                        <tbody>
                                            <tr>
                                                <td valign="top" style="font-size: 50px; font-weight: bold; text-align: center; text-transform: uppercase;">
                                                 <?php echo $siteinfos->sname;?> </td>
                                            </tr>
                                            <tr>
                                                <td valign="top" style="font-size: 20px; font-weight: bold; text-align: center; text-transform: uppercase;">
                                                   <?php echo $siteinfos->address;?></td>
                                            </tr>
                                            <tr>
                                                <td valign="top" height="5"></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>


                            </tr>
                        </tbody>
                    </table>
        </div>
            <div style="margin-top:25px;">
            <table cellpadding="0" cellspacing="0" width="100%" class="denifittable" style="padding-top:20px;padding-left:80px;">
                                        <tbody>
                                            <tr>

                                                <th valign="top" style="text-align: center; text-transform: uppercase; font-size:12px;">
                                                    Name</th>

                                                <th valign="top" style="text-align: center; text-transform: uppercase; border-right:1px solid #999; font-size:12px;">
                                                    Roll Number</th>

                                            </tr>
                                            <tr>
                                                <td valign="top" style="text-transform: uppercase;text-align: center;font-size:12px;">
                                                <?php echo $row->name;?></td>
                                                <td valign="top" style="text-transform: uppercase;text-align: center;border-right:1px solid #999;font-size:12px;">
                                                <?php echo $row->roll;?></td>


                                            </tr>

                                            <tr>
                                                <td valign="top" colspan="5" style="text-align: center; padding-top:40px; text-transform: uppercase; border:0">

                                                    GRADESHEET: <?php echo @$row->title?></td>
                                            </tr>

                                        </tbody>
                                    </table>
            </div>
            <?php if(!empty($marksheetval))
                    {
                        $i=1;
                    ?>
        <div style="border:1px solid #ddd; margin-top:10px; padding:10px;"> 
        <table cellpadding="0" cellspacing="0" width="100%" class="denifittable" style="text-align: center; text-transform: uppercase;">
                        <tbody>
                            <tr>
                                <th valign="middle" width="8%" style="font-size:12px;">S.N</th>
                                <th valign="middle" width="35%" style="font-size:12px;">Subjects</th>
                                <th valign="middle" style="text-align: center;font-size:12px;">Total Marks</th>
                                <th valign="middle" style="text-align: center;font-size:12px;">Pass Marks</th>
                                <th valign="top" style="text-align: center;font-size:12px;">Obtained Marks</th>
                                <th valign="top" style="text-align: center;font-size:12px;">Grade</th>
                                <th valign="top" style="text-align: center;font-size:12px;">GPA</th>
                                <th valign="middle" style="border-right:1px solid #ddd; text-align: center;font-size:12px;">Remarks
                                </th>
                            </tr>
                            <?php 
                          $totalprecentage = 0;
                          $finalmark = 0;
                          $passmark = 0 ;
                          $totalObtainedMark = 0;
                          $gradepoint = 0;
                          foreach($marksheetval as $marksheet)
                          {
                            $grd = $this->online_exam_user_status_m->getgpa($marksheet->totalPercentage);  
                            $totalprecentage +=$marksheet->totalPercentage;
                            $finalmark +=$marksheet->finalmark;
                            $passmark +=$marksheet->passmark;
                            $totalObtainedMark += $marksheet->totalObtainedMark;
                            $gradepoint += @$grd->grade_point;
                            
                         ?>
                            <tr>
                                <td valign="top" style="text-align: left;"><?php echo $i;?></td>
                                <td valign="top" style="text-align: left;"><?php echo $marksheet->subject;?></td>
                                <td valign="top" style="text-align: center;"><?php echo $marksheet->finalmark;?></td>
                                <td valign="top" style="text-align: center;"><?php echo $marksheet->passmark;?></td>
                                <td valign="top" style="text-align: center;"><?php echo $marksheet->totalObtainedMark?></td>
                                <td valign="top" style="text-align: center;"><?php echo @$grd->grade_point!=''?@$grd->grade_point:'-';?></td>
                                <td valign="top" style="text-align: center;"><?php echo @$grd->grade!=''?@$grd->grade:'-';?></td>
                                <td valign="top" style="text-align: center;border-right:1px solid #ddd;"><?php echo @$grd->description!=''?@$grd->description:'Non Graded';?></td>
                            </tr>
                            <?php $i++; }
                          $sumpercentage = round($totalprecentage/count($marksheetval),2);
                          $sumgrd = $this->online_exam_user_status_m->getgpa($sumpercentage);
                          ?>
                            <tr>
                            <td valign="top"></td>
                                <td valign="top" colspan="0" style="border-left:0">GRAND TOTAL</td>
                                <td valign="top"><?php echo @$finalmark;?></td>
                                <td valign="top" style="text-align: center;"><?php echo @$passmark;?></td>
                                <td valign="top" style="text-align: center;"><?php echo @$totalObtainedMark;?></td>
                                <td valign="top" style="text-align: center;"><?php echo @$gradepoint;?></td>
                                <td valign="top" style="text-align: center;"><?php echo @$sumgrd->grade!=''?$sumgrd->grade:'-';?></td>
                                <td valign="top" style="text-align: center;border-right:1px solid #ddd"></td>
                            </tr>
                        </tbody>
                    </table>
        </div><!-- result -->
        <?php } ?>
       <div style="padding-top:20px;padding-left:10px;">Result: <?php echo round($totalprecentage/count($marksheetval),2)<=40?'Fail':'Pass'; ?></div>
       <div style="padding-top:10px;padding-left:10px;">Date: <?php echo date('d/m/Y');?></div>

       <div style="border-bottom:1px solid #ddd; margin-top:25px; margin-left:10px; margin-right:10px;"> 
       <table cellpadding="0" cellspacing="0" width="100%" class="">
           <tbody>
               <tr>
                   <td valign="bottom" style="font-size: 12px;">
                </td>
                <td valign="bottom" align="center" style="text-transform: uppercase;">
                <p>Teacher's signature</p>
            </td>
            <td valign="bottom" align="center" style="text-transform: uppercase;">
            <p>Principal's signature</p>
        </td>
        </tr>
        </tbody>
        </table>
       </div>
       <div style="margin-top:25px;font-weight: bold; padding-left:10px;">
       Mark Range
       </div>
       <div style="padding:10px;">
       <table cellpadding="0" cellspacing="0" width="100%" class="denifittable" style="text-align: center; text-transform: uppercase;">
                        <tbody>
                            <tr>
                                <th valign="middle" style="font-size:12px;">Range</th>
                                <th valign="middle" style="font-size:12px;">90+</th>
                                <th valign="middle" style="text-align: center;font-size:12px;">80 - 90</th>
                                <th valign="middle" style="text-align: center;font-size:12px;">70- 80</th>
                                <th valign="top" style="text-align: center;font-size:12px;">60 - 70</th>
                                <th valign="top" style="text-align: center;font-size:12px;">50 to 60</th>
                                <th valign="top" style="text-align: center;font-size:12px;">40 to 50</th>
                                <th valign="top" style="text-align: center;font-size:12px;">20 to 40</th>
                                <th valign="top" style="text-align: center;font-size:12px;">1 to 20</th> 
                                <th valign="top" style="text-align: center;border-right:1px solid #999;font-size:12px;">0</th>
                            </tr>

                            <tr>
                                <td valign="top" style="text-align: center;">Grade</td>
                                <td valign="top" style="text-align: center;">A+</td>
                                <td valign="top" style="text-align: center;">A</td>
                                <td valign="top" style="text-align: center;">B+</td>
                                <td valign="top" style="text-align: center;">B</td>
                                <td valign="top" style="text-align: center;">C+</td>
                                <td valign="top" style="text-align: center;">C</td>
                                <td valign="top" style="text-align: center;">D</td>
                                <td valign="top" style="text-align: center;">E</td>
                                <td valign="top" style="text-align: center;border-right:1px solid #999">N</td>

                            </tr>
                            <tr>
                                <td valign="top" style="text-align: center;">GPA</td>
                                <td valign="top" style="text-align: center;">4.0</td>
                                <td valign="top" style="text-align: center;">3.6</td>
                                <td valign="top" style="text-align: center;">3.2</td>
                                <td valign="top" style="text-align: center;">2.8</td>
                                <td valign="top" style="text-align: center;">2.4</td>
                                <td valign="top" style="text-align: center;">2.0</td>
                                <td valign="top" style="text-align: center;">1.6</td>
                                <td valign="top" style="text-align: center;">0.8</td>
                                <td valign="top" style="text-align: center;border-right:1px solid #999">0.0</td>
                            </tr>
                        </tbody>
                    </table>
        </div>
    </div><!-- row -->
<div style="margin-bottom: 20px;">&nbsp;</div>
<?php } } ?>
</body>
</html>