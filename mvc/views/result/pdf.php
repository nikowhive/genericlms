<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
 <?php if(customCompute($studentLists)) { foreach($studentLists as $student) { ?>
    <div class="report-canvas" style="padding:-36px;">
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
                                            <h1><b style="font-variant: all-petite-caps;"> <?=$siteinfos->sname?></b></h1>
                                            <div><b><?=$siteinfos->address?></b></div>
                                            <p>Ph: <a href="tel:<?=$siteinfos->phone?>"><?=$siteinfos->phone?></a>
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
                                                        <p>Student Name : <b><?=$student->srname?></b>
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
                                GRADE SHEET: <?=$examName?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
 
        <table class="container">
         
              
            <tr>
                <td>
                    <table class="reports table-fluid">
                        <thead>
                            <tr>
                                <?php 
                                reset($markpercentagesArr);
                                $firstindex          = key($markpercentagesArr);
                                $uniquepercentageArr = isset($markpercentagesArr[$firstindex]) ? $markpercentagesArr[$firstindex] : [];
                                $markpercentages     = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                                ?>
                                <th rowspan="3">S.N.</th>
                                <th rowspan="3">Subject</th>
                                <th rowspan="3">Total</th>
                                <th colspan="<?=count($markpercentages) ?>">Obtained Grade</th>
                                <th rowspan="3">Grade Points</th>
                                <th rowspan="3">Final Grade</th>
                                <th rowspan="3">Remarks</th>
                            </tr>
                            <tr>
                                <?php $cas = 0; ?>
                                <?php if(customCompute($markpercentages)) {
                                    foreach($markpercentages as $markpercentageID) {
                                        $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                        if($markpercentage == 'theory' || $markpercentage == 'Theory') { ?>
                                            <?php $cas = 1; ?>
                                            <?php 
                                            if($subjects[0]->finalmark == 100) {
                                                echo "<th rowspan='2'>".$markpercentage.' ('.$percentageArr[$markpercentageID]->percentage.')'."</th>"; 
                                            } else {
                                                echo "<th></th>";
                                            } ?>
                                            <?php }
                                    }
                                }
                                ?>
                                <th colspan="<?=count($markpercentages) - $cas ?>">Continuous Assessment System(CAS)</th>
                            </tr>
                            <tr>
                                <?php
                                if(customCompute($markpercentages) && $subjects[0]->finalmark == 100) {
                                    foreach($markpercentages as $markpercentageID) {
                                        $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                        if($markpercentage != 'theory' && $markpercentage != 'Theory') {
                                            echo "<th>".$markpercentage.' ('.$percentageArr[$markpercentageID]->percentage.')'."</th>";
                                        }
                                    } 
                                }
                                ?>
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
                            <?php if($first_fifty == true) { $after_first = true; ?>
                            <tr>
                                <?php if($subjects[0]->finalmark == 100) {
                                ?>
                                <th></th>
                                <th></th>
                                <th></th>
                                <?php } ?>
                                <?php if(customCompute($markpercentages)) {
                                    foreach($markpercentages as $markpercentageID) {
                                        $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                        if($markpercentage == 'theory' || $markpercentage == 'Theory') { ?>
                                            <th>Theory (25)</th>
                                            <?php }
                                    }
                                }
                                ?>
                                <?php
                                if(customCompute($markpercentages)) {
                                    foreach($markpercentages as $markpercentageID) {
                                        $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                        if($markpercentage != 'theory' && $markpercentage != 'Theory') {
                                            echo "<th>".$markpercentage." (5)</th>";
                                        }
                                    } 
                                }
                                if($subjects[0]->finalmark == 100) {
                                ?>
                                <th></th>
                                <th></th>
                                <th></th>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                            <tr class="<?php echo $index % 2 == 0 ? 'odd': 'even'?>">
                                <td><?=$index+1 ?></td>
                                <td><?=$subject->subject ?></td>
                                <td><?=$subject->finalmark ?></td>
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
                                            if($markpercentage == 'theory' || $markpercentage == 'Theory') { ?>
                                            <td>
                                            <?php 
                                                if(isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {
                                                    echo $studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID];
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
                                        foreach($markpercentages as $markpercentageID) {
                                            $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                            if($markpercentage != 'theory' && $markpercentage != 'Theory') { ?>
                                                <td>
                                                <?php 
                                                if(isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {
                                                    echo $studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID];
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
                                                <td><?=$grade->point?></td>
                                                <td><?=$grade->grade?></td>
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
                                <td colspan="<?= count($markpercentages) + 3 ?>" align="center">
                                    <b>GRADE POINTS AVERAGE (GPA)</b>
                                </td>
                                <?php 
                                $total_subject_mark = round($total_subject_mark / $subject_count);
                                if(isset($studentPosition[$student->srstudentID]['classPositionMark']) && $studentPosition[$student->srstudentID]['classPositionMark'] > 0 && isset($studentPosition['totalStudentMarkAverage']) && $total_subject_mark > 1) {
                                    if(customCompute($grades)) { 
                                        foreach($grades as $grade) {
                                            if(($grade->gradefrom <= $total_subject_mark) && ($grade->gradeupto >= $total_subject_mark)) { 
                                                echo '<td><b>'.$grade->point.'</b></td>';
                                                echo '<td><b>'.$grade->grade.'</b></td>';
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
            </tr>
            <tr>
                <td style="padding-left: 0;">
                    <table class="trim-yaxis-td remarks-table table-fluid">
                        <tr>
                            <td valign="top">
                                <table class="class-teacher-remarks table-fluid">
                                    <tr>
                                        <td colspan="3">
                                            <h4>Class Teachers Remarks:</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <br>
                                            <p>....................................................................................................................................................</p>
                                            <br>
                                            <p>....................................................................................................................................................</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="500" style="padding-left: 0; padding-right: 0; ">
                                <table  class="grade-table table-fluid">
                                    <thead>
                                        <tr>
                                            <th colspan="6">DETAILS OF GRADE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="font-size: small;">90 or above</td>
                                            <td style="font-size: small;">A+ Outstanding</td>
                                            <td style="font-size: small;">4.0</td>
                                            <td style="font-size: small;">80 to Below 90</td>
                                            <td style="font-size: small;">A Excellent</td>
                                            <td style="font-size: small;">3.6</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: small;">70 to Below 80</td>
                                            <td style="font-size: small;">B+ Very Good</td>
                                            <td style="font-size: small;">3.2</td>
                                            <td style="font-size: small;">60 to Below 70</td>
                                            <td style="font-size: small;">B Good</td>
                                            <td style="font-size: small;">2.8</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: small;">50 to Below 60</td>
                                            <td style="font-size: small;">C+ Above Average</td>
                                            <td style="font-size: small;">2.4</td>
                                            <td style="font-size: small;">40 to Below 50</td>
                                            <td style="font-size: small;">C Average</td>
                                            <td style="font-size: small;">2.0</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: small;">30 to Below 40</td>
                                            <td style="font-size: small;">D+ Partially Acceptable</td>
                                            <td style="font-size: small;">1.6</td>
                                            <td style="font-size: small;">20 to Below 30</td>
                                            <td style="font-size: small;">D Insufficient</td>
                                            <td style="font-size: small;">1.2</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: small;">1 to Below 20</td>
                                            <td style="font-size: small;">E Very Insufficient</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                       
                        <tr>
                            <td colspan="2">
                                <table  class="signature-table table-fluid trim-yaxis-td ">
                                    <tr>
                                        <td valign="center" style="vertical-align: bottom;"><b>Date of Issue:</b> <?=$date ?></td>
                                        <td valign="center">
                                            <img height='35' src="uploads/images/site.png" style="visibility:hidden"/>
                                           <table>
                                               <tr>
                                                   <td class="dot-border-top">
                                                   Class Teacher 
                                                   </td>
                                               </tr>
                                           </table>
                                        </td>
                                        <td valign="center" >
                                            <img height='35' src="uploads/images/site.png" style="visibility:hidden"/>
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
                                                        echo "<center><img height='35' src=".base_url('uploads/images/'.$siteinfos->principal)." /></center>";
                                                    }
                                                } else { ?>
                                                    <img height='35' src="uploads/images/site.png" style="visibility:hidden"/>
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
                <td align="center" class="footer-td">NOTE: *=Absent</td>
            </tr>
        </table>
    </div>
    <p style="page-break-after: always;">&nbsp;</p>
    <?php } } } else { ?>
        <div class="notfound">
            <?php echo $this->lang->line('terminalreport_data_not_found'); ?>
        </div>
    <?php } ?>
</body>
</html>