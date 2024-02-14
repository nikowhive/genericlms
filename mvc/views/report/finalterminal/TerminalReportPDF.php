<!DOCTYPE html>
<html lang="en">
<head> <meta charset="UTF-8"></head>
<body>
<?php if(customCompute($studentLists)) { foreach($studentLists as $x => $student) { ?>
<div class="report-canvas" style="padding-top:<?php echo count($subjects) <= 6 ? '-16px' : '-40px'; ?>; padding:-16px;">
    <table align="center">
        <tr>
            <td valign="bottom" align="right" style="padding-top:20px; padding-right:-8px;">
                <img src="<?=base_url("uploads/images/$siteinfos->photo")?>" height="100" alt=""/>
            </td>
            <td>
                <table>
                    <tr>
                        <td align="center">
                            <table>
                                <tr style= "text-align: center;">
                                    <td>
                                        <div><b>"A good education equips one for life"</b></div>
                                        <h1><b> <?=strtoupper($siteinfos->sname)?></b></h1>
                                        <div><b><?=$siteinfos->address?></b></div>
                                        <p>Ph:<?=$siteinfos->phone?></p>
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
                                                    <p>Student Name : <b><?=$student->srname?></b></p>
                                                        <?php if($student->parent_name): ?>
                                                            <p>Parent's Name : <?=$student->parent_name?></p>
                                                        <?php endif ?>
                                                </td>
                                                <td>
                                                    <p>Class:<?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : ''?></p>
                                                    <p>Section:<?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID] : ''?></p>
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
    <table class="container">
        <tr>
            <td>
                <table class="reports table-fluid">
                <thead>
                                            <tr>
                                                <?php 
											    $col = count($examtermSettings) + 2;
                                                ?>
                                                <th rowspan="2">S.N.</th>
                                                <th rowspan="2">Subject</th>
                                                <th rowspan="2">Total</th>
                                                <th colspan="<?php echo $col; ?>">Obtained Grade</th>
                                                <th rowspan="2">Remarks</th>
                                            </tr>
                                            
                                            <tr>
                                               <?php foreach($examtermSettings as $examtermSetting){
                                                    echo '<th>'.$examtermSetting->exam.' ('.$examtermSetting->value.')</th>';
                                               } ?>
                                                <th>Final Grade</th>
                                                <th>Grade Points</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php 
                                           $grand_total = 0;
                                           $grand_total1 = 0;
                                           $subject_count = 0;
                                           $after_first = false;
										   if(customCompute($subjects)) {
                                             $loop = 1;  
                                             foreach($subjects as $index => $subject) 
                                             { 
												if(isset($studentPosition[$examtermSettings[0]->examID][$student->srstudentID]['subjectMark'][$subject->subjectID])) 
                                                {
                                                    $finaltermfullmark = $subject->finalmark;
                                                    if(isset($subject_marks[$subject->subjectID]) && $subject_marks[$subject->subjectID] != '') {
                                                        $finaltermfullmark = $subject_marks[$subject->subjectID];
                                                    }
                                               	?>
                                            <tr>
                                                <td><?=$loop ?></td>
                                                <td><?=$subject->subject ?></td>
                                                <td><?=$finaltermfullmark ?></td>
                                                <?php
                                                   $subject_count = $subject_count + 1;
                                                   $total_final_subject_mark = 0;
                                                   foreach($examtermSettings as $examtermSetting){

                                                    $examID = $examtermSetting->examID;
                                                    
                                                    $fullmark = $subject->finalmark;
                                                    if(isset($examwise_subject_marks[$examID][$subject->subjectID]) && $examwise_subject_marks[$examID][$subject->subjectID] != '') {
                                                        $fullmark = $examwise_subject_marks[$examID][$subject->subjectID];
                                                    }

                                                    $weightage = $examtermSetting->value;
                                                    $weightage = ($weightage / 100) * $fullmark;

                                                    $examwisemarkpercentagesArr = $markpercentagesArr[$examID];
                                                  
                                                    reset($examwisemarkpercentagesArr);
                                                    $firstindex          = key($examwisemarkpercentagesArr);
                                                    $uniquepercentageArr = isset($examwisemarkpercentagesArr[$firstindex]) ? $examwisemarkpercentagesArr[$firstindex] : [];
                                                    $markpercentages     = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                                                    
                                                     $uniquepercentageArr =  isset($examwisemarkpercentagesArr[$subject->subjectID]) ? $examwisemarkpercentagesArr[$subject->subjectID] : [];
                                                     
                                                     $percentageMark  = 0;
                                                     if(customCompute($markpercentages)) {
														foreach($markpercentages as $markpercentageID) {
															$f = false;
															if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
																$f = true;
																$percentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
															} 
                                                        }
                                                    }        

                                                     $subjectMark = isset($studentPosition[$examID][$student->srstudentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$examID][$student->srstudentID]['subjectMark'][$subject->subjectID] : '0';
                                                     $subjectMark1 = markCalculationView($subjectMark, $fullmark, $percentageMark);
                                                    
                                                     $final_subject_mark = ($subjectMark / $fullmark) * $weightage;

                                                     $total_final_subject_mark  += $final_subject_mark;
                                                     
                                                    $mark_exist = false;
													if(customCompute($grades)) { 
                                                        foreach($grades as $grade) {
                                                            if(($grade->gradefrom <= $subjectMark1) && ($grade->gradeupto >= $subjectMark1)) { 
                                                                $mark_exist = true;
                                                                ?>
                                                                <td>(<?=$subjectMark ?>)<?=$grade->grade?></td>
                                                                <?php 
                                                            }
                                                        } 
                                                    } 
                                                    if(!$mark_exist) {
                                                        echo '<td></td>';
                                                    }

                                                } 

                                                   $total_final_subject_mark1 = markCalculationView($total_final_subject_mark, $finaltermfullmark);
                                                   $final_mark_exist = false;
                                                    if(customCompute($grades)) { 
                                                        foreach($grades as $grade) {
                                                            if(($grade->gradefrom <= $total_final_subject_mark1) && ($grade->gradeupto >= $total_final_subject_mark1)) { 
                                                                $final_mark_exist = true;
                                                                ?>
                                                                <td>(<?=$total_final_subject_mark ?>)<?=$grade->grade?></td>
                                                                <td><?=$grade->point?></td>
                                                                <td><?=$grade->note?></td>
                                                                <?php 
                                                            }
                                                        } 
                                                    } 

                                                    if(!$final_mark_exist) {
                                                        echo '<td></td>';
                                                        echo '<td></td>';
                                                        echo '<td></td>';
                                                    }

                                                    $grand_total += $total_final_subject_mark; 
                                                    $grand_total1 += $total_final_subject_mark1;  

                                                ?>

                                            	</tr>
                                            <?php 
                                            $loop++;
                                        } 
                                        } ?>
                                            <tr>
                                                <td colspan="<?= count($examtermSettings) + 3 ?>" align="center">
                                                    <b>GRADE POINTS AVERAGE (GPA)</b>
                                                </td>
                                                <?php 
                                               
                                                    $final_grand_total = round($grand_total1 / $subject_count);
													if(isset($studentPosition[$examID][$student->srstudentID]['classPositionMark']) && $studentPosition[$examID][$student->srstudentID]['classPositionMark'] > 0 && isset($studentPosition[$examID]['totalStudentMarkAverage']) && $final_grand_total > 1) {
                                                        if(customCompute($grades)) { 
                                                            foreach($grades as $grade) {
                                                                if(($grade->gradefrom <= $final_grand_total) && ($grade->gradeupto >= $final_grand_total)) { 
                                                                    echo '<td><b>('.$final_grand_total.')'.$grade->point.'</b></td>';
														            echo '<td><b>'.$grade->grade.'</b></td>';
                                                                }
                                                            } 
                                                        }
													} else {
														echo '<td><b>F</b></td>';
														echo '<td><b>0</b></td>';
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
                    <?php 
                        if(count($subjects) <= 6) {
                            $margin = '42px';
                        } else if(count($subjects) <= 8) {
                            $margin = '38px';
                        } else {
                            $margin = '0px';
                        }
                    ?>
                    <table class="trim-yaxis-td remarks-table table-fluid" style="margin-top:<?php echo $margin ?>">
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
                                            &nbsp;
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
                                <table  class="signature-table table-fluid trim-yaxis-td" style="margin-top:<?php echo $margin ?>">
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
                <td align="center" class="footer-td">NOTE: *=Absent</td>
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
