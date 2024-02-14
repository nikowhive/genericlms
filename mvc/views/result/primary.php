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

                    .container {
                        max-width: 96%;
                        margin: 30px auto;
                        width: 100%;
                    }
                    .trim-yaxis-td > tbody > tr > td:first-child {
                        padding-left: 0;
                    }
                    .trim-yaxis-td > tbody > tr > td:last-child {
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
                        background-color: #fff;
                        vertical-align: middle;
                        text-align: center;
                    }
                    .reports tr:nth-of-type(odd) td {
                        /* background-color: rgb(216, 234, 240); */
                    }
                    .reports tr:last-child td {
                        background-color: #fff;
                    }
                    .remarks-table > tbody > tr > td {
                        width: 50%;
                    }
                    .remarks {
                        border: 1px solid #000;
                    }
                    .dot-border-bottom {
                        border-bottom: 1px solid black;
                        padding: 5px 0;
                    }
                    .dot-border-top {
                        border-top: 1px solid black;
                        padding: 5px 0;
                    }

                    .remarks td {
                        border-left: 1px solid black;
                        border-right: 1px solid black;
                    }
                    .remarks td p {
                        margin-bottom: 10px;
                    }
                    .footer-td {
                        background-color: #f9f9f9;
                        border-top: 1px solid black;
                        padding: 10px 0;
                    }
                    .theader {
                        width:1000px;
                        margin:0 auto;
                    }
                    .theader td {
                        padding:0;
                    }
                    .head-mid {
                        margin:16px 0;
                    }
                    .theader h1 {
                        font-size: 32px;
                        margin-left: -50px;
                        font-weight:bold;
                    }
                    .theader h2, theader h3 {
                        margin:12px 0;
                        font-weight: 800;
                    }
                    .student-details {
                        margin-top:20px;
                    }
                    .report-canvas {
                        overflow-x: auto;
                    }
                    </style>
                    <div class="report-canvas">
                    <table class="container">
                        <tr>
                            <td>
                            <table class="theader" >
                                <tr>
                                <td align="center">
                                    <h1><?=strtoupper($siteinfos->sname)?></h1></td>
                                </tr>
                                <tr> 
                                <td>
                                    <table class="table-fluid head-mid">
                                    <tr> 
                                        <td width="200">
                                            <img
                                            src="<?=base_url("uploads/images/$siteinfos->photo")?>"
                                            width="180"
                                            height="180"
                                            alt=""
                                        />
                                        </td>
                                        <td align="center" width="530">
                                            <p style="font-size:17px;">
                                                <?=$siteinfos->address?>, Tel: <?=$siteinfos->phone?>
                                            <br>
                                                E-mail: <?=$siteinfos->email?>
                                            </p>
                                            <h2><?=strtoupper($examName)?></h2>
                                            <h3>GRADE-SHEET</h3>
                                        </td>
                                        <td></td>
                                        
                                    </tr>
                                </table>
                                </td>
                            </tr>
                            <tr> 
                                <td>
                                <table class="table-fluid student-details">
                                    <tr>
                                    <td><b>Name: <?=$student->srname?></b></td>
                                    <td><b>Class: <?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : ''?></b></td>
                                    <td><b>Section: <?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID] : ''?></b> </td>
                                    </tr>
                                    <tr>
                                    <td><b>Roll NO: <?=$student->srroll?></b></td>
                                    <td><b>DOB: <?=isset($student->dob) ? $student->dob : ''?></b></td>
                                    <td><b>Registration No.: <?=$student->srregisterNO?></b></td>
                                    <!-- <td><b>House: Laligurans</b></td> -->
                                    </tr>
                                </table>
                                </td>
                            </tr> 
                            </table>
                            </td>
                        </tr>
                        <!-- #region reports -->
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
                                        
                                    ?>
                                    <th rowspan="2" align="center">S.No.</th>
                                    <th rowspan="2" align="center" width="400">Subject</th>
                                    <th rowspan="2" align="center">Credit Hours</th>
                                    <!-- <th rowspan="2">Obtained Mark</th> -->
                                    <th colspan="3" align="center">Obtained Grade</th>
                                    <th rowspan="2" align="center">Grade Point</th>
                                    <th rowspan="2" align="center">Highest Grade Point</th>
                                </tr>

                                <tr>
                                    <th align="center">Continuous Assessment System</th>
                                    <th align="center">Periodic Assessment</th>
                                    <th align="center">Final</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $total_subject_mark = 0;
                                $total_grade_point = 0;
                                $subject_count = 0;
                                $after_first = false;
                                $i = 0;
                                if(customCompute($subjects)) { foreach($subjects as $index => $subject) {
                                    $f = true;
                                    $mark_exist = false;
                                    if(isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID])) {
                                    $subject_count = $subject_count + 1;

                                    $fullmark = $subject->finalmark;
                                    if(isset($subject_marks[$subject->subjectID]) && $subject_marks[$subject->subjectID] != '') {
                                        $fullmark = $subject_marks[$subject->subjectID];
                                    }

                                    $uniquepercentageArr =  isset($markpercentagesArr[$subject->subjectID]) ? $markpercentagesArr[$subject->subjectID] : [];
                                    ?>
                                <tr>
                                    <td align="center"><?=$i+1; $i++; ?></td>
                                    <td><?=$subject->subject ?></td>
                                    <td align="center"><?=$fullmark  == 100 ? '4': '2' ?></td>
                                    <?php
                                    $percentageMark  = 0;
                                    ?>

                                    
                                    <?php foreach($markpercentages as $markpercentageID) {
                                        $f = false;
                                        if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                            $f = true;
                                        } 
                                        if(isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {

                                            $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                            if($markpercentage == 'Continuous Assessment System' || $markpercentage == 'continuous assessment system') {
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
                                            if($markpercentage == 'Periodic Assessment' || $markpercentage == 'periodic assessment') {
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
                                    if(customCompute($grades)) {
                                        foreach($grades as $grade) {
                                            if(($grade->gradefrom <= $subjectMark) && ($grade->gradeupto >= $subjectMark)) {
                                                $mark_exist = true;
                                                $total_grade_point += $grade->point;
                                                ?>
                                                <td align="center" ><?=$grade->grade?></td>
                                                <td align="center"><?=$grade->point?></td>
                                                <?php 
                                            }
                                        } 
                                    } 
                                    if($mark_exist == false) {
                                        echo "<td align='center'>-</td>";
                                        echo "<td align='center'>-</td>";
                                    }
                                    $highest = 0;
                                    foreach($markpercentages as $index => $markpercentageID) {
                                        $f = false;
                                        if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                            $f = true;
                                        }  
                                        if(isset($highestmarks[$examID][$subject->subjectID][$markpercentageID]) && ($highestmarks[$examID][$subject->subjectID][$markpercentageID] != -1) && $f) {
                                            $highest += $highestmarks[$examID][$subject->subjectID][$markpercentageID];
                                        }
                                    }
                                    if($fullmark == 50) {
                                        $highest = floor($highest * 2);
                                    }
                                    if(customCompute($grades)) {
                                        foreach($grades as $grade) {
                                            if(($grade->gradefrom <= $highest) && ($grade->gradeupto >= $highest)) {
                                                ?>
                                                <td align="center"><?=$grade->point?></td>
                                                <?php 
                                            }
                                        } 
                                    } 
                                    if($highest == 0) {
                                        echo "<td align='center'>0</td>";
                                    }
                                    ?>
                                </tr>
                                <?php } } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="7" align="right">
                                    <b>GRADE POINTS AVERAGE (GPA): 
                                        <?php 
                                        $total_subject_mark = round($total_subject_mark / $subject_count);
                                        if(isset($studentPosition[$student->srstudentID]['classPositionMark']) && $studentPosition[$student->srstudentID]['classPositionMark'] > 0 && isset($studentPosition['totalStudentMarkAverage']) && $total_subject_mark > 1) {
                                            echo number_format($total_grade_point / $subject_count, 2);
                                            // if(customCompute($grades)) {
                                            //     foreach($grades as $grade) {
                                            //         if(($grade->gradefrom <= $total_subject_mark) && ($grade->gradeupto >= $total_subject_mark)) {
                                            //             // echo $grade->point;
                                            //         }
                                            //     } 
                                            // }
                                        } else {
                                            echo '0';
                                        }
                                    ?></b></td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                            </td>
                        </tr>
                        <!-- #endregion -->

                        <tr>
                            <td>
                                <table class="trim-yaxis-td table-fluid">
                                    <tr>
                                        <td style=" width: 70%;">
                                            <table class=" grade-table table-fluid">
                                                <thead>
                                                    <tr>
                                                        <th colspan="5">Grade Descriptions</th>
                                                    </tr>
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th width="400">Interval in Percent</th>
                                                        <th>Grade</th>
                                                        <th>Description</th>
                                                        <th>Grade Point</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>90 to 100</td>
                                                        <td>A+ </td>
                                                        <td>Outstanding </td>
                                                        <td>4.0</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>80 to Below 90</td>
                                                        <td>A </td>
                                                        <td>Excellent</td>
                                                        <td>3.6</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>70 to Below 80</td>
                                                        <td>B+ </td>
                                                        <td>Very Good</td>
                                                        <td>3.2</td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>60 to Below 70</td>
                                                        <td>B </td>
                                                        <td>Good</td>
                                                        <td>2.8</td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td>50 to Below 60</td>
                                                        <td>C+ </td>
                                                        <td>Above Average</td>
                                                        <td>2.4</td>
                                                    </tr>
                                                    <tr>
                                                        <td>6</td>
                                                        <td>40 to Below 50</td>
                                                        <td>C </td>
                                                        <td>Average</td>
                                                        <td>2.0</td>
                                                    </tr>
                                                    <tr>
                                                    <td>7</td>
                                                        <td>30 to Below 40</td>
                                                        <td>D+ </td>
                                                        <td>Partially Acceptable</td>
                                                        <td>1.6</td>
                                                    </tr>
                                                    <tr>
                                                        <td>8</td>
                                                        <td>20 to Below 30</td>
                                                        <td>D </td>
                                                        <td>Insufficient </td>
                                                        <td>1.2</td>
                                                    </tr>
                                                    <tr>
                                                        <td>9</td>
                                                        <td>1 to Below 20</td>
                                                        <td>E </td>
                                                        <td>Very Insufficient</td>
                                                        <td>0.8</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td rowspan="2">
                                            <table class="grade-table table-fluid">
                                                <thead>
                                                    <tr>
                                                    <th colspan="2">Deportments</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($coscholasticSubjects as $index => $subject) {
                                                    ?>
                                                    <tr>
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
                                                                    if($markpercentage1 == 'deportments' || $markpercentage1 == 'Deportments') { ?>
                                                                        <td><?=$subject->subject ?></td>
                                                                        <td style="text-align:center"  width="100">
                                                                        <?php 
                                                                            if(isset($studentPosition1[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f && $studentPosition1[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] != 0) {
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
                                                            } } 
                                                            ?>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <br>
                                    
                                            <table class="grade-table table-fluid">
                                                <thead>
                                                    <tr>
                                                    <th width="50">S.No.</th>
                                                    <th colspan="2">Attendance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>School Days</td>
                                                        <td width="100" align="center"><?=getSchoolDays($class->classes_numeric); ?></td>
                                                        </tr>
                                                        <tr>
                                                        <td>2</td>
                                                        <td>Present Days</td>
                                                        <td  width="100" align="center"><?=isset($student->presentdays) ? $student->presentdays : 0 ?></td>
                                                        </tr>
                                                        <tr>
                                                        <td>3</td>
                                                        <td>Absent Days</td>
                                                        <td  width="100" align="center"><?=isset($student->presentdays) ? getSchoolDays($class->classes_numeric) - $student->presentdays : getSchoolDays($class->classes_numeric); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                    
                                        </td>
                                    </tr>
                               
                            </table>
                            </td>
                        </tr>



                        <!-- #region departments, attendence, remarks -->

                        <tr>
                            <td>
                            <table class="trim-yaxis-td remarks-table table-fluid">
                                <tr>
                                
                                    <td style="width: 70%;">
                                        <table class="remarks table-fluid">
                                        <tr>
                                            <td colspan="3">
                                            <h4><b>Remarks:</b></h4>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                            <?php 
                                                if(isset($remarks[$student->studentID])) { ?>
                                                    <p><?=$remarks[$student->studentID] ?></p>
                                                <?php } else { ?>
                                                    <p >&nbsp;</p>
                                                    <p >&nbsp;</p>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        </table>
                                    </td>
                                </tr>
                               
                            </table>
                            </td>
                        </tr>
                        <!-- #endregion -->

                        <!-- #region verify, class teachers sign, issue, priciple   -->
                        <tr>
                            <td align="center" >
                                <table  class="table-fluid" width="900">
                                    <tr>
                                    
                            
                                        <td align="center" width="300">
                                            <table>
                                            <tr><td height="80"  style="vertical-align: bottom;"><?=isset($exam->issue_date) ? $exam->issue_date : '' ?></td></tr>
                                            <tr><td  align="center" class="dot-border-top">Issue Date</td></tr>
                                            </table>
                                            
                                        </td>
                                        
                                        
                                        <td align="center" width="300">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <?="<img height='60' src=".getClassTeacherSignature($class->classes_numeric, $sections[$student->srsectionID])." />"; ?>
                                                    </td>
                                                </tr>
                                                <tr><td align="center" class="dot-border-top"><?=isset($class_teacher[$student->srsectionID]) ? $class_teacher[$student->srsectionID] : ''?></td></tr> 
                                                <tr><td  align="center">Class Teacher</td></tr>
                                            </table>
                                    
                                        </td>



                                        <td align="center" width="300">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <?="<img height='60' src=".getVerifiedSignature($class->classes_numeric)." />"; ?>
                                                    </td>
                                                </tr>
                                                <?=getVerifiedBy($class->classes_numeric) ?>
                                                <tr><td  align="center">Co-ordinator</td></tr>
                                            </table>
                                    
                                        </td>
                                        
                                        <td align="center" width="300">
                                            <table>
                                            <tr>
                                                <td>
                                                <?php
                                                if($setting->enable_principal_signature == 'YES') {
                                                    if(customCompute($setting->principal)) {
                                                        echo "<img height='60' src=".base_url('uploads/images/'.$setting->principal)." />";
                                                    }
                                                } else { ?>
                                                    <img height='60' src="uploads/images/site.png" style="visibility:hidden"/>
                                                <?php } ?>
                                            </td>
                                            </tr>
                                            <tr><td align="center"  class="dot-border-top"><?= customCompute($setting->principal_name) ? $setting->principal_name : '' ?></td></tr> 
                                            <tr>
                                                <td  align="center">Principal</td>
                                            </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        
                        <!-- #endregion -->
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