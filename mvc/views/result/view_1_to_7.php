<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $pdf_preview_uri = base_url('studentresult/pdf/'.$examID.'/'.$classesID.'/'.$sectionID.'/'.$studentIDD);
        ?>

        <a href="<?php echo $pdf_preview_uri ?>" class="btn btn-default pdfurl" target="_blank"><i class="fa fa-file"></i> PDF Preview</a>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i>
            <?=$this->lang->line('terminalreport_report_for')?> <?=$this->lang->line('terminalreport_terminal')?> -
            <?=$examName?> <?=isset($classes[$classesID]) ? "( ".$classes[$classesID]." ) " : ''?>
        </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <?php if(customCompute($studentLists)) { foreach($studentLists as $student) { ?>
                    <!-- <div class="mainterminalreport"> -->
            <style>
                    * {
                        margin: 0;
                        padding: 0;
                        /* font-family: "Arial"; */
                        box-sizing: border-box;
                    }
                    table {
                        border-spacing: 15px;
                    }
                    table,
                    th,
                    td {
                        border: none;
                        padding: 10px;
                        border-collapse: collapse;
                        vertical-align: top;
                    }
                    th {
                        text-align: center;
                    }

                    .container {
                        width: 96%;
                        margin: 0 auto;
                    }

                    .trim-yaxis-td>tbody>tr>td:first-child {
                        padding-left: 0;

                    }

                    .trim-yaxis-td>tbody>tr>td:last-child {
                        padding-right: 0;

                    }

                    .grade-sheet {
                        background-color: #212121;
                        color: white !important;
                        text-align: center;
                    }
                    .table-fluid {
                        width: 100%;
                    }
                    .reports,
                    .reports th,
                    .reports td,
                    .grade-table,
                    .grade-table th,
                    .grade-table td {
                        border: 1px solid #000;
                    }
                    .reports th {
                        background-color: #f9f9f9;
                    }
                    .reports tr:nth-of-type(odd) td {
                        background-color: #D2E0BC;
                    }
                    .reports tr:last-child td {
                        background-color: #f9f9f9;
                    }

                    .remarks-table>tbody>tr>td {
                        width: 50%;
                    }
                    .class-teacher-remarks {
                        border: 1px solid #000;
                    }
                    .dot-border-bottom {
                        border-bottom: 1px dashed black;
                        border-left:none !important;
                        border-right:none !important;
                        /* padding: 5px 0; */
                    }
                    .dot-border-top {
                        border-top: 1px dashed black;
                        padding: 5px 0;
                        width: 121px;
                    }

                    .class-teacher-remarks >tbody >tr >td {
                        border-left: 1px solid black;
                        border-right: 1px solid black;
                    }
                    .class-teacher-remarks td p {
                        margin-bottom: 16px;

                    }
                    .footer-td {
                        background-color: #f9f9f9;
                        border-top: 1px solid black;
                        padding: 10px 0;
                    }
                    .report-canvas {
                        overflow-x: auto;

                    }
                    .signature-tbl {
                      display:flex;
                      gap:16px;
                      justify-content: space-between;
                    }
                    .table-blank-fill td {
                            height:40px;
                      
                    }
                    .report-canvas {
                        overflow-x: auto;
                    }
                    </style>
                    <div class="report-canvas">
                        <table class="container">
                            <tr>
                                <td align="center">
                                    <table width="900">
                                        <tr>
                                          <td align="center">
                                            <table>
                                              <tr style= "text-align: center !important;">
                                                <td valign="top" >
                                                    <img src="<?=base_url("uploads/images/$siteinfos->photo")?>" height="100" alt=""/>
                                                </td>
                                                <td>
                                                    <div><b>"A good education equips one for life"</b></div>
                                                    <h1 style="margin-top: -10px;"><b style="font-variant: all-petite-caps; margin: -10px;"><?=$siteinfos->sname?></b></h1>
                                                    <div style="margin-top: -10px;"><b><?=$siteinfos->address?></b></address>
                                                    <p>Ph: <a href="tel:<?=$siteinfos->phone?>"><?=$siteinfos->phone?></a>
                                                    </p>
                                                </td>
                                            </tr>
                                            </table>
                                          </td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <table  style="margin: -30px;">
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
                                                GRADE SHEET: <?=strtoupper($examName)?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
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
                                               
                                                reset($markpercentagesArr1);
                                                $firstindex1          = key($markpercentagesArr1);
                                                $uniquepercentageArr1 = isset($markpercentagesArr1[$firstindex]) ? $markpercentagesArr1[$firstindex] : [];
                                                $markpercentages1     = $uniquepercentageArr1[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                                                
                                                $count_markpercentages = count($markpercentages);
                                                         
                                                if(customCompute($markpercentages)) {
                                                    foreach($markpercentages as $markpercentageID) {
                                                        $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                                        if($markpercentage == 'Co-scholastic') {
                                                            $count_markpercentages -= 1;
                                                        }
													}
                                                }


                                                ?>

                                                <th rowspan="3">S.N.</th>
                                                <th rowspan="3">Subject</th>
                                                <th rowspan="3">Total</th>
                                                <th colspan="<?=$count_markpercentages ?>">Obtained Grade</th>
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
                                                            <?php $cas += 1; ?>
                                                            <?php 
                                                            if($subjects[0]->finalmark == 100) {
                                                                echo "<th rowspan='2'>".$markpercentage.' ('.$percentageArr[$markpercentageID]->percentage.')'."</th>"; 
                                                            } else {
                                                                echo "<th></th>";
                                                            } ?>
                                                            <?php }
                                                        if($markpercentage == 'Co-scholastic') {
                                                            $cas += 1;
                                                        }
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
														if($markpercentage != 'theory' && $markpercentage != 'Theory' && $markpercentage != 'Co-scholastic') {
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
											if(customCompute($subjects)) {
                                                 $loop = 1;
                                                 foreach($subjects as $index => $subject) { 
												if(isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID])) {
                                                $subject_count = $subject_count + 1;

                                                $uniquepercentageArr =  isset($markpercentagesArr[$subject->subjectID]) ? $markpercentagesArr[$subject->subjectID] : [];
                                                
                                                $first_fifty = false;
                                                if($subject->finalmark == 50 && $after_first != true) {
                                                    $first_fifty = true;
                                                }
                                                
											?>
                                            <?php if($first_fifty == true) { $after_first = true; ?>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
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
														if($markpercentage != 'theory' && $markpercentage != 'Theory' && $markpercentage != 'Co-scholastic') {
															echo "<th>".$markpercentage." (5)</th>";
														}
													} 
												}
												?>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <?php } ?>
                                            <tr>
                                                <td><?= $loop; ?></td>
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
															if($markpercentage != 'theory' && $markpercentage != 'Theory' && $markpercentage != 'Co-scholastic') { ?>
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
                                            <?php
                                             $loop++;
                                        } } 
                                            
                                            ?>
                                            <tr>
                                                <td colspan="<?= $count_markpercentages + 3 ?>" align="center">
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
                                <td>
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
                                                        <td >
                                                         <table    class="table-fluid table-blank-fill">
                                                             <tr>
                                                                 <td class=" dot-border-bottom">

                                                                 </td>
                                                             </tr>
                                                             <tr>
                                                                 <td class=" dot-border-bottom">

                                                                 </td>
                                                             </tr>
                                                             <tr>
                                                                 <td class=" dot-border-bottom">

                                                                 </td>
                                                             </tr>
                                                         </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                    </tr>

                                                </table>
                                            </td>
                                            <td>
                                                <table class="grade-table table-fluid">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="6">DETAILS OF GRADE</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody style="font-size: small;">
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
                                    </table>
                                </td>
                            </tr>

                            <?php if(customCompute($coscholasticSubjects)){ ?>
                            <tr>
                                <td>
                                                    <table class="grade-table table-fluid">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="10">Co-scholastic Block</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach(array_chunk($coscholasticSubjects, 5) as $row) {
                                                            ?>
                                                            <tr>
                                                                <?php foreach($row as $index => $subject) { ?>
                                                                <?php
                                                                    $percentageMark1  = 0;

                                                                    if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$subject->subjectID])) {
                                                                        $uniquepercentageArr1 =  isset($markpercentagesArr1[$subject->subjectID]) ? $markpercentagesArr1[$subject->subjectID] : [];
                                                                    if(customCompute($markpercentages1)) {
                                                                        // $i = 1;
                                                                        foreach($markpercentages1 as $markpercentageID) {
                                                                            // if($i == 1){
                                                                            $f = false;
                                                                            if(isset($uniquepercentageArr1['own']) && in_array($markpercentageID, $uniquepercentageArr1['own'])) {
                                                                                $f = true;
                                                                                $percentageMark1   += isset($percentageArr1[$markpercentageID]) ? $percentageArr1[$markpercentageID]->percentage : 0;
                                                                            } 
                                                                            $markpercentage1 = isset($percentageArr1[$markpercentageID]) ? $percentageArr1[$markpercentageID]->markpercentagetype : '';
                                                                            if($markpercentage1 == 'Co-scholastic') { ?>
                                                                                <td><?=$subject->subject ?></td>
                                                                                <td style="text-align:center">
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
                                                                        // }
                                                                        // $i++;
                                                                        }
                                                                    } } }
                                                                    ?>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                            </tr>
                                                <?php } ?>


                            <tr>
                                <td>
                                    <table  class="signature-table table-fluid trim-yaxis-td ">
                                        <tr>
                                            <td valign="center" style="vertical-align: bottom;"><b>Date of Issue:</b> <?=$date != 0 ? $date : '' ?></td>
                                            <td valign="center">
                                                <?php
                                                if($setting->class_teacher != 'site.png') {
                                                    if(customCompute($setting->class_teacher)) {
                                                        echo "<img height='75' src=".base_url('uploads/images/'.$setting->class_teacher)." />";
                                                    }
                                                } else { ?>
                                                    <img height='75' src="uploads/images/site.png" style="visibility:hidden"/>
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
                                                    if($setting->incharge != 'site.png') {
                                                        if(customCompute($siteinfos->incharge)) {
                                                            echo "<img height='75' src=".base_url('uploads/images/'.$setting->incharge)." />";
                                                        } 
                                                    } else { ?>
                                                        <img height='75' src="uploads/images/site.png" style="visibility:hidden"/>
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
                                                    if($setting->enable_principal_signature == 'YES') {
                                                        if(customCompute($setting->principal)) {
                                                            echo "<img height='75' src=".base_url('uploads/images/'.$setting->principal)." />";
                                                        }
                                                    } else { ?>
                                                        <img height='75' src="uploads/images/site.png" style="visibility:hidden"/>
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
                                    <!-- <div class="signature-tbl">
                                    <div><b>Date of Issue:</b> <?=$date ?></div>
                                    <p class="dot-border-top">Class Teacher</p>
                                    <p class="dot-border-top">Principal</p>
                                    </div> -->
                                    <!-- <table class="signature-table table-fluid trim-yaxis-td ">
                                        <td><b>Date of Issue:</b> 2077-01-14</td>
                                        <td>
                                            <p class="dot-border-top">Class Teacher</p>
                                        </td>
                                        <td>
                                            <p class="dot-border-top">Principal</p>
                                        </td>
                                    </table> -->
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="font-size: small;">
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
                    <!-- </div> -->
                    <p style="page-break-after: always;">&nbsp;</p>
                    <?php } } } else { ?>
                    <div class="callout callout-danger">
                        <p><b class="text-info"><?=$this->lang->line('terminalreport_data_not_found')?></b></p>
                    </div>
                    <?php } ?>
                </div>
            </div><!-- row -->
        </div>
    </div>
