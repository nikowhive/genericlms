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
                            <thead>
                                <tr>
                                <td align="center">
                                    <table width="900">
                                    <tr>
                                        <td valign="top">
                                        <img
                                            src="<?=base_url("uploads/images/$siteinfos->photo")?>"
                                            width="200"
                                            height="150"
                                            alt=""/>
                                        </td>
                                        <td>
                                        <table>
                                            <tr>
                                            <td align="center">
                                                <h1><?=strtoupper($siteinfos->sname)?></h1>
                                                <br>
                                                <p><?=$siteinfos->address?></p>
                                                <p>Ph: <?=$siteinfos->phone?></p>
                                            </td>
                                            </tr>
                                        </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" colspan="2" style="padding-top:30px">
                                        <h3>GRADE SHEET</h3>
                                        </td>
                                    </tr>
                                    </table>
                                </td>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                <td class="description">
                                    THE FOLLOWING ARE THE GRADE(S) OBTAINED BY <b><?=strtoupper($student->srname)?></b>. DATE OF
                                    BIRTH: <b><?=isset($student->dob_in_bs) ? strtoupper($student->dob_in_bs) : '................'?></b> B.S. ( <b class="dot-border-bottom"></b><?=isset($student->dob) ? strtoupper($student->dob) : '................'?> A.D.). REGISTRATION NO.:
                                    <b><?=strtoupper($student->srregisterNO)?></b> SYMBOL NO.: <b><?=strtoupper($student->remarks)?></b> GRADE: <b><?=isset($classes[$student->srclassesID]) && $classes[$student->srclassesID] == "Eleven" ? 'XI' : 'XII'?></b> IN THE ANNUAL
                                    EXAMINATION CONDUCTED BY SCHOOL/CAMPUS IN <b><?=isset($exam->date_in_nepali) ? date('Y', strtotime($exam->date_in_nepali)) : '.............' ?></b> B.S (<b
                                    ><?=isset($exam->date) ? date('Y', strtotime($exam->date)) : '.............' ?></b>  
                                    A.D.) ARE GIVEN BELOW:
                                </td>
                                </tr>

                                <tr>
                                <td>
                                    <table class="table-fluid table-bordered">
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
                                        <th>Subject Code</th>
                                        <th>Subjects</th>
                                        <th>Credit Hours</th>
                                        <th>Grade Point</th>
                                        <th>Grade</th>
                                        <th>Final Grade</th>
                                        <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $total_subject_mark = 0;
                                    $total_grade_point = 0;
                                    $total_credit_hours = 0;
                                    $credit_hours_x_grade_point = 0;
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
                                            <td><?=$subject->subject_code ?></td>
                                            <td><?=$subject->subject ?></td>
                                            <td align="center"><?=$subject->credit ?></td>
                                            <?php 
                                            if($subject->credit != '') {
                                                $total_credit_hours += $subject->credit; 
                                            }
                                            $mark_exist = false;
                                            foreach($markpercentages as $markpercentageID) {
                                                $f = false;
                                                if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                                    $f = true;
                                                } 
                                                if(isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {

                                                    $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                                    if($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] != 0) {
                                                        $practical_part = ($percentageArr[$markpercentageID]->percentage/100) * $fullmark;
                                                        $percentage = floor(($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]/$practical_part)*100);
                                                        if(customCompute($grades)) {
                                                            foreach($grades as $grade) {
                                                                if(($grade->gradefrom <= $percentage) && ($grade->gradeupto >= $percentage)) {
                                                                    $type = $markpercentage;
                                                                    $mark_exist = true;
                                                                    ?>
                                                                    <td align="center"><?=$grade->point?></td>
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
                                            if($mark_exist == false) {
                                                echo "<td align='center'>-</td>";
                                                echo "<td align='center'>-</td>";
                                            }

                                            $percentageMark  = 0;

                                            $subjectMark = isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID] : '0';                                    
                                    
                                            $subjectMark = markCalculationView($subjectMark, $fullmark, $percentageMark);
        
                                            $total_subject_mark += $subjectMark;
                                            if(customCompute($grades)) {
                                                foreach($grades as $grade) {
                                                    if(($grade->gradefrom <= $subjectMark) && ($grade->gradeupto >= $subjectMark)) {
                                                        $mark_exist = true;
                                                        $total_grade_point += $grade->point;
                                                        if($subject->credit != '') {
                                                            $credit_hours_x_grade_point += ($subject->credit * $grade->point);
                                                        } else {
                                                            $credit_hours_x_grade_point += 0;
                                                        }
                                                        // get theory subject with (th) in the end
                                                        // if found store it
                                                        // and find same subject with (pr) in the end
                                                        // add and show
                                                        if($type == 'th' || $type == 'Th' || $type == 'Theory' || $type == 'theory') {
                                                        ?>
                                                        <td align="center" ><?=$grade->grade?></td>
                                                        <?php } else { ?>
                                                            <td align="center" ></td>
                                                        <?php } ?>
                                                        <?php 
                                                    }
                                                } 
                                            }  
                                            if($mark_exist == false) {
                                                echo "<td align='center'>-</td>";
                                            }
                                        ?>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>

                                        <tr>
                                        <td></td>
                                        <td align="right">Total</td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="3">Grade Point Average (GPA): 
                                        <?php
                                            $total_subject_mark = round($total_subject_mark / $subject_count);
                                            if(isset($studentPosition[$student->srstudentID]['classPositionMark']) && $studentPosition[$student->srstudentID]['classPositionMark'] > 0 && isset($studentPosition['totalStudentMarkAverage']) && $total_subject_mark > 1) {
                                                echo number_format($credit_hours_x_grade_point / $total_credit_hours, 2);
                                                // echo number_format($total_grade_point / $subject_count, 2);
                                            } else {
                                                echo '0';
                                            }
                                        ?>
                                        </td>
                                        </tr>
                                    </tfoot>
                                    </table>
                                </td>
                                </tr>

                                <tr>
                                <td>
                                    <table class="table-fluid">
                                    <tr>
                                        <td colspan="3">PREPARED BY:</td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <table>
                                            <tr>
                                            <td align="center">
                                            <?="<img width='150' src=".getClassTeacherSignature($class->classes_numeric, $sections[$student->srsectionID])." />"; ?>
                                            </td>
                                            </tr>
                                            <tr>
                                            <td class="dot-border-top" align="center">
                                                CHECKED BY <br />
                                                (CLASS TEACHER)
                                            </td>
                                            </tr>
                                        </table>
                                        </td>
                                        <td></td>
                                        <td align="right">
                                        <table>
                                            <tr>
                                            <td align="center">
                                                <?php
                                                if($setting->enable_principal_signature == 'YES') {
                                                    if(customCompute($setting->principal)) {
                                                        echo "<img width='150' src=".base_url('uploads/images/'.$setting->principal)." />";
                                                    }
                                                } else { ?>
                                                    <img width='150' src="uploads/images/site.png" style="visibility:hidden"/>
                                                <?php } ?>
                                            </td>
                                            </tr>
                                            <tr>
                                            <td class="dot-border-top" align="center">
                                                HEAD MASTER / CAMPUS CHIEF
                                            </td>
                                            </tr>
                                        </table>
                                        </td>
                                    </tr>
                                    </table>
                                </td>
                                </tr>
                                <tr>
                                <td>DATE OF ISSUE: <?=isset($exam->issue_date_in_english) ? $exam->issue_date_in_english : '.............' ?></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                <td>
                                    <h4>NOTE: ONE CREDIT HOURS EQUALS 32 CLOCK HOURS</h4>
                                    TH = THEORY &emsp; &emsp;&emsp; &emsp;&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp;PR = PRACTICAL &emsp; &emsp;&emsp; &emsp;&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp;XC = EXPELLED <br />
            ABS = ABSENT&emsp; &emsp;&emsp; &emsp;&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp;W = WITHHELD
                                </td>
                                </tr>
                            </tfoot>
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
