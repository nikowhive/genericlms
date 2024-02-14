<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
 <?php if(customCompute($studentLists)) { foreach($studentLists as $x => $student) { ?>
    <div class="mainBorder">
    <div class="report-canvas" style="padding-top:<?php echo count($subjects) <= 6 ? '-16px' : '-40px'; ?>; padding:-16px;">
        <table class="school-info" align="center">
            <tr>
                <td valign="bottom" align="right" style="padding-top:20px; padding-right:-8px;">
                    <img src="<?=base_url("uploads/images/$siteinfos->photo")?>" height="100" alt=""/>
                </td>
                <td>
                    <table >
                        <tr>
                            <td align="center">
                                <table>
                                    <tr style= "text-align: center;">
                                     
                                    <td>
                                            <div><b>"A good education equips one for life"</b></div>
                                            <h1><b> <?=strtoupper($siteinfos->sname)?></b></h1>
                                            <div><b><?=$siteinfos->address?></b></div>
                                            <p>Ph:<?=$siteinfos->phone?>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
        
                        <tr>
                            
                            <td align="center">
                                <table>
                                   
                                    <tr>
                                       
                                        <td>
                                            <table class="student-detail trim-yaxis-td ">
                                                <tr>
                                                    <td align="left" style="padding-right: 50px;">
                                                        <p>Student Name : <b><?=$student->srname?></b>
                                                        </p>
                                                        
                                                        <?php if($student->parent_name): ?>
                                                            <p>Parent's Name : <?=$student->parent_name?></p>
                                                        <?php endif ?>
                                                    </td>
                                                    <td align="left" style="padding-right: 50px;">
                                                        <p>Class:
                                                            <?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : ''?>
                                                        </p>
                                                        <p>Section:
                                                            <?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID] : ''?>
                                                        </p>
                                                    </td>
                                                    <td align="left">
                                                        <p>Roll Number: <?=$student->srroll?></p>
                                                        <p>Admission No.: <?=$student->srregisterNO?></p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="grade-sheet" colspan="2">
                                GRADE SHEET: <?=strtoupper($examName)?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
 
        <table class="container">
            <tr>
                <td>
                    <table class="reports table-fluid" style="margin-left:8px; margin-right:8px">
                        <thead>
                            <tr>
                                <?php 
                                  $fullmark = $subjects[0]->finalmark;
                                  if(isset($subject_marks[$subjects[0]->subjectID]) && $subject_marks[$subjects[0]->subjectID] != '') {
                                      $fullmark = $subject_marks[$subjects[0]->subjectID];
                                  }
                                reset($markpercentagesArr);
                                $firstindex          = key($markpercentagesArr);
                                $uniquepercentageArr = isset($markpercentagesArr[$firstindex]) ? $markpercentagesArr[$firstindex] : [];
                                $markpercentages     = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                                
                                reset($markpercentagesArr1);
                                $firstindex1          = key($markpercentagesArr1);
                                $uniquepercentageArr1 = isset($markpercentagesArr1[$firstindex]) ? $markpercentagesArr1[$firstindex] : [];
                                $markpercentages1     = $uniquepercentageArr1[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                                                                                        
                                $count_markpercentages = count($markpercentages) + 2;
                                                         
                                if(customCompute($markpercentages)) {
                                    foreach($markpercentages as $markpercentageID) {
                                        $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                        if($markpercentage == 'Co-scholastic') {
                                            $count_markpercentages -= 1;
                                        }
                                    }
                                }


                                ?>
                                <th rowspan="2" style="width:5%;padding-top:10px;font-size:26px">S.N.</th>
                                <th rowspan="2" style="width:25%;padding-top:10px;font-size:26px">Subject</th>
                                <!-- <th rowspan="2">Total</th> -->
                                <th colspan="4" style="font-size:26px">Obtained Grade</th>
                                <th rowspan="2"  style="width:15%;padding-top:10px;font-size:26px">Remarks</th>
                            </tr>
                            <tr>
                                <th style='width:5%;font-size:26px'>TH</th>
                                <th style='width:5%;font-size:26px'>PR</th>
                                <th style="width:12%;font-size:26px">Grade Points</th>
                                <th style="width:11%;font-size:26px">Final Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_subject_mark = 0;
                            $total_grade_point = 0;
                            $subject_count = 0;
                            $after_first = false;
                            $grade_remarks = '';
                            if(customCompute($subjects)) { 
                                $loop = 1;
                                foreach($subjects as $index => $subject) { 
                                if(isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID])) {
                                $subject_count = $subject_count + 1;
                                $fullmark = $subject->finalmark;
                                if(isset($subject_marks[$subject->subjectID]) && $subject_marks[$subject->subjectID] != '') {
                                    $fullmark = $subject_marks[$subject->subjectID];
                                }

                                $uniquepercentageArr =  isset($markpercentagesArr[$subject->subjectID]) ? $markpercentagesArr[$subject->subjectID] : [];
                                
                                $first_fifty = false;
                                if($fullmark == 50 && $after_first != true) {
                                    $first_fifty = true;
                                }
                            ?>
                            <tr class="<?php echo $loop % 2 == 0 ? 'even': 'odd'?>">
                                <td style="font-size:26px"><?=$loop ?></td>
                                <td style="font-size:26px"><?=$subject->subject ?></td>
                                <!-- <td><?//=$fullmark ?></td> -->
                                <?php
                                    $percentageMark  = 0;
                                    $mark_exist = false;
                                    foreach($markpercentages as $markpercentageID) {
                                        $f = false;
                                        if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                            $f = true;
                                        } 
                                        if(isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {

                                            $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                            if($markpercentage == 'th' || $markpercentage == 'Th' || $markpercentage == 'Theory' || $markpercentage == 'theory') {
                                                if($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] != 0) {
                                                    $theory_part = ($percentageArr[$markpercentageID]->percentage/100) * $fullmark;
                                                    $percentage = floor(($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]/$theory_part)*100);
                                                    if(customCompute($grades)) {
                                                        foreach($grades as $grade) {
                                                            if(($grade->gradefrom <= $percentage) && ($grade->gradeupto >= $percentage)) {
                                                                $mark_exist = true;
                                                                ?>
                                                                <td align="center"><?=$grade->grade?></td>
                                                                <?php 
                                                            }
                                                        } 
                                                    }
                                                } else {
                                                    $mark_exist = false;
                                                }
                                            }
                                        }
                                    } 
                                    if($mark_exist == false) {
                                        echo "<td align='center'>-</td>";
                                    }
                                    $mark_exist = false;
                                    foreach($markpercentages as $markpercentageID) {
                                        $f = false;
                                        if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                            $f = true;
                                        } 
                                        if(isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {

                                            $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                            if($markpercentage == 'pr' || $markpercentage == 'Pr' || $markpercentage == 'Practical' || $markpercentage == 'practical' || $markpercentage == 'CAS') {
                                                if($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] != 0) {
                                                    $practical_part = ($percentageArr[$markpercentageID]->percentage/100) * $fullmark;
                                                    $percentage = floor(($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]/$practical_part)*100);
                                                    if(customCompute($grades)) {
                                                        foreach($grades as $grade) {
                                                            if(($grade->gradefrom <= $percentage) && ($grade->gradeupto >= $percentage)) {
                                                                $mark_exist = true;
                                                                ?>
                                                                <td align="center"><?=$grade->grade?></td>
                                                                <?php
                                                            }
                                                            
                                                        } 
                                                    }
                                                } else {
                                                    $mark_exist = false;
                                                }
                                            }
                                        }
                                    }
                                    if($mark_exist == false) {
                                        echo "<td align='center'>-</td>";
                                    }
                                    $subjectMark = isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID] : '0';
                                    $subjectMark = markCalculationView($subjectMark, $fullmark, $percentageMark);

                                    $total_subject_mark += $subjectMark;
                                    $mark_exist = false;
                                    if(customCompute($grades)) { 
                                        foreach($grades as $grade) {
                                            if(($grade->gradefrom <= $subjectMark) && ($grade->gradeupto >= $subjectMark)) { 
                                                $mark_exist = true;
                                                ?>
                                                <td style="font-size:26px"><?=$grade->point?></td>
                                                <td style="font-size:26px"><?=$grade->grade?></td>
                                                <td style="font-size:26px"><?=$grade->note?></td>
                                                <?php 
                                            }
                                        } 
                                    } 
                                    if(!$mark_exist) {
                                        echo '<td></td>';
                                        echo '<td></td>';
                                        echo '<td></td>';
                                    }
                                    ?>
                                </tr>
                            <?php
                           $loop++;
                        } } ?>
                            
                        </tbody>
                    </table>
                    <table class="reports table-fluid" style="margin-top: 16px;margin-left:8px;margin-right:8px;">
                    <tr>
                                <td align="left" style="font-size:26px;">
                                    <b>GRADE POINTS AVERAGE (GPA)</b>
                                </td>
                                <?php 
                                $total_subject_mark = round($total_subject_mark / $subject_count);
                                $remarks = '-';
                                if(isset($studentPosition[$student->srstudentID]['classPositionMark']) && $studentPosition[$student->srstudentID]['classPositionMark'] > 0 && isset($studentPosition['totalStudentMarkAverage']) && $total_subject_mark > 1) {
                                    if(customCompute($grades)) { 
                                        foreach($grades as $grade) {
                                            if(($grade->gradefrom <= $total_subject_mark) && ($grade->gradeupto >= $total_subject_mark)) { 
                                                echo "<td style='font-size:26px;'><b>".$grade->point.'</b></td>';
                                                // echo '<td><b>'.$grade->grade.'</b></td>';
                                                $remarks = $grade->remarks;
                                            }
                                        } 
                                    }
                                } else {
                                    echo '<td></b>F</b></td>';
                                    // echo '<td></b>0</b></td>';
                                }
                            ?>
                            <td style="font-size:26px;">Attendance</td>
                            <td style="font-size:26px;">
                                - </td>
                           
                            </tr>
                            </table>

                    <?php 
                        if(count($subjects) <= 6) {
                            $margin = '42px';
                        } else if(count($subjects) <= 8) {
                            $margin = '38px';
                        } else {
                            $margin = '0px';
                        }
                    ?>
                    <table class="trim-yaxis-td remarks-table table-fluid" style="margin-top: 16px;">
                       <tbody>
                        <tr>
                            <td valign="top">
                                <table class="class-teacher-remarks table-fluid">
                                    <!-- <tr>
                                        <td>
                                            <h4>Class Teachers Remarks:</h4>
                                        </td>
                                    </tr> -->
                                    <?php if($examID == 9) { ?>
                                        <tr>
                                            <td style="padding:20px;">
                                                <br>
                                                <h3>Class Teachers Remarks:</h3>
                                                <br>
                                                <?php echo $remarks; ?>
                                                <br>
                                            </td>
                                        </tr>
                                    <?php }else {  ?>
                                        <tr>
                                            <td>
                                                <br>
                                                <h3>Class Teachers Remarks:</h3>
                                                    <br>
                                                    <p>...............................................................................................................................................................................................................................................</p>
                                                    <br>
                                                    <p>...............................................................................................................................................................................................................................................</p>
                                                    &nbsp;
                                            </td>
                                        </tr>
                                    <?php } ?>    
                                </table>
                            </td>
                            <!-- <td width="500" style="padding-left: 0; padding-right: 0; ">
                                
                            </td> -->
                        </tr>
                       
                        <tr>
                            <td>
                                <table  class="signature-table table-fluid trim-yaxis-td" style="margin-left:100px;margin-top:26px;">
                                    <tr>
                                        <!-- <td valign="center" style="vertical-align: bottom;"><b>Date of Issue:</b> <?//=$date != 0 ? $date : '' ?></td> -->
                                        <td valign="center">
                                            <?php
                                                if($siteinfos->class_teacher != 'site.png') {
                                                    if(customCompute($siteinfos->class_teacher)) {
                                                        echo "<center><img height='100' src=".base_url('uploads/images/'.$siteinfos->class_teacher)." /></center>";
                                                    }
                                                } else { ?>
                                                    <img height='100' src="uploads/images/site.png" style="visibility:hidden"/>
                                                <?php }
                                            ?>
                                           <table style="width:80px"> 
                                               <tr>
                                                   <td class="dot-border-top" style="font-size:26px;">
                                                       <center> Class Teacher </center>
                                                   </td>
                                               </tr>
                                           </table>
                                        </td>
                                        <!-- <td valign="center" >
                                            <?php
                                                if($siteinfos->incharge != 'site.png') {
                                                    if(customCompute($siteinfos->incharge)) {
                                                        echo "<center><img  height='100' src=".base_url('uploads/images/'.$siteinfos->incharge)." /></center>";
                                                    }
                                                } else { ?>
                                                    <img height='50' src="uploads/images/site.png" style="visibility:hidden"/>
                                                <?php }
                                            ?>
                                           <table>
                                               <tr>
                                                   <td class="dot-border-top">
                                                    Incharge
                                                   </td>
                                               </tr>
                                           </table>
                                        </td> -->
                                        <td valign="center">
                                            <?php
                                                if($siteinfos->enable_principal_signature == 'YES') {
                                                    if(customCompute($siteinfos->principal)) {
                                                        echo "<center><img height='100' src=".base_url('uploads/images/'.$siteinfos->principal)." /></center>";
                                                    }
                                                } else { ?>
                                                    <img height='100' src="uploads/images/site.png" style="visibility:hidden"/>
                                                <?php }
                                            ?>
                                           <table style="width:80px">
                                               <tr>
                                                   <td class="dot-border-top" style="font-size:26px;">
                                                  Principal
                                                   </td>
                                               </tr>
                                           </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                
                    <td style="width:1100px;padding-left:0px;">
                    <?php if(customCompute($coscholasticSubjects)){
                         ?>
                                                    <table class="grade-table table-fluid" style="margin-right:8px;">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="4" style="font-size:26px;">Co-scholastic Block</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach(array_chunk($coscholasticSubjects, 2) as $row) {
                                                            ?>
                                                            <tr>
                                                                <?php foreach($row as $index => $subject) { ?>
                                                                <?php
                                                                    $percentageMark1  = 0;

                                                                    if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$subject->subjectID])) {
                                                                        $uniquepercentageArr1 =  isset($markpercentagesArr1[$subject->subjectID]) ? $markpercentagesArr1[$subject->subjectID] : [];
                                                                    if(customCompute($markpercentages1)) {
                                                                        foreach($markpercentages1 as $markpercentageID) {
                                                                            $f = false;
                                                                            if(isset($uniquepercentageArr1['own']) && in_array($markpercentageID, $uniquepercentageArr1['own'])) {
                                                                                $f = true;
                                                                                $percentageMark1   += isset($percentageArr1[$markpercentageID]) ? $percentageArr1[$markpercentageID]->percentage : 0;
                                                                            } 
                                                                            $markpercentage1 = isset($percentageArr1[$markpercentageID]) ? $percentageArr1[$markpercentageID]->markpercentagetype : '';
                                                                            if($markpercentage1  == 'Co-scholastic') { ?>
                                                                                <td style="font-size:26px"><?=$subject->subject ?></td>
                                                                                <td style="font-size:26px">
                                                                                <?php 
                                                                                    if(isset($studentPosition1[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f && $studentPosition1[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] != '') {
                                                                                        foreach($grades as $grade) {
                                                                                            if($grade->gradefrom <= $studentPosition1[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] && $grade->gradeupto >= $studentPosition1[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) {
                                                                                                echo $grade->grade;
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        echo '-';
                                                                                    }
                                                                                ?>
                                                                                </td>
                                                                            <?php 
                                                                            }
                                                                        }
                                                                    } } }
                                                                    ?>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                    <?php } ?>
                                                    <table  class="grade-table table-fluid" style="margin-top: 15px; margin-right:8px; ">
                                    <thead>
                                        <tr>
                                            <th colspan="6" style="font-size:26px;">DETAILS OF GRADE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="font-size:26px">90 or above</td>
                                            <td style="font-size:26px">A+ Outstanding</td>
                                            <td style="font-size:26px">4.0</td>
                                            <td style="font-size:26px">80 to Below 90</td>
                                            <td style="font-size:26px">A Excellent</td>
                                            <td style="font-size:26px">3.6</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:26px">70 to Below 80</td>
                                            <td style="font-size:26px">B+ Very Good</td>
                                            <td style="font-size:26px">3.2</td>
                                            <td style="font-size:26px">60 to Below 70</td>
                                            <td style="font-size:26px">B Good</td>
                                            <td style="font-size:26px">2.8</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:26px">50 to Below 60</td>
                                            <td style="font-size:26px">C+ Above Average</td>
                                            <td style="font-size:26px">2.4</td>
                                            <td style="font-size:26px">40 to Below 50</td>
                                            <td style="font-size:26px">C Average</td>
                                            <td style="font-size:26px">2.0</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:26px">30 to Below 40</td>
                                            <td style="font-size:26px">D+ Partially Acceptable</td>
                                            <td style="font-size:26px">1.6</td>
                                            <td style="font-size:26px">20 to Below 30</td>
                                            <td style="font-size:26px">D Insufficient</td>
                                            <td style="font-size:26px">1.2</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:26px">1 to Below 20</td>
                                            <td style="font-size:26px">E Very Insufficient</td>
                                        </tr>
                                    </tbody>
                                </table>
                                                </td>
                                                
            </tr>
            <?php $value = $subject_count < 8 || $subject_count == 8? 200:80; ?>
            <tr>
               <td valign="center" style="padding-bottom:20px;padding-top:<?php echo $value; ?>px;"><b>Date of Issue:</b> <?=$date != 0 ? $date : '' ?></td>
            </tr>
                                      
            <tr>
                <td colspan="3" style="padding-bottom: 10px;">
                    <p class="footer-text" style="font-size: small;">
                        This is a comprehensive progress report of a child's performance in
                        curricular and co-curricular activities. It indicates whether a child is
                        able to cope up with the pressures of the class in which he/she studies.
                        It also shows whether a child is fit to be promoted to the immediate
                        higher grade or not. ThisProgess Report Card is expected to help the
                        parents/guardians analyze the capabilities of hteir ward/s and take
                        needfull steps if required. If more comprehensive information on a
                        child's progress is desired, concerned class Teacher/Subject
                        Teacher/Academic coordinator can be personally contacted with prior
                        appointment.
                    </p>   
                </td>
            </tr>
            <tr>
                <td align="center" style="padding-bottom: 10px;padding-top:15px;" class="footer-td" colspan="3">NOTE: Th = Theory, Pr = Practical, *=Absent</td>
            </tr>
           
        </table>
    </div>
    </div>
    <?php if($x < count($studentLists) - 1) { ?>
        <!-- <p style="page-break-after: always;">&nbsp;</p> -->
    <?php } ?>
    <?php } } } else { ?>
        <div class="notfound">
            <?php echo $this->lang->line('terminalreport_data_not_found'); ?>
        </div>
    <?php } ?>
    
</body>
</html>