</div>


<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('terminalreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span
                            class="sr-only"><?=$this->lang->line('terminalreport_close')?></span></button>
                    <h4 class="modal-title"><?=$this->lang->line('terminalreport_mail')?></h4>
                </div>
                <div class="modal-body">

                    <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("terminalreport_to")?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="to" name="to" value="<?=set_value('to')?>">
                    </div>
                    <span class="col-sm-4 control-label" id="to_error">
                    </span>
                </div>

                <?php
                    if(form_error('subject'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                <label for="subject" class="col-sm-2 control-label">
                    <?=$this->lang->line("terminalreport_subject")?> <span class="text-red">*</span>
                </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="subject" name="subject"
                        value="<?=set_value('subject')?>">
                </div>
                <span class="col-sm-4 control-label" id="subject_error">
                </span>

            </div>

            <?php
                    if(form_error('message'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
            <label for="message" class="col-sm-2 control-label">
                <?=$this->lang->line("terminalreport_message")?>
            </label>
            <div class="col-sm-6">
                <textarea class="form-control" id="message" style="resize: vertical;" name="message"
                    value="<?=set_value('message')?>"></textarea>
            </div>
        </div>


    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" style="margin-bottom:0px;"
            data-dismiss="modal"><?=$this->lang->line('close')?></button>
        <input type="button" id="send_pdf" class="btn btn-success"
            value="<?=$this->lang->line("terminalreport_send")?>" />
    </div>
    </div>
    </div>
    </div>
</form>
<!-- email end here -->

<script type="text/javascript">
$('.terminalreporttable').mCustomScrollbar({
    axis: "x"
});

function check_email(email) {
    var status = false;
    var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
    if (email.search(emailRegEx) == -1) {
        $("#to_error").html('');
        $("#to_error").html("<?=$this->lang->line('terminalreport_mail_valid')?>").css("text-align", "left").css(
            "color", 'red');
    } else {
        status = true;
    }
    return status;
}


$('#send_pdf').click(function() {
    var field = {
        'to': $('#to').val(),
        'subject': $('#subject').val(),
        'message': $('#message').val(),
        'examID': '<?=$examID?>',
        'classesID': '<?=$classesID?>',
        'sectionID': '<?=$sectionID?>',
        'studentID': '<?=$studentIDD?>',
    };

    var to = $('#to').val();
    var subject = $('#subject').val();
    var error = 0;

    $("#to_error").html("");
    $("#subject_error").html("");

    if (to == "" || to == null) {
        error++;
        $("#to_error").html("<?=$this->lang->line('terminalreport_mail_to')?>").css("text-align", "left").css(
            "color", 'red');
    } else {
        if (check_email(to) == false) {
            error++
        }
    }

    if (subject == "" || subject == null) {
        error++;
        $("#subject_error").html("<?=$this->lang->line('terminalreport_mail_subject')?>").css("text-align",
            "left").css("color", 'red');
    } else {
        $("#subject_error").html("");
    }

    if (error == 0) {
        $('#send_pdf').attr('disabled', 'disabled');
        $.ajax({
            type: 'POST',
            url: "<?=base_url('terminalreport/send_pdf_to_mail')?>",
            data: field,
            dataType: "html",
            success: function(data) {
                var response = JSON.parse(data);
                if (response.status == false) {
                    $('#send_pdf').removeAttr('disabled');
                    if (response.to) {
                        $("#to_error").html("<?=$this->lang->line('terminalreport_mail_to')?>").css(
                            "text-align", "left").css("color", 'red');
                    }

                    if (response.subject) {
                        $("#subject_error").html(
                            "<?=$this->lang->line('terminalreport_mail_subject')?>").css(
                            "text-align", "left").css("color", 'red');
                    }

                    if (response.message) {
                        toastr["error"](response.message)
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "500",
                            "hideDuration": "500",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                    }
                } else {
                    location.reload();
                }
            }
        });
    }
});
</script>