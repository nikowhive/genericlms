<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body >
 <?php if(customCompute($studentLists)) { foreach($studentLists as $x => $student) { ?>
    <div class="report-canvas" style="padding-top:<?php echo count($subjects) <= 6 ? '-16px' : '-40px'; ?>; padding:-16px;">
        <table align="center">
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
                                                    <td>
                                                        <p>Student's Name : <b><?=$student->srname?></b>
                                                        </p>
                                                        <?php if($student->parent_name): ?>
                                                            <p>Parent's Name : <?=$student->parent_name?></p>
                                                        <?php endif ?>
                                                    </td>
                                                    <td>
                                                        <p>Class:
                                                            <?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : ''?>
                                                        </p>
                                                        <p>Section:
                                                            <?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID] : ''?>
                                                        </p>
                                                    </td>
                                                    <td>
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
        
        <br>
        
        <table>
            
              
            <tr>
                <td>
                    <table class="reports">
                        <thead>
                            <tr>
                                <?php 
                                reset($markpercentagesArr);
                                $firstindex          = key($markpercentagesArr);
                                $uniquepercentageArr = isset($markpercentagesArr[$firstindex]) ? $markpercentagesArr[$firstindex] : [];
                                $markpercentages     = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];

                                reset($markpercentagesArr1);
                                $firstindex1          = key($markpercentagesArr1);
                                $uniquepercentageArr1 = isset($markpercentagesArr1[$firstindex]) ? $markpercentagesArr1[$firstindex] : [];
                                $markpercentages1     = $uniquepercentageArr1[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                                ?>
                                <th rowspan="2">S.N.</th>
                                <th rowspan="2">Subject</th>
                                <th colspan="<?=count($markpercentages) + 1 ?>">Obtained Grade</th>
                                <th rowspan="2"  style="width:12%;">Remarks</th>
                            </tr>
                            <tr>
                                <?php $cas = 0; ?>
                                <?php if(customCompute($markpercentages)) {
                                    foreach($markpercentages as $markpercentageID) {
                                        $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                        if($markpercentage == 'th' || $markpercentage == 'Th' || $markpercentage == 'pr' || $markpercentage == 'Pr') { ?>
                                            <?php $cas = 1; ?>
                                            <?php 
                                            if($subjects[0]->finalmark == 100) {
                                                echo "<th rowspan='1'>".$markpercentage."</th>"; 
                                            } ?>
                                            <?php }
                                    }
                                }
                                ?>
                                <th rowspan="1">Final Grade</th>
                                <th rowspan="1">Grade Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_subject_mark = 0;
                            $subject_count = 0;
                            $after_first = false;
                            if(customCompute($subjects)) { foreach($subjects as $index => $subject) { 
                                if(isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID])) {
                                $subject_count = $index + 1;

                                $uniquepercentageArr =  isset($markpercentagesArr[$subject->subjectID]) ? $markpercentagesArr[$subject->subjectID] : [];
                                
                                $first_fifty = false;
                                if($subject->finalmark == 50 && $after_first != true) {
                                    $first_fifty = true;
                                }
                            ?>
                            <tr class="<?php echo $index % 2 == 0 ? 'odd': 'even'?>">
                                <td><?=$index+1 ?></td>
                                <td><?=$subject->subject ?></td>
                                <?php
                                    $percentageMark  = 0;
                                    if(customCompute($markpercentages)) {
                                        foreach($markpercentages as $markpercentageID) {
                                            $f = false;
                                            if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                                $f = true;
                                                $percentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
                                            } 
                                            $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                            if($markpercentage == 'th' || $markpercentage == 'Th' || $markpercentage == 'pr' || $markpercentage == 'Pr') { ?>
                                            <td>
                                            <?php 
                                                if(isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {
                                                    foreach($grades as $grade) { 
                                                        if($subject->finalmark == 100) {
                                                            if($grade->gradefrom <= $studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] && $grade->gradeupto >= $studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID])
                                                                echo $grade->grade;
                                                        } else if($subject->finalmark == 50) {
                                                            if($grade->gradefrom <= $studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] * 2 && $grade->gradeupto >= $studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] * 2)
                                                                echo $grade->grade;
                                                        }
                                                    }
                                                } else {
                                                    if($f) {
                                                        echo '*';
                                                    }
                                                }
                                            ?>
                                            </td>
                                            <?php 
                                            }
                                        }
                                        
                                    }
                                    $subjectMark = isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID] : '0';
                                    $subjectMark = markCalculationView($subjectMark, $subject->finalmark, $percentageMark);

                                    $total_subject_mark += $subjectMark;
                                    $mark_exist = false;
                                    if(customCompute($grades)) { 
                                        foreach($grades as $grade) {
                                            if(($grade->gradefrom <= $subjectMark) && ($grade->gradeupto >= $subjectMark)) { 
                                                $mark_exist = true;
                                                ?>
                                                <td><?=$grade->grade?></td>
                                                <td><?=$grade->point?></td>
                                                <td><?=$grade->note?></td>
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
                            <?php } } ?>
                            <tr>
                                <td colspan="<?= count($markpercentages) + 1 ?>" align="center">
                                    <b>GRADE POINTS AVERAGE (GPA)</b>
                                </td>
                                <?php 
                                $total_subject_mark = round($total_subject_mark / count($mandatorySubjects));
                                if(isset($studentPosition[$student->srstudentID]['classPositionMark']) && $studentPosition[$student->srstudentID]['classPositionMark'] > 0 && isset($studentPosition['totalStudentMarkAverage']) && $total_subject_mark > 1) {
                                    if(customCompute($grades)) { 
                                        foreach($grades as $grade) {
                                            if(($grade->gradefrom <= $total_subject_mark) && ($grade->gradeupto >= $total_subject_mark)) { 
                                                echo '<td><b>'.$grade->grade.'</b></td>';
                                                echo '<td><b>'.$grade->point.'</b></td>';
                                            }
                                        } 
                                    }
                                } else {
                                    echo '<td></b>F</b></td>';
                                    echo '<td></b>0</b></td>';
                                }
                            ?>
                            <td>
							</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td>
                    <table class="grade-table">
                        <thead>
                            <tr>
                                <th colspan="4">Co-scholastic Block</th>
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
                                            if($markpercentage1 != 'th' && $markpercentage1 != 'Th' && $markpercentage1 != 'pr' && $markpercentage1 != 'Pr') { ?>
                                                <td><?=$subject->subject ?></td>
                                                <td style="text-align:center">
                                                <?php 
                                                    if(isset($studentPosition1[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {
                                                        foreach($grades as $grade) {
                                                            if($grade->gradefrom <= $studentPosition1[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] && $grade->gradeupto >= $studentPosition1[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) {
                                                                echo $grade->grade;
                                                            }
                                                        }
                                                    } else {
                                                        echo '*';
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
                </td>
            </tr>
        </table>
        <br>
        <br>
        <table>
            <tr>    
                <td style="padding-left: 0;">
                
                    <table class="table-fluid">
                        <tr>
                            <td valign="top">
                                <table class="class-teacher-remarks table-fluid">
                                    <tr>
                                        <td>
                                            <h4>Class Teacher's Remarks:</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br>
                                            <p>....................................................................................................................................................</p>
                                            <br>
                                            <p>....................................................................................................................................................</p>
                                            <br>
                                            <p>....................................................................................................................................................</p>
                                            &nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            
                            <td style="padding-left: 0; padding-right: 0; ">
                                <table  class="grade-table table-fluid">
                                    <thead>
                                        <tr>
                                            <th colspan="6">DETAILS OF GRADE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>90 or above</td>
                                            <td>A+ Outstanding</td>
                                            <td>4.0</td>
                                            <td>80 to Below 90</td>
                                            <td>A Excellent</td>
                                            <td>3.6</td>
                                        </tr>
                                        <tr>
                                            <td>70 to Below 80</td>
                                            <td>B+ Very Good</td>
                                            <td>3.2</td>
                                            <td>60 to Below 70</td>
                                            <td>B Good</td>
                                            <td>2.8</td>
                                        </tr>
                                        <tr>
                                            <td>50 to Below 60</td>
                                            <td>C+ Above Average</td>
                                            <td>2.4</td>
                                            <td>40 to Below 50</td>
                                            <td>C Average</td>
                                            <td>2.0</td>
                                        </tr>
                                        <tr>
                                            <td>30 to Below 40</td>
                                            <td>D+ Partially Acceptable</td>
                                            <td>1.6</td>
                                            <td>20 to Below 30</td>
                                            <td>D Insufficient</td>
                                            <td>1.2</td>
                                        </tr>
                                        <tr>
                                            <td>1 to Below 20</td>
                                            <td>E Very Insufficient</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>  
                        </tr>
                       <br>
                       <br>
                        <tr>
                            <td colspan="2">
                                <table  class="signature-table table-fluid trim-yaxis-td">
                                    <tr>
                                        <td valign="center" style="vertical-align: bottom;"><b>Date of Issue:</b> <?=$date != 0 ? $date : '' ?></td>
                                        <td valign="center">
                                            <?php
                                                if($siteinfos->class_teacher != 'site.png') {
                                                    if(customCompute($siteinfos->class_teacher)) {
                                                        echo "<center><img height='50' src=".base_url('uploads/images/'.$siteinfos->class_teacher)." /></center>";
                                                    }
                                                } else { ?>
                                                    <img height='50' src="uploads/images/site.png" style="visibility:hidden"/>
                                                <?php }
                                            ?>
                                           <table>
                                               <tr>
                                                   <td class="dot-border-top">
                                                   Class Teacher 
                                                   </td>
                                               </tr>
                                           </table>
                                        </td>
                                        <td valign="center" >
                                            <?php
                                                if($siteinfos->incharge != 'site.png') {
                                                    if(customCompute($siteinfos->incharge)) {
                                                        echo "<center><img  height='50' src=".base_url('uploads/images/'.$siteinfos->incharge)." /></center>";
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
                                        </td>
                                        <td valign="center">
                                            <?php
                                                if($siteinfos->enable_principal_signature == 'YES') {
                                                    if(customCompute($siteinfos->principal)) {
                                                        echo "<center><img height='50' src=".base_url('uploads/images/'.$siteinfos->principal)." /></center>";
                                                    }
                                                } else { ?>
                                                    <img height='50' src="uploads/images/site.png" style="visibility:hidden"/>
                                                <?php }
                                            ?>
                                           <table>
                                               <tr>
                                                   <td class="dot-border-top">
                                                  Principal
                                                   </td>
                                               </tr>
                                           </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                       
                    </table>
                </td>
            </tr>
            

            <tr>
                <td>
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
                <td align="center" class="footer-td">NOTE: Th=Theory, *=Absent</td>
            </tr>
        </table>
    </div>
    <?php if($x < count($studentLists) - 1) { ?>
        <p style="page-break-after: always;">&nbsp;</p>
    <?php } ?>
    <?php } } } else { ?>
        <div class="notfound">
            <?php echo $this->lang->line('terminalreport_data_not_found'); ?>
        </div>
    <?php } ?>
</body>
</html>
